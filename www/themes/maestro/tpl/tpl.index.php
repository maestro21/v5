<!DOCTYPE HTML>
<html>
<head>
<title><?=($class->title!=''?strip_tags($class->title) . ' - ' : '') . G('sitename');?></title>
<link href="<?=PUB_URL;?>favicon.ico" rel="shortcut icon" type="image/x-icon" />
<script src="<?=PUB_URL;?>functions.php" type="text/javascript"></script>
<script src="<?=BASE_URL;?>external/jquery-latest.min.js" type="text/javascript"></script>
<Script> BASE_URL = '<?=BASE_URL;?>';</script>
<script src="<?=themePath();?>script.js" type="text/javascript"></script>
<LINK REL="StyleSheet" HREF="<?=furl();?>style.css" TYPE="text/css" MEDIA="screen">
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<body>
<?php include('tpl.adminpanel.php'); ?>
<div class="page-wrapper">
	<div class="menu">
		<a href="<?=BASE_URL;?>" style="float:left">Главная</a>
		<center>
			<div class="dropdown">
				<ul class="topmenu">
					<?php echo menu(); ?>
				</ul>
			</div>	
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

<div class="modal-overlay"></div>	
<section id="modal" class="modal">
	<div class="modal-close">X</div>
	<div class="modal-body">	
	</div>
</section>
	
	
</body>
</html>