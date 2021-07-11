<?php

require_once __DIR__ . "/lib/PointCounter.php";
require_once __DIR__ . '/data/testData.php';

try{

	$counter =new PointCounter($exampleData1,$courses);
	echo $counter->getFinalPoints();

} catch (Exception $e) {
	echo ($e->getMessage());
}
