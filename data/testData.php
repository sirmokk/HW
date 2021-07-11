<?php

// output: 470 (370 alappont + 100 többletpont)
$exampleData = [
	'valasztott-szak' => [
		'egyetem' => 'ELTE',
		'kar' => 'IK',
		'szak' => 'Programtervező informatikus',
	],
	'erettsegi-eredmenyek' => [
		[
			'nev' => 'magyar nyelv és irodalom',
			'tipus' => 'közép',
			'eredmeny' => '70%',
		],
		[
			'nev' => 'történelem',
			'tipus' => 'közép',
			'eredmeny' => '80%',
		],
		[
			'nev' => 'matematika',
			'tipus' => 'emelt',
			'eredmeny' => '90%',
		],
		[
			'nev' => 'angol nyelv',
			'tipus' => 'közép',
			'eredmeny' => '94%',
		],
		[
			'nev' => 'informatika',
			'tipus' => 'közép',
			'eredmeny' => '95%',
		],
	],
	'tobbletpontok' => [
		[
			'kategoria' => 'Nyelvvizsga',
			'tipus' => 'B2',
			'nyelv' => 'angol',
		],
		[
			'kategoria' => 'Nyelvvizsga',
			'tipus' => 'C1',
			'nyelv' => 'német',
		],
	],
];

// output: 476 (376 alappont + 100 többletpont)
$exampleData1 = [
	'valasztott-szak' => [
		'egyetem' => 'ELTE',
		'kar' => 'IK',
		'szak' => 'Programtervező informatikus',
	],
	'erettsegi-eredmenyek' => [
		[
			'nev' => 'magyar nyelv és irodalom',
			'tipus' => 'közép',
			'eredmeny' => '70%',
		],
		[
			'nev' => 'történelem',
			'tipus' => 'közép',
			'eredmeny' => '80%',
		],
		[
			'nev' => 'matematika',
			'tipus' => 'emelt',
			'eredmeny' => '90%',
		],
		[
			'nev' => 'angol nyelv',
			'tipus' => 'közép',
			'eredmeny' => '94%',
		],
		[
			'nev' => 'informatika',
			'tipus' => 'közép',
			'eredmeny' => '95%',
		],
		[
			'nev' => 'fizika',
			'tipus' => 'közép',
			'eredmeny' => '98%',
		],
	],
	'tobbletpontok' => [
		[
			'kategoria' => 'Nyelvvizsga',
			'tipus' => 'B2',
			'nyelv' => 'angol',
		],
		[
			'kategoria' => 'Nyelvvizsga',
			'tipus' => 'C1',
			'nyelv' => 'német',
		],
	],
];

// output: hiba, nem lehetséges a pontszámítás a kötelező érettségi tárgyak hiánya miatt
$exampleData2 = [
	'valasztott-szak' => [
		'egyetem' => 'ELTE',
		'kar' => 'IK',
		'szak' => 'Programtervező informatikus',
	],
	'erettsegi-eredmenyek' => [
		[
			'nev' => 'matematika',
			'tipus' => 'emelt',
			'eredmeny' => '90%',
		],
		[
			'nev' => 'angol nyelv',
			'tipus' => 'közép',
			'eredmeny' => '94%',
		],
		[
			'nev' => 'informatika',
			'tipus' => 'közép',
			'eredmeny' => '95%',
		],
	],
	'tobbletpontok' => [
		[
			'kategoria' => 'Nyelvvizsga',
			'tipus' => 'B2',
			'nyelv' => 'angol',
		],
		[
			'kategoria' => 'Nyelvvizsga',
			'tipus' => 'C1',
			'nyelv' => 'német',
		],
	],
];

// output: hiba, nem lehetséges a pontszámítás a magyar nyelv és irodalom tárgyból elért 20% alatti eredmény miatt
$exampleData3 = [
	'valasztott-szak' => [
		'egyetem' => 'ELTE',
		'kar' => 'IK',
		'szak' => 'Programtervező informatikus',
	],
	'erettsegi-eredmenyek' => [
		[
			'nev' => 'magyar nyelv és irodalom',
			'tipus' => 'közép',
			'eredmeny' => '15%',
		],
		[
			'nev' => 'történelem',
			'tipus' => 'közép',
			'eredmeny' => '80%',
		],
		[
			'nev' => 'matematika',
			'tipus' => 'emelt',
			'eredmeny' => '90%',
		],
		[
			'nev' => 'angol nyelv',
			'tipus' => 'közép',
			'eredmeny' => '94%',
		],
		[
			'nev' => 'informatika',
			'tipus' => 'közép',
			'eredmeny' => '95%',
		],
	],
	'tobbletpontok' => [
		[
			'kategoria' => 'Nyelvvizsga',
			'tipus' => 'B2',
			'nyelv' => 'angol',
		],
		[
			'kategoria' => 'Nyelvvizsga',
			'tipus' => 'C1',
			'nyelv' => 'német',
		],
	],
];

$courses=[
	'ELTE' =>[
		'IK' => [
			'Programtervező informatikus' =>[
				'kotelezo' =>[
					[
						'nev' => 'matematika',
						'tipus' => 'közép'
					]
				],
				'kotelezoen-valaszthato' =>[
					[
						'nev' => 'biologia',
						'tipus' => 'közép'
					],
					[
						'nev' => 'fizika',
						'tipus' => 'közép'
					],
					[
						'nev' => 'informatika',
						'tipus' => 'közép'
					],
					[
						'nev' => 'kémia',
						'tipus' => 'közép'
					]
				]

			]
		]
	],
	'PPKE' =>[
		'BTK' => [
			'Anglisztika' =>[
				'kotelezo' =>[
					[
						'nev' => 'angol',
						'tipus' => 'emelt'
					]
				],
				'kotelezoen-valaszthato' =>[
					[
						'nev' => 'francia',
						'tipus' => 'közép'
					],
					[
						'nev' => 'német',
						'tipus' => 'közép'
					],
					[
						'nev' => 'olasz',
						'tipus' => 'közép'
					],
					[
						'nev' => 'orosz',
						'tipus' => 'közép'
					],
					[
						'nev' => 'spanyol',
						'tipus' => 'közép'
					],
					[
						'nev' => 'történelem',
						'tipus' => 'közép'
					]
				]

			]
		]
	]
];