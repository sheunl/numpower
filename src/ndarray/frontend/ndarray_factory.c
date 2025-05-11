// Z_TYPE_P,            IS_ARRAY,         IS_LONG,  IS_DOUBLE, IS_OBJECT,
// Z_OBJ_P,             Z_LVAL_P,         Z_DVAL_P, Z_OBJCE_P, ZSTR_VAL,
// instanceof_function, zend_throw_error
#include <Zend/zend_interfaces.h>

// ce, phpsci_ce_NDArray
#include "../../../php_numpower.h"

// NDARRAY_TYPE_FLOAT32, NDARRAY_TYPE_DOUBLE64
#include "../../types.h"

// buffer_get
#include "../../buffer.h"

// NDArray
#include "ndarray_factory.h"

//PRIVATE

/**
 * @brief Get number of dimensions from php array
 *
 * @param ht A pointer to the zval array value
 * 
 * @return The number of dimensions in the array
 */
int _getNumDimsFromZval(zend_array *zendArray)
{
    int num_dims = 0;

    if (zend_array_count(zendArray) == 0) {
        return 1;
    }

    zval *val = zend_hash_index_find(zendArray, 0);

    while (val && Z_TYPE_P(val) == IS_ARRAY) {
        num_dims++;
        val = zend_hash_index_find(Z_ARRVAL_P(val), 0);
    }

    return num_dims + 1;
}

/**
 * @brief Check if the zend array is packed
 *
 * @param arr A pointer to the zend array
 * 
 * @return 1 if the array is packed, 0 otherwise
 */
bool _isPackedZendArray(zend_array *zendArray) {
    return zendArray->nNumUsed == zendArray->nNumOfElements;
}

/**
 * @brief Count the shape of a zend array
 *
 * @param[in]    zendArray A pointer to the zend array
 * @param[inout] shape     A pointer to the shape array
 * @param[in]    ndim      The number of dimensions
 */
void _countZendArrayShape(zend_array* zendArray, int* shape, int ndim) {
    int i;

    if (shape == NULL && ndim != 0) {
        return;
    }

    if (zend_array_count(zendArray) == 0) {
        return;
    }

    // Initialize shape array to zeros
    for (i = 0; i < ndim; i++) {
        shape[i] = 0;
    }

    // Traverse the zend array to get the shape
    zval* val;
    ZEND_HASH_FOREACH_VAL(zendArray, val) {
        if (Z_TYPE_P(val) == IS_ARRAY) {
            _countZendArrayShape(Z_ARRVAL_P(val), shape + 1, ndim - 1);
            shape[0]++;
        } else {
            shape[0]++;
        }
    }
    ZEND_HASH_FOREACH_END();
}

/**
 * @brief Create a buffer for the NDArray
 *
 * @param[inout] ndarray     A pointer to the NDArray
 * @param        numElements The number of elements in the array
 * @param        elsize      The size of each element in bytes
 */
void _createBuffer(NDArray* ndarray, int numElements, int elsize) {
    ndarray->data = emalloc(numElements * elsize);
}

/**
 * @brief Fill the NDArray from a zend array
 *
 * @param[inout] ndarray     A pointer to the NDArray
 * @param[in]    zendArray   A pointer to the zend array
 * @param[inout] firstIndex  A pointer to the first index
 */
void _fillFromZendArray(NDArray* ndarray, zend_array* zendArray, int* firstIndex) {
    zval * element;

    ZEND_HASH_FOREACH_VAL(zendArray, element) {
        ZVAL_DEREF(element);
        switch (Z_TYPE_P(element)) {
            case IS_ARRAY:
                _fillFromZendArray(ndarray, Z_ARRVAL_P(element), firstIndex);
                break;
            case IS_LONG:
                if (NDArray_TYPE(ndarray) == NDARRAY_TYPE_FLOAT32) {
                    float* data_float;
                    data_float = NDArray_FDATA(ndarray);
                    data_float[*firstIndex] = (float) zval_get_long(element);
                } else if (NDArray_TYPE(ndarray) == NDARRAY_TYPE_DOUBLE64) {
                    double* data_double;
                    data_double = NDArray_DDATA(ndarray);
                    data_double[*firstIndex] = zval_get_long(element);
                }
            case IS_DOUBLE:
                if (NDArray_TYPE(ndarray) == NDARRAY_TYPE_FLOAT32) {
                    float* data_float;
                    data_float = NDArray_FDATA(ndarray);
                    data_float[*firstIndex] = (float) zval_get_double(element);
                } else if (NDArray_TYPE(ndarray) == NDARRAY_TYPE_DOUBLE64) {
                    double* data_double;
                    data_double = NDArray_DDATA(ndarray);
                    data_double[*firstIndex] = zval_get_double(element);
                }
                *firstIndex = *firstIndex + 1;
                break;
            case IS_TRUE:
                if (NDArray_TYPE(ndarray) == NDARRAY_TYPE_FLOAT32) {
                    float* data_float;
                    data_float = NDArray_FDATA(ndarray);
                    data_float[*firstIndex] = (float) 1.0;
                } else if (NDArray_TYPE(ndarray) == NDARRAY_TYPE_DOUBLE64) {
                    double* data_double;
                    data_double = NDArray_DDATA(ndarray);
                    data_double[*firstIndex] = (double) 1.0;
                }
                *firstIndex = *firstIndex + 1;
                break;
            case IS_FALSE:
                if (NDArray_TYPE(ndarray) == NDARRAY_TYPE_FLOAT32) {
                    float* data_float;
                    data_float = NDArray_FDATA(ndarray);
                    data_float[*firstIndex] = (float) 0.0;
                } else if (NDArray_TYPE(ndarray) == NDARRAY_TYPE_DOUBLE64) {
                    double* data_double;
                    data_double = NDArray_DDATA(ndarray);
                    data_double[*firstIndex] = (double) 0.0;
                }
                *firstIndex = *firstIndex + 1;
                break;
            default:
                zend_throw_error(NULL, "an element with an invalid type was used at initialization");
                return;
        }
    }
    ZEND_HASH_FOREACH_END();
}

/**
 * @brief Creates an NDArray object from a zval php array.
 *
 * This function takes a PHP array (zval) and creates an NDArray from it.
 * The type parameter is used to specify the desired data type of the NDArray.
 *
 * @param[in] ht    A pointer to the zval array value to be converted to an NDArray.
 * @param     ndim  A number of PHP array dimensions.
 * @param[in] type  A string representing the desired data type for the NDArray.
 * 
 * @return A pointer to the newly created NDArray, or NULL if the zval is not an array.
 */
NDArray* _createFromZendArray(zend_array* ht, const char *type)
{
    int last_index = 0;
    int *shape;

    int ndim = _getNumDimsFromZval(ht);

    if (ndim != 0) {
        shape = ecalloc(ndim, sizeof(int));
    } else {
        shape = ecalloc(1, sizeof(int));
    }

    if (!_isPackedZendArray(ht)) {
        return NULL;
    }

    _countZendArrayShape(ht, shape, ndim);
    int total_num_elements = shape[0];

    // Calculate number of elements
    for (int i = 1; i < ndim; i++) {
        total_num_elements = total_num_elements * shape[i];
    }

    NDArray* array = NDArray_create(shape, ndim, type, NDARRAY_DEVICE_CPU);

    add_to_buffer(array);
    
    if (ndim != 0) {
        _createBuffer(array, total_num_elements, get_type_size(type));
        _fillFromZendArray(array, ht, &last_index);
    } else {
        array->data = NULL;
        array->descriptor->numElements = 0;
    }

    return array;
}

/**
 * @brief Creates a float32 NDArray from a long scalar.
 *
 * @param scalar The long scalar value to be converted to a float32 NDArray.
 * 
 * @return A pointer to the newly created NDArray.
 */
NDArray* _createFloat32FromLongScalar(long scalar)
{
    NDArray *rtn = safe_emalloc(1, sizeof(NDArray), 0);

    rtn->ndim = 0;
    rtn->descriptor = emalloc(sizeof(NDArrayDescriptor));
    rtn->descriptor->numElements = 1;
    rtn->descriptor->elsize = sizeof(float);
    rtn->descriptor->type = NDARRAY_TYPE_FLOAT32;
    rtn->data = emalloc(sizeof(float));
    rtn->device = NDARRAY_DEVICE_CPU;
    rtn->strides = emalloc(sizeof(int));
    rtn->dimensions = emalloc(sizeof(int));
    rtn->iterator = NULL;
    rtn->base = NULL;
    rtn->refcount = 1;
    ((float*)rtn->data)[0] = (float)scalar;
    
    add_to_buffer(rtn);

    return rtn;
}

/**
 * @brief Creates a float32 NDArray from a double scalar.
 * 
 * @param scalar The double scalar value to be converted to a float32 NDArray.
 * 
 * @return A pointer to the newly created NDArray.
 */
NDArray* _createFloat32FromDoubleScalar(double scalar)
{
    NDArray *rtn = safe_emalloc(1, sizeof(NDArray), 0);

    rtn->ndim = 0;
    rtn->descriptor = emalloc(sizeof(NDArrayDescriptor));
    rtn->descriptor->numElements = 1;
    rtn->descriptor->elsize = sizeof(float);
    rtn->descriptor->type = NDARRAY_TYPE_FLOAT32;
    rtn->data = emalloc(sizeof(float));
    rtn->device = NDARRAY_DEVICE_CPU;
    rtn->strides = emalloc(sizeof(int));
    rtn->dimensions = emalloc(sizeof(int));
    rtn->iterator = NULL;
    rtn->base = NULL;
    rtn->refcount = 1;
    ((float*)rtn->data)[0] = (float)scalar;

    add_to_buffer(rtn);

    return rtn;
}

/**
 * @brief Creates a double64 NDArray from a long scalar.
 * 
 * @param scalar The long scalar value to be converted to a double64 NDArray.
 * 
 * @return A pointer to the newly created NDArray.
 */
NDArray* _createDouble64FromLongScalar(long scalar)
{
    NDArray *rtn = safe_emalloc(1, sizeof(NDArray), 0);

    rtn->ndim = 0;
    rtn->descriptor = emalloc(sizeof(NDArrayDescriptor));
    rtn->descriptor->numElements = 1;
    rtn->descriptor->elsize = sizeof(double);
    rtn->descriptor->type = NDARRAY_TYPE_DOUBLE64;
    rtn->data = emalloc(sizeof(double));
    rtn->device = NDARRAY_DEVICE_CPU;
    rtn->strides = emalloc(sizeof(int));
    rtn->dimensions = emalloc(sizeof(int));
    rtn->iterator = NULL;
    rtn->base = NULL;
    rtn->refcount = 1;
    ((double*)rtn->data)[0] = (double)scalar;

    add_to_buffer(rtn);

    return rtn;
}

/**
 * @brief Creates a double64 NDArray from a double scalar.
 * 
 * @param scalar The double scalar value to be converted to a double64 NDArray.
 * 
 * @return A pointer to the newly created NDArray.
 */
NDArray* _createDouble64FromDoubleScalar(double scalar)
{
    NDArray *rtn = safe_emalloc(1, sizeof(NDArray), 0);

    rtn->ndim = 0;
    rtn->descriptor = emalloc(sizeof(NDArrayDescriptor));
    rtn->descriptor->numElements = 1;
    rtn->descriptor->elsize = sizeof(double);
    rtn->descriptor->type = NDARRAY_TYPE_DOUBLE64;
    rtn->data = emalloc(sizeof(double));
    rtn->device = NDARRAY_DEVICE_CPU;
    rtn->strides = emalloc(sizeof(int));
    rtn->dimensions = emalloc(sizeof(int));
    rtn->iterator = NULL;
    rtn->base = NULL;
    rtn->refcount = 1;
    ((double*)rtn->data)[0] = scalar;

    add_to_buffer(rtn);

    return rtn;
}

// PUBLIC

/**
 * @brief Get the UUID of an object.
 * 
 * @param obj A pointer to the zval object.
 * 
 * @return The UUID of the object.
 */
int getObjectUuid(zval* obj) {
    return Z_LVAL_P(OBJ_PROP_NUM(Z_OBJ_P(obj), 0));
}

/**
 * @brief Creates an NDArray object from a zval php array.
 * 
 * @param[in] obj  A pointer to the zval object to be converted to an NDArray.
 * @param[in] type A string representing the desired data type for the NDArray.
 * 
 * @return A pointer to the newly created NDArray, or NULL if the zval is not an array.
 */
NDArray* NDArrayFactory_createFromZval(zval* obj, const char* type)
{
    if (Z_TYPE_P(obj) == IS_ARRAY) {
        return _createFromZendArray(Z_ARRVAL_P(obj), type);
    }

    if (Z_TYPE_P(obj) == IS_LONG && type == NDARRAY_TYPE_FLOAT32) {
        return _createFloat32FromLongScalar(Z_LVAL_P(obj));
    }

    if (Z_TYPE_P(obj) == IS_DOUBLE && type == NDARRAY_TYPE_FLOAT32) {
        return _createFloat32FromDoubleScalar(Z_DVAL_P(obj));
    }

    if (Z_TYPE_P(obj) == IS_LONG && type == NDARRAY_TYPE_DOUBLE64) {
        return _createDouble64FromLongScalar(Z_LVAL_P(obj));
    }

    if (Z_TYPE_P(obj) == IS_DOUBLE && type == NDARRAY_TYPE_DOUBLE64) {
        return _createDouble64FromDoubleScalar(Z_DVAL_P(obj));
    }
    if (Z_TYPE_P(obj) == IS_OBJECT) {
        zend_class_entry *ce = Z_OBJCE_P(obj);
        if (instanceof_function(ce, phpsci_ce_NDArray)) {
            return buffer_get(getObjectUuid(obj));
        }
#ifdef HAVE_GD
        zend_string* class_name = Z_OBJ_P(obj)->ce->name;
        if (strcmp(ZSTR_VAL(class_name), "GdImage") == 0) {

            NDArray* ndarray = NDArray_FromGD(obj, false);
            add_to_buffer(ndarray);
            return ndarray;
        }
#endif
    }

    zend_throw_error(NULL, "Argument must be an array if numerics, float, int, GdImage or NDArray.");
    return NULL;
}

/**
 * @brief Restores an NDArray from a zval object.
 * 
 * @param zvalNdarray A pointer to the zval object to be restored.
 * 
 * @return A pointer to the restored NDArray, or NULL if the zval is not an NDArray.
 */
NDArray* NDArrayFactory_restoreFromZval(zval* zvalNdarray)
{
    if (Z_TYPE_P(zvalNdarray) == IS_OBJECT) {
        zend_class_entry *ce = Z_OBJCE_P(zvalNdarray);
        if (instanceof_function(ce, phpsci_ce_NDArray)) {
            return buffer_get(getObjectUuid(zvalNdarray));
        }
    }

    zend_throw_error(NULL, "Argument must be an NDArray.");
    return NULL;
}