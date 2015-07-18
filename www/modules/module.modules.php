<?php class modules extends masterclass {

	use Master;

	function gettables() {
		return
		[
			'modules' => [
				'fields' => [
					'name' 		=> [ 'string', 'text', 'search' => TRUE ],
					'active' 	=> [ 'bool', 'checkbox', 'null' => TRUE  ],
					'installed' => [ 'bool', 'checkbox', 'null' => TRUE  ],
				],
				'idx' => [
					'name' => [ 'name' ],
				]
			],
		];	
	}
	
	function admin() {
		if(hasRight($this->rights['admin'])) {
			/** getting items from db **/
			$items = q($this->cl)->qlist()->un('limit')->run();
			/** getting real modules from module directory **/
			$modules = scandir('www/modules');
			unset($modules[0]);
			unset($modules[1]);
			foreach($modules as $k => $module) {
				$modules[$k] = str_replace('module.','',str_replace('.php','', $module));
			}
			$modules = array_flip($modules);
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
						'name' 		=> $k,
						'active' 	=> 0,
						'installed' =>0,
					);
					q($this->cl)->qadd($item)->run();					
					$modules[$k] = $item;
				}
			}		
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
}