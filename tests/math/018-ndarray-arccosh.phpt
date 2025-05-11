--TEST--
NumPower::arccosh
--FILE--
<?php
$a = NumPower::array([[1, 2], [3, 4]]);
print_r(NumPower::arccosh($a)->toArray());
print_r(NumPower::arccosh($a[0])->toArray());
print_r(NumPower::arccosh([[1],[2]])->toArray());
?>
--EXPECT--
Array
(
    [0] => Array
        (
            [0] => 0
            [1] => 1.316957950592
        )

    [1] => Array
        (
            [0] => 1.762747168541
            [1] => 2.0634369850159
        )

)
Array
(
    [0] => 0
    [1] => 1.316957950592
)
Array
(
    [0] => Array
        (
            [0] => 0
        )

    [1] => Array
        (
            [0] => 1.316957950592
        )

)