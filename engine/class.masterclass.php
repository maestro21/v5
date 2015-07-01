<?php
require_once("db/db." . DB_TYPE . ".class.php");
class masterclass{
	
	/** default field for a field type in table **/
	const FIELDTYPE = 1;
	
	/** variable to store dynamically all class fields **/
  	public $params = array();
	
	/** setter for dynamic variables **/
	public function __set($name, $value) {
        $this->params[$name] = $value;
    }	
	
	/** getter for dynamic variables **/
	public function __get($name) 
    {
        if (array_key_exists($name, $this->params)) {
            return $this->params[$name];
        }
		return NULL;
	}
	
	
	/** overrides __construct **/
  	function __construct($className = ''){
  		global $_GET, $_POST, $_SESSION, $_PATH, $_FILES, $_REQUEST , $_DB;
		
		/** Global arrays **/
  		$this->params['get'] 		= & $_GET;
		$this->params['request'] 	= & $_REQUEST;
  		$this->params['post'] 		= & $_POST;
		$this->params['session'] 	= & $_SESSION;
		$this->params['path'] 		= & $_PATH;
		$this->params['files'] 		= & $_FILES;
		
		/** Class routing parameters **/
		$this->className= ($className != '' ? $className : (@$this->path[0] != '' ? $this->path[0] : get_class($this)));
		$this->id 		= (isset($this->post['id']) ? $this->post['id'] : @$this->path[2]);
		$this->page 	= (int)@$this->path[2];
		$this->search 	= @$this->path[3];
		$this->defmethod = 'items'; 
		
		/** Database **/
		$this->db 		= new MySQL($this->className);
		
		/** Class data parameters **/
		$this->tables 	= NULL;
		$this->options	= NULL;
		$this->parse	= TRUE;
		$this->ajax		= FALSE;
		$this->data		= NULL;
		$this->fields 	= $this->tables[$this->className];	
		$this->rights 	= array(
			'admin' => 'admin',
			'add'	=> 'admin',
			'edit'	=> 'admin',
			'save'	=> 'admin',
			'del'	=> 'admin',			
		);
		
		
		/** Class template parameters **/
		$this->tpl 		= (file_exists( "tpl/".$this->className.".html") ? $this->className : $this->tpl);		
		$this->title 	= ($this->id!=''?T(S($this->className)) .' #'.$this->id:T($this->className));
		$this->buttons = array(
			'admin' => array( 'items' => 'list', 'item' => 'add' ),
			'view'  => array( 'items' => 'list', 'item/'.$this->id => 'edit' ),
			'table' => array( 'item/{id}' => 'edit',  'view/{id}' => 'view', ),
		);	
		
		/** Calls virtual method for class extension in child classes **/
		$this->extend();		
  	}
	
	
	/** 
		Calls a method and parses output if needed
		@param $method - name of method
		@param $args - arguments to pass
		@return NULL | string | mixed data
	**/
	function call($method = '', $args = array()) {
		if(!method_exists($this, $method)) { 
			$method = $this->defmethod;
		}	
		$this->data = call_user_func_array(array($this, $method), $args); 		
		
		if($this->parse) {
			$this->tpl = $method;
			$this->data = $this->parse();
		}
		return $this->data;
	}
	
	
	/** 
		Returns information about fields of a table
		@param $table - name of a table 
		@return NULL | array();
	**/
	function getFields($table = NULL) {	
		if(NULL == $table) $table = self::className;
		if(isset($this->tables[$table])) {
			return $this->tables[$table]['fields'];
		}
		return NULL;	
	}
	
	
	/**
		Admin method for class data listing
		@return array() or FALSE;
	**/
	function admin() {
		if(hasRight($this->rights['admin'])){
			return items();
		}
		return FALSE;
	}	
	
	/**
		Default method for class data listing
		@return array() or FALSE;
	**/
  	function items() {
		$oQuery = $this->db->glist();
		$oCountQuery = $this->db->gcount();
		
		/* Applying search **/
		if($this->search!=''){
			foreach($this->fields as $k => $v){
				if($v['search']) {
					$oQuery->where(dbEq($k,$v));
					$oCountQuery->where(dbEq($k,$v));
				}
			}		
		}
		$this->pageCount = ceil(DBfield($oCountQuery->run()) / $this->perpage);	
		
		/** Applying sort filter **/
		$filter = explode("_", getVar('sort_' . $this->className)); 
		if(isset($this->fields[$filter[0]]) && ($filter[1]=='ASC' || $filter[1] == 'DESC')) {
			$oQuery->order($filter[0] . ' ' . $filter[1]);
		}
		
		return $oQuery->run();
  	}


	/** Opens form for adding new element **/
	function add() {
		if(!hasRight($this->rights['add'])) redirect(BASE_PATH . $this->className);
	}	
	
	/** Retrieves data of a single element for edit **/
    function edit($id = NULL) {	
		if(hasRight($this->rights['edit'])){
			return $this->view($id);
		}
		redirect(BASE_PATH . $this->className);
    }	
	
	/** Retrieves data of a single element for view **/
    function view($id = NULL) {
		if(NULL == $id) $id = $this->id;
		return $this->db->qget($id)->run();
    }     

     
    /** Save element **/
    function save() {
		if(hasRight($this->rights['save'])){
			/* Determines query type; 
			Update if element exists, insert if it`s a new element **/
			if($this->id > 0) {
				$oQuery = $this->db->qsave();
			} else {
				$oQuery = $this->db->qedit();
			}			
			/** Setting values for table fields **/
			foreach ($this->post['form'] as $k=>$v) {
				if(isset($this->fields[$k])) {
					if($this->fields[$k]['type']=='pass' && trim($v)=='') continue;
					$oQuery->set($k, sqlFormat($this->fields[$k][FIELDTYPE], $v));
				}
			}			
			/** Executing query, retrieving id, returning result **/	
			$oQuery->run();
			if($this->id < 1) $this->id = DBinsertId();
			$this->parse = FALSE; 
			$this->ajax = TRUE;
			return json_encode(array('id' => $this->id));
		}
		return json_encode(array('error' => 'access_denied'));
    }

     
    /** Delete element **/
    function del($id = NULL) {
		if(hasRight($this->rights['admin'])){
			if(NULL == $id) $id = $this->id;
			$this->db->qdel($id)->run();
			$this->parse = FALSE; 
			$this->ajax = TRUE;
			return json_encode(array('id' => $this->id));
		}
		return json_encode(array('error' => 'access_denied'));
    }
	
	
	/** Renders class output **/
	function parse() { 
		return tpl( $this->className . '/' . $this->tpl , $this->params);
    }
	
         
    /** Class installation method **/ 
    function install() { install($this->tables); }

	
	/** Virtual method for class extension **/
	function extend(){}		
	
	
	/** Caches data **/
	function cache() {
		cache($this->className, $this->db->qlist()->run());
	}
	
}
