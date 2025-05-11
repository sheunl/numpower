--TEST--
NumPower::arcsin
--FILE--
<?php
$a = NumPower::array([[0, -0.5], [0, -0.5]]);
print_r(NumPower::arcsin($a)->toArray());
print_r(NumPower::arcsin($a[0])->toArray());
print_r(NumPower::arcsin([[0],[-0.5]])->toArray());
?>
--EXPECT--
Array
(
    [0] => Array
        (
            [0] => 0
            [1] => -0.52359879016876
        )

    [1] => Array
        (
            [0] => 0
            [1] => -0.52359879016876
        )

)
Array
(
    [0] => 0
    [1] => -0.52359879016876
)
Array
(
    [0] => Array
        (
            [0] => 0
        )

    [1] => Array
        (
            [0] => -0.52359879016876
        )

)