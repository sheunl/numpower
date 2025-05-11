--TEST--
NumPower::sin
--FILE--
<?php
$a = NumPower::array([[0, -0.5], [0, -0.5]]);
print_r(NumPower::sin($a)->toArray());
print_r(NumPower::sin($a[0])->toArray());
print_r(NumPower::sin([[0],[-0.5]])->toArray());
?>
--EXPECT--
Array
(
    [0] => Array
        (
            [0] => 0
            [1] => -0.47942554950714
        )

    [1] => Array
        (
            [0] => 0
            [1] => -0.47942554950714
        )

)
Array
(
    [0] => 0
    [1] => -0.47942554950714
)
Array
(
    [0] => Array
        (
            [0] => 0
        )

    [1] => Array
        (
            [0] => -0.47942554950714
        )

)