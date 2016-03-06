<?php class languages extends masterclass {

	use Master;

	function getTables() {
		return [
			'languages' => [
				'fields' => [
					'abbr' 		=> [ 'string', 'text', ],
					'name' 		=> [ 'string', 'text', ],
					'status'	=> [ 'int', 'select'],
				],
			],
		];	
	}
	
	function extend() {
		$this->description = 'Core module for internationalization';
		
		$this->options = [
			'status'	=> [ 'inactive', 'active', 'default' ]
		];
	}

}