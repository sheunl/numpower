--TEST--
NumPower::abs
--FILE--
<?php
$a = NumPower::array([[0, -0.5], [0, -0.5]]);
print_r(NumPower::abs($a)->toArray());
print_r(NumPower::abs($a[0])->toArray());
print_r(NumPower::abs([[0],[-0.5]])->toArray());
?>
--EXPECT--
Array
(
    [0] => Array
        (
            [0] => 0
            [1] => 0.5
        )

    [1] => Array
        (
            [0] => 0
            [1] => 0.5
        )

)
Array
(
    [0] => 0
    [1] => 0.5
)
Array
(
    [0] => Array
        (
            [0] => 0
        )

    [1] => Array
        (
            [0] => 0.5
        )

)