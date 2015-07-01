<?php class system extends masterclass {

	function extend() {
		$this->tables = [
			'system' => [
				'fields' => [
					'name' 		=> [ 'string', 'string', ],
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
				'value'		=> serialize($v),
				'deletable'	=> 0,
			);
			$this->db->qadd($item)->run();
		}
	}

}