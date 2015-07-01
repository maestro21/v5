<?php
require_once('db.query.class.php');
require_once('db.mysql.functions.php');

class mysql extends dbquery {
	
	/** composing MySQL query **/
	function compose() {
		switch($this->queryType) {
			case 'select':
				$this->composeSelectQuery();
			break;
			
			case 'delete':
				$this->composeDeleteQuery();
			break;

			case 'insert':
				$this->composeInsertQuery();
			break;
			
			case 'update':
				$this->composeUpdateQuery();
			break;

			case 'replace':
				$this->composeInsertQuery();
			break;
		}
		$this->rawQuery .= ";";
		return $this;
	}
	
	/** composing MySQL Select query **/
	function composeSelectQuery(){
		$this->rawQuery = 'SELECT ' . implode(",\r\n ", $this->parts['select'])  . "\r\n";
		$this->rawQuery .= 'FROM ' . implode(',', $this->parts['from'])  . "\r\n";
		if(isset($this->parts['where']) && is_array($this->parts['where'])) {
			$this->rawQuery .= 'WHERE 1 '  . "\r\n";
			foreach($this->parts['where'] as $where) {
				$this->rawQuery .= ' ' . $where['op'] . ' ' . $where['query']  . "\r\n";
			}
		}
		if(isset($this->parts['join']) && is_array($this->parts['join'])) {
			foreach($this->parts['join'] as $join) {
				if($join['table'] && $join['cond']) {
					$this->rawQuery .= $join['type'] . 'JOIN ' . $join['table'] . "\r\n" . ' ON ' . $join['cond']  . "\r\n";
				}
			}
		}
		if(isset($this->parts['group']) && is_array($this->parts['group'])) {
			$this->rawQuery .= 'GROUP BY ' . implode(',', $this->parts['group'])  . "\r\n";
		}
		if(isset($this->parts['having']) && is_array($this->parts['having'])) {
			$this->rawQuery .= 'HAVING 1 '  . "\r\n";
			foreach($this->parts['having'] as $having) {
				$this->rawQuery .= ' ' . $having['op'] . ' ' . $having['query']  . "\r\n";
			}
		}
		if(isset($this->parts['order']) && is_array($this->parts['order'])) {
			$this->rawQuery .= 'ORDER BY ' . implode(',', $this->parts['order'])  . "\r\n";
		}
		if(isset($this->parts['limit']) && is_array($this->parts['limit'])) {
			$this->rawQuery .= 'LIMIT ' . implode(',', $this->parts['limit'])  . "\r\n";
		}
		return $this;
	}
	
	/** composing MySQL Delete query **/
	function composeDeleteQuery() {
		$this->rawQuery = 'DELETE FROM ' . $this->parts['from'] . ' '  . "\r\n";
		if(isset($this->parts['where']) && is_array($this->parts['where'])) {
			$this->rawQuery .= 'WHERE 1 '  . "\r\n";
			foreach($this->parts['where'] as $where) {
				$this->rawQuery .= ' ' . $where['op'] . ' ' . $where['query']  . "\r\n";
			}
		}
		if(isset($this->parts['order']) && is_array($this->parts['order'])) {
			$this->rawQuery .= 'ORDER BY ' . implode(',', $this->parts['order'])  . "\r\n";
		}
		if(isset($this->parts['limit']) && is_array($this->parts['limit'])) {
			$this->rawQuery .= 'LIMIT ' . implode(',', $this->parts['limit'])  . "\r\n";
		}
		return $this;
	}
	
	/** composing MySQL Update query **/
	function composeUpdateQuery() {
		if(isset($this->parts['set']) && is_array($this->parts['set'])) {
			$this->rawQuery = 'UPDATE ' . $this->parts['update'] . " SET";
			
			$tmp = array();	
			foreach ($this->parts['set'] as $key => $value) {
				$tmp[] = "\r\n `{$key}` = $value";
			}
			$this->rawQuery .= implode(',', $tmp);
			
			if(isset($this->parts['where']) && is_array($this->parts['where'])) {
				$this->rawQuery .= "\r\n" . 'WHERE 1 ';
				foreach($this->parts['where'] as $where) {
					$this->rawQuery .= "\r\n " . $where['op'] . ' ' . $where['query'];
				}
			}
			if(isset($this->parts['order']) && is_array($this->parts['order'])) {
				$this->rawQuery .= 'ORDER BY ' . implode(',', $this->parts['order'])  . "\r\n";
			}
			if(isset($this->parts['limit']) && is_array($this->parts['limit'])) {
				$this->rawQuery .= 'LIMIT ' . implode(',', $this->parts['limit'])  . "\r\n";
			}
		}
	}
	
	/** composing MySQL Insert query **/
	function composeInsertQuery() {
		if(isset($this->parts['set']) && is_array($this->parts['set'])) {
			$this->rawQuery =  $this->parts['insert'] . ' INTO ' . $this->parts['into'] . ' SET';
			$tmp = array();	
			foreach ($this->parts['set'] as $key => $value) {
				$tmp[] = "\r\n `{$key}` = $value";
			}
			$this->rawQuery .= implode(',', $tmp);
		}
	}
	
	/** running query **/	
	function run($type = NULL, $debug = 0) {
		$this->compose();
		if(NULL == $type) $type = $this->requestType; 
		$result = FALSE;
		switch($type) {
			case self::DBCELL : $result = DBcell($this->rawQuery,  $debug); break; 
			case self::DBROW : $result = DBrow($this->rawQuery,   $debug); break; 
			case self::DBCOL : $result = DBcol($this->rawQuery,   $debug); break;
			case self::DBALL : $result = DBall($this->rawQuery,   $debug); break; 
			case self::DBQUERY : $result = DBquery($this->rawQuery, $debug); break; 			
		}
		return $result;
	}
}

