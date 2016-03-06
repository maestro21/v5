<?php 
/** DATABASE FUNCTIONS **/
function q($table = '') {
	return new MySQL($table);
}

function DBconnect()
{
	//inspect(HOST_SERVER . ' ' . HOST_NAME . ' ' . HOST_PASS);
	$link = mysql_connect(HOST_SERVER, HOST_NAME , HOST_PASS) or die('cannot connect to server');
	define('HOST_LINK',$link);
	mysql_select_db(HOST_DB,$link) or die('cannot connect to database');
	mysql_query("SET CHARACTER SET 'UTF8'");
}

function DBsrvconnect()
{
	$link = mysql_connect(HOST_SERVER, HOST_NAME , HOST_PASS) or die('cannot connect to server');
	define('HOST_LINK',$link);
	mysql_query("SET CHARACTER SET 'UTF8'");
}

function DBselDB(){
	mysql_select_db(HOST_DB,HOST_LINK) or die('cannot connect to database');
		mysql_query("SET CHARACTER SET 'UTF8'");
}

function DBquery($sql, $echo = true)
{	
	$echo = 1;
	$res = mysql_query($sql,HOST_LINK);
	if(!$res) {
		if($echo) {
			echo $sql . "<br>";
			echo mysql_error();			
			echo "<pre>";
			print_r(debug_backtrace());
		}
		die();
	}
	return $res;	
}

function DBrow($sql, $echo = false)
{
	$res = DBquery($sql, $echo); //echo $sql;
	if($res){
		$arr = mysql_fetch_assoc($res);
		striprow($arr);
		mysql_free_result($res);
		return $arr;
	}else return false;	
}

function DBcol($sql, $echo = false)
{	
	$arr = Array();
	$res = DBquery($sql, $echo);
	if($res){
		while ($row =mysql_fetch_row($res)) $arr[] = stripslashes($row[0]);	
		mysql_free_result($res);	
		return $arr;	
	}else return false;
}

function DBall($sql, $echo = false)
{	
	$arr = Array();
	$res = DBquery($sql, $echo);
	if($res){
		while ($row = @mysql_fetch_assoc($res)) $arr[] = striprow($row);
		@mysql_free_result($res);	
		return $arr;
	}else
		return false;
}

function DBfield($sql, $echo = false)
{	
	$res = DBquery($sql, $echo);
	if($res){
		$arr = stripslashes(@mysql_result($res,0));	
		@mysql_free_result($res);	
		return $arr;
	}else	
		return false;
}

function DBcell($sql, $echo = false){
	return DBfield($sql, $echo); }

function DBnumrows($sql, $echo = false) //select
{	
	$res = DBquery($sql, $echo);
	if($res){
		$arr = mysql_num_rows($res);	
		mysql_free_result($res);
		return $arr;
	}else
		return false;
}

function DBinsertId(){
	return mysql_insert_id();
}

function DBaffrows($sql, $echo = false) //insert update delete
{
	$res = DBquery($sql, $echo);
	if($res){
		$arr = mysql_affected_rows(DBquery($sql));
		mysql_free_result($res);
		return $arr;
	}else
		return false;
}

function DBfields($sql, $echo = false){ //returns fields
	$return = Array();
	$query = DBquery($sql, $echo);
	$field = mysql_num_fields( $query );   
	for ( $i = 0; $i < $field; $i++ ) {   
		$f = mysql_field_name( $query, $i );   
		$return[$f]=$f;
	}
	return $return;
}


/** Query operators **/
function qCount($field = '*', $as = ''){
	return "COUNT({$field})" . ($as != '' ? ' AS ' . $as : '');
}
/* type : 1 - %var 2 - var% 3 - %var% */
function qLike($value, $type = 3) {
	if($type == (1 or 3)) $value = "%" . $value;
	if($type == (2 or 3)) $value .= "%";
	return " LIKE '$value' ";
}

function qConcat($data) {
	return " CONCAT (" . implode(',',$data) . ") ";
}

function qBetween($from, $to) {
	return " BETWEEN $from AND $to ";
}

function qEq($key, $value) {
	return "`$key` = '$value'";
}



/** DB schema functions **/
function uninstall($tables) {
	foreach($tables as $table_name => $table) {
		/** droping first; it's new install, so old table means to be dropped if exists **/
		$sql = "DROP TABLE IF EXISTS `$table_name`"; DBquery($sql);
	}
}


function install($tables) {
	/** running through all tables **/ 
	foreach($tables as $table_name => $table) {
		/** droping first; it's new install, so old table means to be dropped if exists **/
		$sql = "DROP TABLE IF EXISTS `$table_name`"; DBquery($sql);
		$sql =	"CREATE TABLE `$table_name`(";
		
		$fieldsql = array();
		foreach ($table['fields'] as  $field_name => $field){
			/** adding fields **/
			$fsql = '';
			$type = $field[0];
			switch($type){
				case 'string': $type = ' VARCHAR(255)'; break;
				case 'blob': $type = ' BLOB'; break;
				case 'text': $type = ' TEXT'; break;
				case 'int' : $type = ' INT'; break;
				case 'date' :
				case 'time' : $type = ' DATETIME'; break;
				case 'float' : $type = ' FLOAT'; break;	
				case 'bool' : $type = ' TINYINT(1)'; break;	
				default : $type = '';	
			}
			if($type != '' && $field_name!='') $fsql .= "`$field_name` $type";
			
			/** adding field options **/
			if(isset($field[2])) {
				$options = $field[2];
				if(isset($options['null'])) {
					if(!$options['null']) $fsql .= ' NOT';  $fsql .= ' NULL';
				}
				if(isset($options['ai'])) {
					$fsql .= ' AUTO_INCREMENT';
				}
				if(isset($options['default'])) {
					$fsql .= ' DEFAULT "' . $options['default'] . '"';
				}	
			}
			
			/* composing query */
			if($fsql != '')	$fieldsql[] = $fsql;
		}
		$sql .= implode(',', $fieldsql);
		
		/** adding primary key **/
		if(isset($table['pk'])) {
			if(NULL != $table['pk']) {
				$sql .=	sprintf(", PRIMARY KEY(%s)", $table['pk']);	
			}	
		} else {
			$sql .=	",\r\n id INT NOT NULL AUTO_INCREMENT PRIMARY KEY";	
		}
		
		/** adding foreing keys **/
		if(isset($table['fk'])) {
			foreach($table['fk'] as $key => $target) {
				$sql .=	sprintf(", FOREIGN KEY(%s) REFERENCES %s ON DELETE CASCADE ON UPDATE CASCADE", $key, $target);	
			}	
		}
		
		/** adding indexes **/
		if(isset($table['idx'])) {
			foreach($table['idx'] as $idx_name => $idx) {				
				$sql .= "," . (isset($idx[1]) ? 'UNIQUE' : 'INDEX') . " `$idx_name`(" . $idx[0] . ")";
			}	
		}
		
		$sql.=	");";
		
		/* executing query **/
		DBquery($sql,true);
	}
}

function update($update) {
	foreach ($update as $table_name => $fields) {
		$parts = array();
		foreach ($fields as  $field => $val){
			if($field!='') {				
				$action = $val['do'];
				$type	= $val['type'];				
				if($action == 'DROP') {
					if($type == ('index' || 'unique')) {
						"DROP INDEX($field),";
					} else {
						$parts[] = "DROP `$field`,";
					}
				} else {
					$newname = '';
					if($action == 'CHANGE') {
						$newname = "`". $val[1] . "`";
					}
					switch($type){
						case 'string': 	$type = ' VARCHAR(255)'; break;
						case 'blob': 	$type = ' BLOB'; break;
						case 'text': 	$type = ' TEXT'; break;
						case 'int' : 	$type = ' INT DEFAULT 0'; break;
						case 'date' :
						case 'time' : 	$type = ' DATETIME DEFAULT CURRENT_TIMESTAMP;'; break;
						case 'float' : 	$type = ' FLOAT DEFAULT 0'; break;	
						case 'unique':  $type = 'UNIQUE'; break;
						case 'index':	$type = 'INDEX'; break;
						default : $type = '';	
					}
					if($type!='') {
						if($action == 'CHANGE') {
							$parts[] = "CHANGE `$field` $newname $type";
						} else {
							if($type == 'UNIQUE' || $type == 'INDEX') { 
								$parts[] = "ADD $type($field)";
							} else {
								$parts[] = "ADD COLUMN `$field` $type";
							}
						}
					}		
				}
			}
		}
		$sql =	"ALTER TABLE `$table_name` " . implode(',', $parts) . ";";
	} echo $sql;
	if($sql) {
		DBquery($sql) or die(mysql_error());
	}
}


function checkTables($tables) {
	$arr = DBcol(sprintf("SHOW TABLES FROM '%s'", HOST_DB));
	return in_array($tables, $arr);
}