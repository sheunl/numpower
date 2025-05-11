--TEST--
NumPower::notEqual
--FILE--
<?php
$a = NumPower::array([[1, 2], [3, 4]]);
$b = NumPower::array([[5, 6], [7, 8]]);
$c = NumPower::array([9, 10]);
print_r(NumPower::notEqual($a, $b)->toArray());
print_r(NumPower::notEqual($a, $a)->toArray());
print_r(NumPower::notEqual($c, $c)->toArray());
?>
--EXPECT--
Array
(
    [0] => Array
        (
            [0] => 1
            [1] => 1
        )

    [1] => Array
        (
            [0] => 1
            [1] => 1
        )

)
Array
(
    [0] => Array
        (
            [0] => 0
            [1] => 0
        )

    [1] => Array
        (
            [0] => 0
            [1] => 0
        )

)
Array
(
    [0] => 0
    [1] => 0
)