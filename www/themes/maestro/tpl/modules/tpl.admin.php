<h1><?php echo $title;?></h1>
<?php echo drawBtns($buttons['admin']);?>
<?php 
$statuses = ['not','far','ok'];
$statustexts = ['not installed','installed','activated'];
 foreach (@$data as $module){ ?>
	<div class="<?php echo $statuses[$module['status']];?>">
		<div class="info">
			<h2><a href="<?php echo BASE_URL . $module['name'];?>"><?php echo $module['name'];?></a></h2>
			<?php echo $module['description']; ?>
		</div>
		<div class="status">
			<?php echo T($statustexts[$module['status']]);?>
		</div>
		<div class="btns">
			<?php switch($module['status']) { 
					//Not installed
					case 0:?>
						<a class="btn btn-active" href="javascript:changestatus('<?php echo $module['name'];?>', 1);"><?php echo T('Install');?></a>
			<?php  	break;
			
					//Installed, not activated
					case 1:?>						
						<a class="btn btn-ok" href="javascript:changestatus('<?php echo $module['name'];?>', 2);"><?php echo T('Activate');?></a>
						<a class="btn btn-del" href="javascript:changestatus('<?php echo $module['name'];?>', 0);"><?php echo T('Uninstall');?></a>
			<?php	break;

					case 2: ?>						
						<a class="btn" href="javascript:changestatus('<?php echo $module['name'];?>', 1);"><?php echo T('Deactivate');?></a>
						<a class="btn btn-del" href="javascript:changestatus('<?php echo $module['name'];?>', 0);"><?php echo T('Uninstall');?></a>
			<?php  } ?>
		</div>
	</div>
<?php } ?>

<script>

function changestatus(module, status_id) {
	$.get(BASE_URL + 'modules/changestatus/' + module + '?ajax=1&status=' + status_id)
		.done(function() {
			//window.location.reload(0);	
	});
}

</script>