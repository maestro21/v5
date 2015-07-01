<pre><?php 
$DB =  [
	'globals' => [
		'fields' => [
			'title' 	=> [ 'string', 'string', [ 'index' => TRUE ] ],
			'text' 		=> [ 'blob', 'text', [ 'null' => TRUE ] ],
		],
	],
	'modules' => [
		'fields' => [
			'name' 	=> [ 'string', 'string', [ 'index' => TRUE ] ],
			'active'=> [ 'bool', 'checkbox', [ 'null' => TRUE ] ],
		],
	],
];
print_r($DB );