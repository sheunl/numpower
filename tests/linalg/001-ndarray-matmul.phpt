--TEST--
NumPower::matmul
--FILE--
<?php
$a = NumPower::array([[1, 2], [3, 4]]);
$b = NumPower::array([[5, 6], [7, 8]]);
$c = NumPower::array([9, 10]);
$d = NumPower::array([[9], [10]]);
print_r(NumPower::matmul($a, $b)->toArray());
print_r(NumPower::matmul([[1, 2], [3, 4]], [[5, 6], [7, 8]])->toArray());
print_r(NumPower::matmul($a, $d)->toArray());
?>
--EXPECT--
Array
(
    [0] => Array
        (
            [0] => 19
            [1] => 22
        )

    [1] => Array
        (
            [0] => 43
            [1] => 50
        )

)
Array
(
    [0] => Array
        (
            [0] => 19
            [1] => 22
        )

    [1] => Array
        (
            [0] => 43
            [1] => 50
        )

)
Array
(
    [0] => Array
        (
            [0] => 29
        )

    [1] => Array
        (
            [0] => 67
        )

)