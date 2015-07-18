<!DOCTYPE HTML>
<html>
<head>
<title><?=($class->title!=''?strip_tags($class->title) . ' - ' : '');?></title>
<link href="<?=PUB_URL;?>favicon.ico" rel="shortcut icon" type="image/x-icon" />
<script src="<?=PUB_URL;?>functions.php" type="text/javascript"></script>
<script src="<?=BASE_URL;?>external/jquery-latest.min.js" type="text/javascript"></script>
<script src="<?=themePath();?>script.js" type="text/javascript"></script>
<LINK REL="StyleSheet" HREF="<?=furl();?>style.css" TYPE="text/css" MEDIA="screen">
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
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
		<div class="wrap wrap-<?=$class->className .  ' ' . $class->tpl;?>">
			<?=$content;?>		
		</div>		
	</div>
	
	<div class="page-buffer"></div>
</div>	
		
		
<div class="footer">

</div>
	
	
</body>
</html>