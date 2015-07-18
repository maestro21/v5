<?php class users extends masterclass {

	function extend() {
		$this->tables = [
			'user' => [
				'fields' => [
					'login' => [ 'string', 'string', ],
					'pass' 	=> [ 'string', 'text', ],
				],
			],
		];	
	}
	
	function login() {
		
	
	}

}