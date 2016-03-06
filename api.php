<?php include('autoload.php');
$do = $_GET['do'];
$api = new api();
if(method_exists($api, $do)) {
	$api->$do();
}

/**  API class **/

class api {

	function saveLink() {
		global $_GET;
		$url = $_GET['url'];
		if($url) echo M('links')->saveByUrl($url);
	}
}
