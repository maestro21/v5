<?php 
include('../autoload.php');
echo "<pre>";
echo ":::::::::: DB TEST :::::::::: \n\r";
$oDB = new dbMySQL('testtable');
$data = array( 'id' => 1 , 'text' => 'blablabla');

echo $oDB->getItemQuery(1)->compose()->getRawQuery() . "\n\r"; 
echo $oDB->getItemsQuery()->compose()->getRawQuery() . "\n\r";
echo $oDB->deleteItemQuery(2)->compose()->getRawQuery() . "\n\r";
echo $oDB->saveItemQuery($data)->compose()->getRawQuery() . "\n\r";
echo $oDB->insertItemQuery($data)->compose()->getRawQuery() . "\n\r";
echo $oDB->updateItemQuery($data, dbEq('id',1))->compose()->getRawQuery() . "\n\r";

$oDB->select()
	->select('tt.testfield')
	->select('tt.testfield','tf')
	->from('testtable')
	->from('testtable', 'tt')
	->join('testtable2',dbEq('tt.id','`testtable2.tt_id`'))
	->join('testtable3',dbEq('tt.id','`testtable2.tt_id`'), 'tt3')
	->join('testtable4',dbEq('tt.id','`tt4.tt_id`'), 'tt4','LEFT')
	->group('tt.id')
	->compose();
	
echo $oDB->getRawQuery() . "\n\r";

echo ":::::::: DB SCHEMA TEST :::::::: \n\r";
include(BASE_PATH . "engine/schemadbquery.php");
include(BASE_PATH . "engine/schema" . DB_TYPE . ".php");
$schema = array(
	'testtable' => array(
		'fields' => array (
			'name' => array (
				'type' => 'string',
				'widget' => 'string',
				'in_list' => true,
				'index' => true,
			),
			'value' => array (
				'type' => 'text',
				'widget' => 'text',
				'null' => true,
			),
		),	
	),
);

foreach($schema as $tablename => $table) {
	$oDB = new schemaDBqueryMySQL($tablename, $table);
	echo $oDB->create()->compose()->getRawQuery() . "\n\r";
	echo $oDB->update()->compose()->getRawQuery() . "\n\r";
	echo $oDB->delete()->compose()->getRawQuery() . "\n\r";
}

