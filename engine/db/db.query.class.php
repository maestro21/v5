<?php 

class DBquery {

	public $parts = array();
	public $queryType;
	public $table;
	public $rawQuery;
	public $requestType = self :: DBQUERY;
	
	const DBCELL = 1;
	const DBROW = 2;
	const DBCOL = 3;
	const DBALL = 4;
	const DBQUERY = 5;
	
	
	function __construct($table = '') {
		$this->setTable($table);
	}
	
	function setTable($table) {
		$this->table = $table;
	}
	
	function getTable() {
		return $table;
	}
	
	/** types **/
	
	function select($query = '*', $shortname = '') {
		$this->clear();
		$this->queryType = 'select';	
		//if($query != '*') $query = str_replace('.','`.`',"`$query`");	
		if ($shortname == '')
			$this->parts['select'][] = $query;
		else 
			$this->parts['select'][$shortname] = $query . ' AS ' . $shortname;		
		return $this;
	}
	
	function delete() {
		$this->clear();
		$this->queryType = 'delete';
		return $this;
	}
	
	function update($table) {
		$this->clear();
		$this->queryType = 'update';
		$this->parts['update'] = "`$table`";
		return $this;
	}
	
	function insert($ignore = FALSE) {
		$this->clear();
		$this->queryType = 'insert';
		$this->parts['insert'] = 'INSERT';
		if($ignore) $this->parts['insert'] .= ' IGNORE';
		return $this;
	}
	
	function replace($table = null) {
		$this->clear();
		$this->queryType = 'insert';
		$this->parts['insert'] = 'REPLACE';
		return $this;
	}
	
	/** vars **/
	
	// select & delete & update
	function from($table, $shortname = '') {
		if($this->queryType == 'select') {
			if ($shortname == '') {
				$this->parts['from'][] = "`$table`";
			} else {
				$this->parts['from'][$shortname] = "`$table` `$shortname`";
			}
		} else {
			$this->parts['from'] = "`$table`";
		}
		return $this;
	}	
		
	function where($query, $op = 'AND') {
		$this->parts['where'][] = array( 'op' => $op, 'query' => $query );
		return $this;
	}
	
	function order($query) {
		$this->parts['order'][] = $query;
		return $this;
	}
	
	function limit($start, $end) {
		$this->parts['limit'] = array( 'start' => $start, 'end' => $end );
		return $this;
	}
	
	// select	
	function join($table, $cond, $shortname = '',$type = '') {
		if($type != '') $type .= ' ';
		if ($shortname == '')
			$this->parts['join'][] = array( 'type' => $type, 'cond' => $cond, 'table' => $table);
		else 
			$this->parts['join'][$shortname] = array( 'type' => $type, 'cond' => $cond, 'table' =>  $table . ' AS ' . $shortname);
		return $this;
	}
	
	function group($query) {
		$this->parts['group'][] = $query;
		return $this;
	}
	
	function having($query, $op = 'AND') {
		$this->parts['having'][] = array( 'op' => $op, 'query' => $query );
		return $this;
	}
	
	// insert & replace & update
	function into($table) {
		$this->parts['into'] = "`$table`";
		return $this;
	}	
	
	function set($key, $value) {
		$this->parts['set'][$key] = "'$value'";
		return $this;
	}	
	
	/** system functions **/
	
	function run() {}
	function compose() {}	
	
	function clear() {
		$this->parts = array();
		$this->rawQuery = '';
		return $this;
	}
	
	function un($partname = '') {
		unset($this->parts[$partname]);
		return $this;
	}
	
	function getRawQuery() {
		$this->compose();
		return $this->rawQuery;
	}
	
	/** default queries **/
	
	function qget($id, $query = '*') {
		$this->requestType = self::DBROW;
		$id = (int) $id;
		if($id > 0) {		
			$this
				->select($query)
				->from($this->table)
				->where("id = $id");
				 
		}
		return $this;
	}
	
	function qcount() {
		$this->requestType = self::DBCELL;
		$this
			->select(dbCount('id'))
			->from($this->table);
			 
		return $this;	 
	}
	
	
	function qlist($query = '*', $page = 0, $perpage = 10) {
		$this->requestType = self::DBALL;
		$this
			->select($query)
			->from($this->table)
			->limit($page * $perpage, ($page + 1) * $perpage);
		
		return $this;	 
	}
	
	function qdel($id) {
		$this->requestType = self::DBQUERY;
		$id = (int) $id;
		if($id > 0) {
			$this
				->delete()
				->from($this->table)
				->where("id = $id");
				 
		}
		return $this;
	}
	
	function qsave($params = array()) {
		$this->requestType = self::DBQUERY;
		if(sizeof($params > 0)) {
			$this
				->replace()
				->into($this->table);
			foreach ($params as $key => $value) {
				$this->set($key, $value);
			}
		}
		return $this;
	}
	
	function qadd($params = array()) {
		$this->requestType = self::DBQUERY;
		if(sizeof($params > 0)) {
			$this
				->insert()
				->into($this->table);
			foreach ($params as $key => $value) {
				$this->set($key, $value);
			}
		}
		return $this;
	}
	
	function qedit($params = array(), $cond) {
		$this->requestType = self::DBQUERY;
		if(sizeof($params > 0)) {
			$this
				->update($this->table)
				->where($cond);				
			foreach ($params as $key => $value) {
				$this->set($key, $value);
			}
		}
		return $this;
	}
	
	function test() {
		
	}
}