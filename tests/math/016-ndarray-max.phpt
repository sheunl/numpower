--TEST--
NumPower::max
--FILE--
<?php
$a = NumPower::array([[1, 2], [3, 4]]);
print_r(NumPower::max($a));
print_r(NumPower::max($a[0]));
print_r(NumPower::max([[1],[2]]));
?>
--EXPECT--
422