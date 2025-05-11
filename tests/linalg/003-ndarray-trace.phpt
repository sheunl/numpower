--TEST--
NumPower::trace
--FILE--
<?php
$a = NumPower::array([[1, 2], [3, 4]]);
$b = NumPower::array([[5, 6], [7, 8]]);
$c = NumPower::array([9, 10]);
$d = NumPower::array([[9], [10]]);
print_r(NumPower::trace($a));
print_r(NumPower::trace([[1, 2], [3, 4]]));
print_r(NumPower::trace($d));
?>
--EXPECT--
559