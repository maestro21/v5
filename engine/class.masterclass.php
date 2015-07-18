<?php
require_once("db/db." . DB_TYPE . ".class.php");
abstract class masterclass{
	
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
		$this->cl = $this->className = ($className != '' ? $className : (@$this->path[0] != '' ? $this->path[0] : get_class($this)));

		$this->id 		= (isset($this->post['id']) ? $this->post['id'] : @$this->path[2]);
		$this->page 	= (int)@$this->path[2];
		$this->search 	= @$this->path[3];
		$this->defmethod = 'items'; 
		
		/** Database **/
		//$this->db 		= q($this->className);
		
		/** Class data parameters **/
		$this->perpage 	= 20;
		$this->tables	= $this->gettables();
		$this->options	= NULL;
		$this->parse	= TRUE;
		$this->ajax		= (isset($this->get['ajax']));
		$this->data		= NULL;
		$this->fields 	= $this->tables[$this->cl]['fields'];	
		$this->rights 	= array(
			'admin' => 'admin',
			'add'	=> 'admin',
			'edit'	=> 'admin',
			'save'	=> 'admin',
			'del'	=> 'admin',			
		);
		
		
		/** Class template parameters **/
		$this->title 	= ($this->id!=''? T($this->cl) .' #'.$this->id : T($this->cl));
		$this->buttons = array(
			'admin' => array( 'add' => 'add new' ),
			//'view'  => array( 'items' => 'list', 'item/'.$this->id => 'edit' ),
			'table' => array( 'item/{id}' => 'edit',  'view/{id}' => 'view', ),
		);	
		
		/** Calls virtual method for class extension in child classes **/
		$this->extend();		
  	}
	
	abstract function getTables();
		
		
	/** 
		Returns information about fields of a table
		@param $table - name of a table 
		@return NULL | array();
	**/
	public function getFields($table = NULL) {	
		if(NULL == $table) $table = self::cl;
		if(isset($this->tables[$table])) {
			return $this->tables[$table]['fields'];
		}
		return NULL;	
	}
	
	
	/**
		Admin method for class data listing
		@return array() or FALSE;
	**/
	public function admin() {
		if(hasRight($this->rights['admin'])){
			return $this->items();
		}
		return FALSE;
	}	
	
	/**
		Default method for class data listing
		@return array() or FALSE;
	**/
  	public function items() { 
		$oQuery 		= q($this->cl)->qlist(); 
		$oCountQuery 	= q($this->cl)->qcount();
		
		/* Applying search **/
		if($this->search!=''){
			foreach($this->fields as $k => $v){
				if($v['search']) {
					$oQuery->where(dbEq($k,$v));
					$oCountQuery->where(dbEq($k,$v));
				}
			}		
		}
		$this->pageCount = ceil($oCountQuery->run() / $this->perpage);	
		
		/** Applying sort filter **/
		$filter = explode("_", getVar('sort_' . $this->cl)); 
		if(isset($this->fields[$filter[0]]) && ($filter[1]=='ASC' || $filter[1] == 'DESC')) {
			$oQuery->order($filter[0] . ' ' . $filter[1]);
		}

		return $oQuery->run();
  	}


	/** Opens form for adding new element **/
	public function add($data = NULL) {
		$this->tpl = 'addedit';  
		return array('data' => $data, 'fields' =>$this->fields, 'options'=> $this->options);
	}	
	
	/** Retrieves data of a single element for edit **/
    public function edit($id = NULL) { 
		return $this->add($this->view($id));

    }	
	
	/** Retrieves data of a single element for view **/
    public function view($id = NULL) {
		if(NULL == $id) $id = $this->id;
		return q($this->cl)->qget($id)->run();
    }     

     
    /** Save element **/
    public function save() { 
		$this->parse = FALSE; 
		/* Determines query type; 
		Update if element exists, insert if it`s a new element **/
		if($this->id > 0) {			
			$oQuery = q($this->cl)->qedit($this->post['form'], dbEq('id',$this->id));
		} else {
			$oQuery = q($this->cl)->qadd($this->post['form']);
		}		
		/** Executing query, retrieving id, returning result **/	
		$oQuery->run();
		if($this->id < 1) {
			$this->id = DBinsertId();
			return json_encode(array('redirect' => BASE_URL . $this->cl . '/edit/' . $this->id));			
		}
		return json_encode(array('id' => $this->id));
	 }

     
    /** Delete element **/
    public function del($id = NULL) {
		if(NULL == $id) $id = $this->id;
		q($this->cl)->qdel($id)->run();
		$this->parse = FALSE; 
		return json_encode(array('redirect' => 'reload'));
    }
	
	
	/** Renders class output **/
	public function parse() {
		return tpl( $this->className . '/' . $this->tpl , array_merge($this->params, $this->data));
    }
	
         
    /** Class installation method **/ 
    public function install() { install($this->tables); }

	
	/** Virtual method for class extension **/
	public function extend(){}		
	
	
	/** Caches data **/
	public function cache() {
		cache($this->className, q($this->cl)->qlist()->run());
	}
	
}


/** 
	Trait for compatibility so that Masterclass->call() function would call child method instead of parent;
**/
trait Master {
	/** 
		Calls a method and parses output if needed;
		All methods should be called that way.
		@param $method - name of method
		@param $args - arguments to pass
		@return NULL | string | mixed data
	**/
	public function call($method = '', $args = array(), $parse = TRUE) {
		if(!method_exists($this, $method)) { 
			$method = $this->defmethod;
		}		
		/* Checking for rights */
		if(isset($this->rights[$method]) && !hasRight($this->rights[$method])) {
			if($this->ajax) {
				json_encode(array('error' => 'access_denied'));
			} else {	
				redirect(BASE_PATH);
			}
		}
		$this->tpl = $method;
		/* Calling function */
		$this->data = call_user_func_array(array($this, $method), $args); 		
		/* Parsing output if needed */
		if($this->parse && $parse) {
			$this->data = $this->parse();
		}
		return $this->data;
	}
	

}