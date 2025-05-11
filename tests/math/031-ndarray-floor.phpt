--TEST--
NumPower::floor
--FILE--
<?php
$a = NumPower::array([[-156.50, 150.525435], [0, -39.151414]]);
print_r(NumPower::floor($a)->toArray());
print_r(NumPower::floor($a[0])->toArray());
print_r(NumPower::floor([[0.12],[-0.513124]])->toArray());
?>
--EXPECT--
Array
(
    [0] => Array
        (
            [0] => -157
            [1] => 150
        )

    [1] => Array
        (
            [0] => 0
            [1] => -40
        )

)
Array
(
    [0] => -157
    [1] => 150
)
Array
(
    [0] => Array
        (
            [0] => 0
        )

    [1] => Array
        (
            [0] => -1
        )

)