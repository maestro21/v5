<?php class modules extends masterclass {

	use Master;

	
	private $systemModules = array('module', 'system');
	
	function gettables() {
		return
		[
			'modules' => [
				'fields' => [
					'name' 			=> [ 'string', 'text', 'search' => TRUE ],
					'description' 	=> [ 'text', 'textarea', 'null' => TRUE  ],
					'status' 		=> [ 'int',  NULL, 'null' => TRUE  ], // 0 - not installed, 1 - installed, 2 - active
				],
				'idx' => [
					'name' => [ 'name' ],
				]
			],
		];	
	}
	
	function extend() {
		$this->buttons = [
			'admin' => [
				'reinstall'	=> 'reinstall'
			]
		];	
		$this->description = 'Core module for managing other modules';	
	}
	
	
	function reinstall() {		
		$this->install();
		$modules = $this->getModules(); 
		foreach($modules as $module) {
			if($module != $this->className) M($module)->install();
		}		
		$this->admin();
		q()->update($this->cl)->set('status', 2)->run();
	}
	
	
	function getModules() {
		$modules = scandir('www/modules');
		unset($modules[0]);
		unset($modules[1]);
		foreach($modules as $k => $module) {
			$modules[$k] = str_replace('module.','',str_replace('.php','', $module));
		}
		return $modules;
	}
	
	function admin() {
		if(hasRight($this->rights['admin'])) {
			/** getting items from db **/
			$items = q($this)
						->qlist()
						->un('limit')
						->order('status DESC, name ASC')
						->run();
			/** getting real modules from module directory **/
			$modules = array_flip($this->getModules());
			/** running through db and assigning values to modules **/	
			foreach($items as $item){
				if(isset($modules[$item['name']])) {
					$modules[$item['name']] = $item;
				} else {
					q($this->cl)->qdel($item['id'])->run();
				}
			}
			/** running through modules; if module is not in db - adding it**/
			foreach($modules as $k => $module) {
				if(!is_array($module)) {
					$item = array(
						'name' 			=> $k,
						'description' 	=> M($k)->getDescription(),
						'status' 		=> 0,
					);
					if(in_array($k, $this->systemModules)) {
						M($k)->install();
						$item['status'] = 2;
					}
					q($this)->qadd($item)->run();					
					$modules[$k] = $item;
				}
			}
			
			/** once more receiving modules **/
			$modules = q($this)
						->qlist()
						->un('limit')
						->order('status DESC, name ASC')
						->run();
			
			/** writing cache **/
			cache($this->className, $modules);
			
			return $modules;
		}
		return FALSE;
	}

	function cache() {
		return $this->admin();
	}
	
	function items() {
		return cache($this->className);
	}
	
	
	function changeStatus() {
		$status = $this->get['status'];
		$MName = $this->id;
		
		q()	-> update($this->cl)
			-> set('status', $status)
			-> where(qEq('name', $MName))			
		->run();
		
		switch($status) {
			case 0: M($MName)->uninstall(); break;
			case 1: 
			q() -> select()
				-> from('modules')
				-> where(qEq('name', $MName))
				-> run();
			M($MName)->install();
			break;
		}
		
		$this->parse = false;
	}
	
}