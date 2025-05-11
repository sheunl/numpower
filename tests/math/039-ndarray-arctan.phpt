--TEST--
NumPower::arctan
--FILE--
<?php
$a = NumPower::array([[0, -0.5], [0, -0.5]]);
print_r(NumPower::arctan($a)->toArray());
print_r(NumPower::arctan($a[0])->toArray());
print_r(NumPower::arctan([[0],[-0.5]])->toArray());
?>
--EXPECT--
Array
(
    [0] => Array
        (
            [0] => 0
            [1] => -0.46364760398865
        )

    [1] => Array
        (
            [0] => 0
            [1] => -0.46364760398865
        )

)
Array
(
    [0] => 0
    [1] => -0.46364760398865
)
Array
(
    [0] => Array
        (
            [0] => 0
        )

    [1] => Array
        (
            [0] => -0.46364760398865
        )

)