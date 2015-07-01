<!DOCTYPE HTML>
<html>
<head>
<title><?=($class->title!=''?strip_tags($class->title) . ' - ' : '');?></title>
<link href="<?=PUB_URL;?>favicon.ico" rel="shortcut icon" type="image/x-icon" />
<script src="<?=PUB_URL;?>functions.php" type="text/javascript"></script>
<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
<LINK REL="StyleSheet" HREF="<?=PUB_URL;?>style.css" TYPE="text/css" MEDIA=screen>
<LINK REL="StyleSheet" HREF="<?=BASE_URL;?>external/shadowbox/shadowbox.css" TYPE="text/css" MEDIA=screen>
<meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<body>
<?php include('tpl.adminpanel.php'); ?>
<div class="page-wrapper">
	<div class="menu">
		<a href="<?=BASE_URL;?>" style="float:left">Главная</a>
		<center>
			<?php /*
				foreach ( $pages->treeList[TOPPAGE]['children'] as $page ) {?>
				<a href="<?=BASE_PATH.$page['url'];?>/"><?=$page['name'];?></a>
			<? } */?>
		</center>
	</div>

	
	<div class="content">
		<div class="wrap wrap-<?=$module;?>">
			<?=$content;?>		
		</div>		
	</div>
	
	<div class="page-buffer"></div>
</div>	
		
		
<div class="footer">

</div>
	
	
</body>
</html>