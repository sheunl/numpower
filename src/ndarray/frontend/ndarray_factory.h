#ifndef PHPSCI_NDARRAY_FACTORY_H
#define PHPSCI_NDARRAY_FACTORY_H

#include "../../ndarray.h"

int getObjectUuid(zval* obj);
NDArray* NDArrayFactory_createFromZval(zval* obj, const char* type);
NDArray* NDArrayFactory_restoreFromZval(zval* obj);

#endif //PHPSCI_NDARRAY_FACTORY_H