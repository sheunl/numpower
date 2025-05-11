--TEST--
NumPower::inv
--FILE--
<?php
$a = NumPower::array([[1, 2], [3, 4]]);
$b = NumPower::array([[5, 6], [7, 8]]);
$c = NumPower::array([9, 10]);
$d = NumPower::array([[9], [10]]);
print_r(NumPower::inv($a)->toArray());
print_r(NumPower::inv([[1, 2], [3, 4]])->toArray());
?>
--EXPECT--
Array
(
    [0] => Array
        (
            [0] => -2
            [1] => 1
        )

    [1] => Array
        (
            [0] => 1.5
            [1] => -0.5
        )

)
Array
(
    [0] => Array
        (
            [0] => -2
            [1] => 1
        )

    [1] => Array
        (
            [0] => 1.5
            [1] => -0.5
        )

)