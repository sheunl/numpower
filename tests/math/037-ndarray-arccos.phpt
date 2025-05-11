--TEST--
NumPower::arccos
--FILE--
<?php
$a = NumPower::array([[0, -0.5], [0, -0.5]]);
print_r(NumPower::arccos($a)->toArray());
print_r(NumPower::arccos($a[0])->toArray());
print_r(NumPower::arccos([[0],[-0.5]])->toArray());
?>
--EXPECT--
Array
(
    [0] => Array
        (
            [0] => 1.5707963705063
            [1] => 2.094395160675
        )

    [1] => Array
        (
            [0] => 1.5707963705063
            [1] => 2.094395160675
        )

)
Array
(
    [0] => 1.5707963705063
    [1] => 2.094395160675
)
Array
(
    [0] => Array
        (
            [0] => 1.5707963705063
        )

    [1] => Array
        (
            [0] => 2.094395160675
        )

)