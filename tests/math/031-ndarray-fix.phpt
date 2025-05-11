--TEST--
NumPower::fix
--FILE--
<?php
$a = NumPower::array([[-156.50, 150.525435], [0, -39.151414]]);
print_r(NumPower::fix($a)->toArray());
print_r(NumPower::fix($a[0])->toArray());
print_r(NumPower::fix([[0.12],[-0.513124]])->toArray());
?>
--EXPECT--
Array
(
    [0] => Array
        (
            [0] => -156
            [1] => 150
        )

    [1] => Array
        (
            [0] => 0
            [1] => -39
        )

)
Array
(
    [0] => -156
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
            [0] => -0
        )

)