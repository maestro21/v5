#Maestro Engine v 0.5#

This is latest version of Maestro engine. There are too much significant changes in comparision to v4 which makes no sense to continue v4. It is currently developing and under construction and thus may contain lots of changes and TODO's.


##Updates##

**New file structure:**

	root
	|- engine
	|	|- masterclass.php
	|	|- functions.php
	|	|- db.php
	|	|- install.php
	|- external *external libraries and other stuff*
	|- data *site data + user content*
	|	|- settings.php
	|	|- cache *if needed*
	|	|	|- %data%.php
	|	|-up *if needed*
	|	|- db
	|	|	|- dump
	|	|	|	|- %date%.sql
	|	|	|- ini.php
	|	|	|- updates.php *initial schema + updates*
	|	|	|- schema.php *generated schema*
	|	|- langs
	|		|- %lang%.php *contains both textlabels AND cases (take them from bookster)*
	|- www *functionality*
	|	|- tpl
	|	|	|- %modulename% * + default.tpl*
	|	|	|	|- list.tpl.php
	|	|	|	|- form.tpl.php
	|	|	|- %part%.tpl.php *(i.e. adminpanel)*
	|	|	|- index.tpl.php
	|	|- img
	|	|- style.css
	|	|- script.js
	|	|- themes *copy of `front` structure*
	|	|- modules
	|	|	|- %modulename%.php
	|- index.php

**Initial tables\modules:**

* settings *also all system stuff like `update` or `install` goes through it; also here all stuff like `inactivemodules`*
* modules +
* users
* usergroups *rights are assignable here* 
* langs
* pages
* articles *blog or news*

**Enhanced user rights:**

* dont set up rights for concrete user - only for usergroup; if user need specific rights - create group
* field `rights` in `users` table with array of user rights
* simple way of right checking hasRights('rightname') - if user`s group has it in rights array then pass +
* by default following rights are required: +
	* admin - admin;
	* edit 	- admin;
	* add 	- admin;
	* save	- admin;
	* del 	- admin;
 by default following rights are not required: +
	* list
	* view

**Clean URL:** ++++

* .htaccess: 

	RewriteEngine on
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule ^(.*)$ index.php [L,QSA]
	
* in index.php 

	$_SERVER['REQUEST_URI']
	explode '?'; [$_PATH = [0][],  $_GET = [1][] ] *OR we dont need it at all?*
	
**Enhanced database schema work* - don`t brainfuck - just take it from WORKING project** ++++

* installation implies to whole project; ---- NOPE
* if you need to add new module - just run update; +
* no need to specify tables in modules - it would be taken by default; ------ NOPE
* TODO: WRITE FUNCTION `savetable` - which reads table and puts data in it and executes query;  ---- NOPE

**Clean index:** +++++

	include('autoload.php');
	/* uncomment if you want to make your website completely private
	checkLogged(); */
	$_PATH = route();
	$class = dispatch();	
	/** output **/

	if($class->ajax)
		echo $class->output;
	else	
		echo tpl('index', array(
			'content' 	=> $class->output,
			'class'		=> $class
			)
		);		


**Enhanced functionality:**

* to create query anywhere just call q(); +
* to load module anywhere just call m(); +
* masterclass($action = NULL) runs action by default if nothing specified (NULL to run default; %actionname% - to run action; FALSE = not to run); when you call loadmodule, you can specify action that would be run by fefualt;
* masterclass has new fields:
 schema - DB schema; 
 table - %classname% table from schema is taken unless other is specified;
 listfields - fields to be displayed in list; taken from %classname% table from schema if nothing is specified;
 form - form for edit; taken from %classname% table from schema if nothing is specified;
 output - HTML response of class; +
 rights - array of rights ('action' => 'required right'); + 
* masterclass now loads tpl with name(%classname%/%action%.php); 
* new default action admin is provided; list is for frontend, admin - for backend; +



###System tables and modules###
_globals 
_modules
langs
themes
user_rights
user_groups
users


###TODO: ###
1. SCHEMA commands +
2. installation +
3. CRUD controller
4. modules
5. themes & frontend (WYSYWIG etc)
6. Unit-Tests & move to Test-driven development




###DBUPDATE : ###

*Old*

	module 
	 'fields' - list of fields
		array (
			'field_name' = array( - All fields are optional
				'type'		- SQL type in table; DEFAULT - 'string';	
				'widget'	- widget to be used; DEFAULT - 'string'; 
				'in_list' 	- show in table in list action; DEFAULT - TRUE; 	
				'search' 	- use field in text search; DEFAULT - FALSE;
				'null' 		- nullable; DEFAULT - TRUE;
				'default' 	- default value; OPTIONAL - NO DEFAULT;
				'after'		- after something; OPTIONAL - NO DEFAULT; 
				'ai' 		- auto increment; DEFAULT - FALSE;
				'onAlter' 	- ALTER TABLE only; DEFAULT schemaDBQuery::ADD;
		   )
	   )
	 'pk' - field OR array of fields OR schemaDBQuery::PK_DEFAULT - would create 'id' auto_increment;
	 'pk_hide' - hide PK if it is made via schemaDBQuery::PK_DEFAULT; DEFAULT - FALSE;
	 'fk' - foreign keys
		array(
			'fk_name' = array(
				'fields' 		- array of fields or field; MUST BE SET UP;
				'table' 		- target table; MUST BE SET UP;
				'target_fields' - target fields; MUST BE SET UP;
				'onDelete' 		- what to do on delete; OPTIONAL;
				'onUpdate' 		- what to do on update; OPTIONAL;
				'onAlter' 		- ALTER TABLE only; DEFAULT schemaDBQuery::ADD;
			)
		)
	 'index' - indexes
		array(
			'idx_name' = array(
				'fields' - array of fields or field; MUST BE SET UP;
				'unique' - is unique; DEFAULT FALSE;
			)
		)


*New example:* 

	$tables = [
		'tablename' => [
			'fields' => [
				'title' 	=> ['string', 'textarea', "ai;notnull;default=0"],
				'text' 		=> ['blob'],
				'url' 		=> 'string',
				'published' => 'int',
			],
			'pk' => [], **required only if differs from default**
			'fk' => [],
		],
		
		'table2' => [
			'fields' => [
				'title' 	=> ['dbtype', 'widget', "additional-params"],
				'text' 		=> ['blob'],
				'url' 		=> 'string',
				'published' => 'int',
			],
			'pk' => [],
			'fk' => [],
		],
	]

	in **module**_ini() {
		install('tablename');
	}






