--TEST--
NumPower::clip
--FILE--
<?php
$a = NumPower::array([[-156, 150], [19, -39]]);
print_r(NumPower::clip($a, min: 0, max: 10)->toArray());
print_r(NumPower::clip($a[0], min: 0, max: 10)->toArray());
print_r(NumPower::clip([[0],[-0.5]], min: 0, max: 10)->toArray());
?>
--EXPECT--
Array
(
    [0] => Array
        (
            [0] => 0
            [1] => 10
        )

    [1] => Array
        (
            [0] => 10
            [1] => 0
        )

)
Array
(
    [0] => 0
    [1] => 10
)
Array
(
    [0] => Array
        (
            [0] => 0
        )

    [1] => Array
        (
            [0] => 0
        )

)