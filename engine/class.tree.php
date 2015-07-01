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
	/* tree array */	
	var $treeList = Array();
	/* tree children array */
	var $treeTMPList = Array(); 
	/* select box options array */ 
	var $options = Array('---');
	
	function __construct($data = '') {
		if(is_array($data)) {
			$data = $this->fetch($data);
			$this->fetchDraw($data);
		}
	}
	
	/* returns path from current leaf to root */
	function getPathToRoot($id, $ret = array()) { 
		$ret[] = $id;
		if($this->treeTMPList[$id]['id'] > 0)
			$ret = $this->getPathToRoot($this->treeTMPList[$id]['pid'], $ret);
		return $ret;	
	}
	
	/* Fetches tree */
	function fetch($data){
		foreach ($data as $k=>$row){	
			foreach ($row as $k=>$v) $this->treeTMPList[$row['id']][$k] = $v; /* writing data to current element */
			$this->treeTMPList[$row['pid']]['_children'][] = $row['id']; /* grouping all children;	*/					
		}
		
		$this->treeList	= $this->branch(0); /* building array */
		return $this->treeList;
	}

	/* Returns single branch based on parent id */
	function branch($id) 
	{
		$tmpArr = Array();

		if(sizeof(@$this->treeTMPList[$id]['_children'])>0)
			foreach ($this->treeTMPList[$id]['_children'] as $child)
			{					
				$tmpArr[$child] = $this->treeTMPList[$child];
				unset($tmpArr[$child]['_children']);
				$tmpArr[$child]['children'] = $this->branch($child);			
			}	
		
		return $tmpArr;		
	}
	
	/* Adds element to options list considering sublevel prefix */
	function fetchDraw($data,$lvl=-1){
		 $lvl++;
		foreach ($data as $row){
			for($i=0;$i<$lvl;$i++) $row['name'] ="--".$row['name'];
			$this->options[$row['id']] = $row['name'];
			if($row['children']!='') $this->fetchDraw($row['children'],$lvl);
		}
	}
}
