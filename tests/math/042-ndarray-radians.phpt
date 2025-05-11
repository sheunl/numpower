--TEST--
NumPower::radians
--FILE--
<?php
$a = NumPower::array([[0, -0.5], [0, -0.5]]);
print_r(NumPower::radians($a)->toArray());
print_r(NumPower::radians($a[0])->toArray());
print_r(NumPower::radians([[0],[-0.5]])->toArray());
?>
--EXPECT--
Array
(
    [0] => Array
        (
            [0] => 0
            [1] => -0.0087266461923718
        )

    [1] => Array
        (
            [0] => 0
            [1] => -0.0087266461923718
        )

)
Array
(
    [0] => 0
    [1] => -0.0087266461923718
)
Array
(
    [0] => Array
        (
            [0] => 0
        )

    [1] => Array
        (
            [0] => -0.0087266461923718
        )

)