--TEST--
NumPower::array
--FILE--
<?php
$a = NumPower::array([[1, 2], [3, 4]]);
print_r($a->toArray());
$a = NumPower::array([]);
print_r($a->toArray());
$a = new NDArray([[1, 2], [3, 4]]);
print_r($a->toArray());
$a = new NDArray([]);
print_r($a->toArray());
?>
--EXPECT--
Array
(
    [0] => Array
        (
            [0] => 1
            [1] => 2
        )

    [1] => Array
        (
            [0] => 3
            [1] => 4
        )

)
Array
(
)
Array
(
    [0] => Array
        (
            [0] => 1
            [1] => 2
        )

    [1] => Array
        (
            [0] => 3
            [1] => 4
        )

)
Array
(
)