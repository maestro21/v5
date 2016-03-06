<?php class users extends masterclass {

	function gettables() {
		return [
			'user' => [
				'fields' => [
					'login' => [ 'string', 'text', ],
					'pass' 	=> [ 'string', 'text', ],
				],
			],
		];	
	}
	
	function extend() {
		$this->description = 'Core module for user operations';	
	}
	
	function login() {
		
	
	}

}