<?php class system extends masterclass {
	
	use Master;

	function gettables() {
		return [
			'system' => [
				'fields' => [
					'name' 		=> [ 'string', 'text', ],
					'value' 	=> [ 'string', 'text', ],
					'deletable'	=> [ 'bool', 'checkbox', ]
				],
				'idx' => [
					'name' => [ 'name' ],
				]
			],
		];	
	}
	
	function install() {
		parent :: install();		
		include('data/default.globals.php'); 
		foreach($globals as $k => $v) {
			$item = array(
				'name'		=> $k,
				'value'		=> $v,
				'deletable'	=> 0,
			);
			q($this->cl)->qadd($item)->run();
		}
	}
	
	
	function cache() {
		$cache 	= array();		
		$data 	= q($this->cl)->qlist()->run();
		foreach($data as $row){
			$cache[$row['name']] = $row['value'];
		}
		cache($this->className, $cache);
	}
}