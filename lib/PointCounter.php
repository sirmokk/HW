<?php

/**
 * Class PointCounter
 */
class PointCounter
{
	const RESULT_KEYS = ['valasztott-szak','erettsegi-eredmenyek','tobbletpontok'];
	const COURSE_KEYS = ['egyetem','kar','szak'];
	const MANDATORY_EXAM_SUBJECTS = ['magyar nyelv és irodalom', 'történelem', 'matematika'];
	const B2 = 28;
	const C1 = 40;
	const HIGH = 50;



	private $courses = [];
	private $results = [];
	private $selected_course = [];
	private $base_point =0;
	private $plus_point=0;


	/**
	 * PointCounter constructor.
	 */
	public function __construct($results = [], $courses = [])
	{
		if(!empty($results)){
			$this->setResults($results);
		}
		if(!empty($courses)){
			$this->setCourses($courses);
		}

	}

	/**
	 * @param array $courses
	 */
	public function setCourses($courses)
	{
		if(!is_array($courses) || empty($courses)){
			throw new RuntimeException("Nem sikerült a szakokat beállítani!");
		}
		$this->courses = $courses;

		return $this;
	}

	/**
	 * @param array $results
	 */
	public function setResults($results)
	{
		if(!is_array($results) || empty($results)){
			throw new RuntimeException("Nem sikerült az eredményeket beállítani!");
		}

		$this->results = $results;

		return $this;
	}

	/**
	 * @return $this
	 */
	private function validateResults()
	{
		foreach (self::RESULT_KEYS as $key){
			if(!array_key_exists($key,$this->results)){
				throw new RuntimeException("Az eredmények hiányosak. Nem létező kulcs: ".$key);
			}
		}

		foreach (self::COURSE_KEYS as $key){
			if(!array_key_exists($key,$this->results['valasztott-szak'])){
				throw new RuntimeException("Az eredmény nem tartalmazza a szak kiválasztásához szükséges adatokat: ".$key);
			}

		}

		if(!isset($this->courses[$this->results['valasztott-szak']['egyetem']][$this->results['valasztott-szak']['kar']][$this->results['valasztott-szak']['szak']])){
			throw new RuntimeException("A válaszott szakhoz hiányznak a felvételi feltételek.");
		}
		$this->selected_course = $this->courses[$this->results['valasztott-szak']['egyetem']][$this->results['valasztott-szak']['kar']][$this->results['valasztott-szak']['szak']];

		return $this;

	}

	/**
	 * @return $this
	 */
	private function checkMandatoryResults(){
		foreach (self::MANDATORY_EXAM_SUBJECTS as $subject) {
			if(array_search($subject,array_column($this->results['erettsegi-eredmenyek'],'nev')) === false){
				throw new RuntimeException("hiba, nem lehetséges a pontszámítás a kötelező érettségi tárgyak hiánya miatt");
			}
		}

		foreach ($this->selected_course['kotelezo'] as $subject) {
			if(array_search($subject['nev'],array_column($this->results['erettsegi-eredmenyek'],'nev')) === false){
				throw new RuntimeException("hiba, nem lehetséges a pontszámítás a kötelező érettségi tárgyak hiánya miatt");
			}

			if( $subject['tipus'] == 'emelt' &&
				$this->results[array_search($subject['nev'],array_column($this->results['erettsegi-eredmenyek']),'nev')]['tipus'] != 'emelt'){
				throw new RuntimeException("hiba, nem lehetséges a pontszámítás a kötelező érettségi tárgy alacsony szintje miatt");
			}

		}
		$found_mandatory_selectable = false;
		foreach ($this->selected_course['kotelezoen-valaszthato'] as $subject) {
			if(array_search($subject['nev'],array_column($this->results['erettsegi-eredmenyek'],'nev')) !== false){
				if( $subject['tipus'] == 'emelt' &&
					$this->results[array_search($subject['nev'],array_column($this->results['erettsegi-eredmenyek']),'nev')]['tipus'] != 'emelt'){
					continue;
				}
				$found_mandatory_selectable = true;
				break;
			}

		}

		if (!$found_mandatory_selectable){
			throw new RuntimeException("hiba, nem lehetséges a pontszámítás a kötelezően választható érettségi tárgy hiánya miatt");
		}

		foreach ($this->results['erettsegi-eredmenyek'] as $result){
			$this->getPointFromResult($result['nev']);
		}



		return $this;

	}

	/**
	 * @return $this
	 */
	private function countBasePoint(){
		$this->base_point = 0;
		foreach ($this->selected_course['kotelezo'] as $subject){
			$this->base_point+=$this->getPointFromResult($subject['nev']);
		}
		$max_selected_mandatory_point=0;
		foreach ($this->selected_course['kotelezoen-valaszthato'] as $subject){
			if($this->getPointFromResult($subject['nev'])>$max_selected_mandatory_point){
				$max_selected_mandatory_point=$this->getPointFromResult($subject['nev']);
			}
		}
		$this->base_point+=$max_selected_mandatory_point;
		$this->base_point*=2;
		if($this->base_point>400){
			throw new RuntimeException("hiba, az alappontok túlépték a maximumot, több kötelező tárgy lett megadva?");
		}
		return $this;
	}

	/**
	 * @return $this
	 */
	private function countPlusPoint()
	{
		$normalized_exams = [];
		$this->plus_point = 0;
		foreach ($this->results['tobbletpontok'] as $exam) {
			if($exam['kategoria']=='Nyelvvizsga'){
				if(isset($normalized_exams[$exam['nyelv']]) && $normalized_exams[$exam['nyelv']]['point'] > $this->getExamPoint($exam['tipus']) ){
					continue;
				}
				$normalized_exams[$exam['nyelv']]['point'] = $this->getExamPoint($exam['tipus']);
			}
		}

		foreach ($normalized_exams as $point){
			$this->plus_point+=$point['point'];
		}

		foreach ($this->results['erettsegi-eredmenyek'] as $subject){
			if($subject['tipus'] == 'emelt'){
				$this->plus_point+= self::HIGH;
			}
		}

		if($this->plus_point>100){
			$this->plus_point=100;
		}

		return $this;

	}


	/**
	 * @return string
	 */
	public function getFinalPoints(){

		if($this->plus_point == 0 || $this->base_point == 0 ){
			$this->calculatePoints();
		}

		return $this->base_point + $this->plus_point . " (". $this->base_point. " alappont + ".$this->plus_point." többletpont)";

	}

	/**
	 * @return int
	 */
	public function getBasePoints(){

		if($this->base_point == 0 ){
			$this->calculatePoints();
		}
		return $this->base_point;

	}

	/**
	 * @return int
	 */
	public function getPlusPoints(){

		if($this->plus_point == 0 ){
			$this->calculatePoints();
		}
		return $this->plus_point;

	}


	/**
	 * @return $this
	 */
	private function calculatePoints(){
		if(empty($this->courses) || empty($this->results)){
			throw new RuntimeException("hiba, hiányos adatok");
		}
		$this->validateResults();
		$this->checkMandatoryResults();
		$this->countBasePoint();
		$this->countPlusPoint();

		return $this;

	}

	/**
	 * @param $subject_name
	 * @return int
	 */
	private function getPointFromResult($subject_name){
		if(array_search($subject_name,array_column($this->results['erettsegi-eredmenyek'],'nev')) === false) {
		 return 0;
		}

		$result_str=$this->results['erettsegi-eredmenyek'][array_search($subject_name,array_column($this->results['erettsegi-eredmenyek'],'nev'))]['eredmeny'];
		$result_int=  (int)str_replace('%', '', $result_str);
		if($result_int<20){
			throw new RuntimeException("hiba, nem lehetséges a pontszámítás a ".$subject_name." tárgyból elért 20% alatti eredmény miatt");
		}
		if($result_int>100 ){
			throw new RuntimeException("hiba, a tárgyra kapott értékelés kívül esik a tartományon(20-100), tárgy neve: ".$subject_name);
		}
		return $result_int;

	}

	/**
	 * @param $exam_type
	 * @return int
	 */
	private function getExamPoint($exam_type){

		switch ($exam_type) {
			case 'B2':
				return self::B2;
			case 'C1':
				return self::C1;
			default:
				return 0;
		}
	}





}