<?php

require_once __DIR__ . "/lib/PointCounter.php";
require_once __DIR__ . '/data/testData.php';

try{

	$counter =new PointCounter();
	echo $counter->setResults($exampleData2)->setCourses($courses)->getFinalPoints();

} catch (Exception $e) {
	echo ($e->getMessage());
}
