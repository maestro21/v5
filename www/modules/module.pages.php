<?php class pages extends masterclass {

	use Master;

	function gettables() {
		return 
		[
			'pages' => [
				'fields' => [					
					'pid'			=>	[ 'int', 'select', 'default' => 0, ],					
					//'lang'			=>  [ NULL, 'select' ],
					'name' 			=> 	[ 'string', 'text', 'search' => TRUE ],
					'url'			=>	[ 'string',	'text' ],
					'fullurl'		=>  [ 'string', 'info' ],
					'type'			=>	[ 'int', 'select' ],					
					'content' 		=> 	[ 'blob', 'html', 'search' => TRUE ],
					'status'		=>	[ 'int', 'select' ],						
				],
			],
		];		
	}
	
	function extend() {
		$this->description = 'Core module for creating website pages';
		$this->defmethod = 'view';
		
		$this->options = [
			'pid' => $this->getPidOptions(),
			'status' => [
				0 => 'hidden',
				1 => 'visible',
				2 => 'in_menu',
			],
			'type' => [
				1 => 'page',
				2 => 'redirect'
			],
		];
		
	}

	function save() {
		$form = $this->post['form'];
		$form['fullurl'] = $this->getFullUrl($form['pid'], $form['url']);		
		$ret = parent :: saveDB($form);
		$this->cacheTree();
		return $ret;
	}
	
	function getPageTree($options = null) {		
		$q = q()	->select('id, pid, name, url, fullurl')
					->from($this->className)
					->order('pid ASC, id ASC');
		if(@$options['id'] > 0) {
			$q->where('id != ' . $options['id']);	
		}
		if(@$options['status']) {
			$q->where('status = ' . $options['status']);	
		}	
		$tree = $q->run(); 
		$T = new tree($tree); 
		return $T;
	}
		
	function cacheTree() {
		$T = $this->getPageTree([ 'status' => 2]);
		cache($this->className, $T->treeList);
		$T = $this->getPageTree();
		cache($this->className . 'options', $T->options);
	}	
	
	function getPidOptions() {
		if($this->method == 'edit') {
			$T = $this->getPageTree([ 'id' => (int)$this->id ]);
			return $T->options;		
		}
		return cache($this->className . 'options');
	}
	
	function getFullUrl($id, $url) {
		if($id > 0) {
			$T = $this->getPageTree();
			$ret = array_reverse($T->getFullUrl($id));
			$ret[] = $url;
			$ret = implode('/', $ret);
		} else {
			$ret = $url;
		}
		return $ret;
	}
	
	function admin() {
		$T = $this->getPageTree();
		$ret = $T->drawTree($this->className . '/adm');
		return $ret;
	}
	
				
	function menu($tpl = 'menu'){
		$this->parse = false;
		$tree = cache($this->className);	
		foreach($tree as $lang) {
			if($lang['url'] == getLang()) {
				$T = new tree();
				$ret = $T->drawTree($this->className . '/' . $tpl, $lang['children']);
				return $ret;
			}
		}
		return FALSE;
	}	
	
	function view() {
		$path = $this->path;
		$url  = implode('/', $this->path);
		$page = q()	->select()
					->from($this->className)
					->where("fullurl='$url'")
					->run(DBROW);
		return $page;
	
	}
	
	
}