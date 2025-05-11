--TEST--
NumPower::tan
--FILE--
<?php
$a = NumPower::array([[0, -0.5], [0, -0.5]]);
print_r(NumPower::tan($a)->toArray());
print_r(NumPower::tan($a[0])->toArray());
print_r(NumPower::tan([[0],[-0.5]])->toArray());
?>
--EXPECT--
Array
(
    [0] => Array
        (
            [0] => 0
            [1] => -0.54630249738693
        )

    [1] => Array
        (
            [0] => 0
            [1] => -0.54630249738693
        )

)
Array
(
    [0] => 0
    [1] => -0.54630249738693
)
Array
(
    [0] => Array
        (
            [0] => 0
        )

    [1] => Array
        (
            [0] => -0.54630249738693
        )

)