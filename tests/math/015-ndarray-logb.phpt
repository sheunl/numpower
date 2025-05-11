--TEST--
NumPower::logb
--FILE--
<?php
$a = NumPower::array([[1, 2], [3, 4]]);
print_r(NumPower::logb($a)->toArray());
print_r(NumPower::logb($a[0])->toArray());
print_r(NumPower::logb([[1],[2]])->toArray());
?>
--EXPECT--
Array
(
    [0] => Array
        (
            [0] => 0
            [1] => 1
        )

    [1] => Array
        (
            [0] => 1
            [1] => 2
        )

)
Array
(
    [0] => 0
    [1] => 1
)
Array
(
    [0] => Array
        (
            [0] => 0
        )

    [1] => Array
        (
            [0] => 1
        )

)