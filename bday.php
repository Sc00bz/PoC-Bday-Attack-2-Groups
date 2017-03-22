<?php

header('Content-Type: text/plain');

function H($a, $b)
{
	if ($a < $b)
	{
		return substr(hash('sha256', $a . ',' . $b), 0, 8);
	}
	return substr(hash('sha256', $b . ',' . $a), 0, 8);
}

// You can change these but just make at least one of them higher than $pubLimit
// So you don't just find pubA = pubA' and pubB = pubB'
$pubA = 123456;
$pubB = 789012;
$pubLimit = 2*65536; // 77162 (1.1774*65536) for ~50% chance

$pubA2s = array();
$pubB2s = array();

for ($pub = 0; $pub < $pubLimit; $pub++)
{
	$pubA2s[] = H($pubB, $pub) . ',' . $pub;
	$pubB2s[] = H($pubA, $pub) . ',' . $pub;
}

sort($pubA2s);
sort($pubB2s);

$a = 0;
$b = 0;
while ($a < $pubLimit && $b < $pubLimit)
{
	if (substr($pubA2s[$a], 0, 8) === substr($pubB2s[$b], 0, 8))
	{
		$pubA2 = substr($pubA2s[$a], 9);
		$pubB2 = substr($pubB2s[$b], 9);
		echo "pubA  = $pubA\n";
		echo "pubB' = $pubB2\n";
		echo "H(pubA, pubB') = " . H($pubA, $pubB2) . "\n";
		echo "pubA' = $pubA2\n";
		echo "pubB  = $pubB\n";
		echo "H(pubA', pubB) = " . H($pubA2, $pubB) . "\n";
		die();
	}
	if ($pubA2s[$a] < $pubB2s[$b])
	{
		$a++;
	}
	else
	{
		$b++;
	}
}

echo 'collision not found';
