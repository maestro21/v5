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
	
	
	function save() {
		parent:: save();
		$this->cache();	
	}
	
	function extend() {
		$this->description = 'Core module for setting up global settings';	
		$this->buttons = array(
			'admin' => array( 'add' => 'add new', 'langs' => 'languages', 'themes' => 'themes' ),
			'table' => array( 'item/{id}' => 'edit',  'view/{id}' => 'view', ),
		);
	}
	
	function cache() {
		$cache 	= array();		
		$data 	= q($this->cl)->qlist()->run();
		foreach($data as $row){
			$cache[$row['name']] = $row['value'];
		}
		cache($this->className, $cache);
	}
	
	
	function langs() {
		
	
	}		
	
	function set($k, $v) {
		/* checking if element exists */
		$res = q()
			->select()
			->from($this->cl)
			->where(qEq('name',$k))
			->run();
		// if exists -> updating	
		if($res) {
			q($this->cl)->qedit(['value' => $v],qEq('name',$k))->run(null,1);			
			return;
		}
		// else -> replace
		q($this->cl)->qadd(['name' => $k, 'value' => $v])->run(null,1);
		
		$this->cache();	
	}
	
	function delByName($name) {
		q()
			->delete()
			->from($this->cl)
			->where(qEq('name',name))
			->run();		
		
		$this->cache();	
	}
	
}