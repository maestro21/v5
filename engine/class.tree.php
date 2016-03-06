<?php 
/* Creates tree of elements;
	usage: 
	1. get elements ordered by parent_id;
	$data = Dbquery('select * from {classname} order by parent_id');
	2. create tree object and pass data there 	
	$tree = new tree($data);
	3. Voila! You have your tree! 
	* TODO drawing method
	
 */
class tree{
	var $treeTMPList = Array(); //МАССИВ ДЕТЕЙ
	var $treeList = Array(); //МАССИВ ДЕРЕВА
	var $options = Array('---');
	var $xcludeId = null;
	var $html = '';
	
	function __construct($data = '') {
		if(is_array($data)) {
			$data = $this->fetch($data);
			$this->fetchDraw($data);
		}
		return $this;
	}
	
	// gets all parent pages
	function getParentPages($id, $ret = array()) { 
		$ret[] = $this->treeTMPList[$id];
		if($this->treeTMPList[$id]['id'] > 0)
			$ret = $this->getPathToRoot($this->treeTMPList[$id]['pid'], $ret);
		return $ret;	
	}
	
	// gets all parent page ids
	function getPathToRoot($id, $ret = array()) { 
		$ret[] = $this->treeTMPList[$id];
		if(@$this->treeTMPList[$id]['pid'] > 0)
			$ret = $this->getPathToRoot($this->treeTMPList[$id]['pid'], $ret);
		else
			$ret = array_reverse($ret);
		return $ret;	
	}
	
	// gets full url of page
	function getFullUrl($id, $ret = array()) {	
		$ret[] = $this->treeTMPList[$id]['url'];
		if($this->treeTMPList[$id]['pid'] > 0) {
			$ret = $this->getFullUrl($this->treeTMPList[$id]['pid'], $ret);
		} 
		return $ret;
	}
	
	function clear() {
		$this->treeTMPList = Array();
		$this->treeList = Array();
		$this->options = Array('---');
		$this->xcludeId = null;
		$this->html = '';
		return $this;
	}
	
	
	function fetch($data){
		//inspect($data);
		foreach ($data as $k=>$row){	
			$row['id'] = (int) $row['id'];
			foreach ($row as $k=>$v) $this->treeTMPList[$row['id']][$k] = $v; //writing data to current element
			$this->treeTMPList[$row['pid']]['_children'][] = $row['id']; // grouping all children;						
		}
		//inspect($this->treeTMPList);
		
		$this->treeList	= $this->branch(); //building array
		//inspect($this->treeList);
		return $this->treeList;
	}

	function branch($id = 0) //returns single branch based on parent id
	{
		$tmpArr = Array();
	
		//echo $id .'=>';print_r($this->treeTMPList[$id]['_children']);
		if(sizeof(@$this->treeTMPList[$id]['_children'])>0)
			foreach ($this->treeTMPList[$id]['_children'] as $child)
			{					
				$tmpArr[$child] = $this->treeTMPList[$child];
				unset($tmpArr[$child]['_children']);
				$tmpArr[$child]['children'] = $this->branch($child);			
			}	
		
		return $tmpArr;		
	}
	
	function getLeaf($id){
		return $this->treeTMPList[$id];
	}
	
	function fetchDraw($data, $lvl = -1){
		 $lvl++;
		foreach ($data as $row){
			for($i=0;$i<$lvl;$i++) $row['name'] ="--".$row['name'];
			$this->options[$row['id']] = $row['name'];
			if($row['children']!='') $this->fetchDraw($row['children'],$lvl);
		}
	}
	
	function drawTree($tpl = '', $data = NULL){
		$_html = '';
		if($data == NULL) $data = $this->treeList;
		foreach ($data as $row){
			$_row['data'] = $row;
			if(isset($row['children']) && is_array($row['children']) && !empty($row['children'])) {
				$_row['data']['children'] = $this->drawTree($tpl, $row['children']);
			}
			$_html .= tpl($tpl,$_row); 
		}
		return $_html;
	}

}
