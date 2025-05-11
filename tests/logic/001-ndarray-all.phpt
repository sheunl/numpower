--TEST--
NumPower::all
--FILE--
<?php
$a = NumPower::array([[1, 0], [3, 4]]);
$c = NumPower::array([9, 10]);
print_r(NumPower::all($a));
print_r(NumPower::all($a[0]));
print_r(NumPower::all($c));
?>
--EXPECT--
001