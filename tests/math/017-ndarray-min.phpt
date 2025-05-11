--TEST--
NumPower::min
--FILE--
<?php
$a = NumPower::array([[1, 2], [3, 4]]);
print_r(NumPower::min($a));
print_r(NumPower::min($a[0]));
print_r(NumPower::min([[1],[2]]));
?>
--EXPECT--
111