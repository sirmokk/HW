<?php
require '..\lib\PointCounter.php';
require '..\data\testData.php';


use PHPUnit\Framework\TestCase;



class PointCounterTest extends TestCase
{

	public function testGetFinalPoints1()
	{
		global $exampleData;
		global $courses;

		$counter = new PointCounter($exampleData,$courses);
		$this->assertEquals('470 (370 alappont + 100 többletpont)',$counter->getFinalPoints());
	}

	public function testGetFinalPoints2()
	{
		global $exampleData1;
		global $courses;

		$counter = new PointCounter($exampleData1,$courses);
		$this->assertEquals('476 (376 alappont + 100 többletpont)',$counter->getFinalPoints());
	}

	public function testGetFinalPoints3()
	{
		global $exampleData2;
		global $courses;

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('hiba, nem lehetséges a pontszámítás a kötelező érettségi tárgyak hiánya miatt');

		$counter = new PointCounter($exampleData2,$courses);
		$counter->getFinalPoints();
	}

	public function testGetFinalPoints4()
	{
		global $exampleData3;
		global $courses;

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('hiba, nem lehetséges a pontszámítás a magyar nyelv és irodalom tárgyból elért 20% alatti eredmény miatt');

		$counter = new PointCounter($exampleData3,$courses);
		$counter->getFinalPoints();
	}
}
