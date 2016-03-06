<h1><?php echo $title;?></h1>
<?php $formid = $class . '_form_item_' . $id;?>
<form method="POST" id="<?=$formid;?>" class="content">
<input type="hidden" name="id" id="id" value="<?=$id;?>">
	<table cellpadding=0 cellspacing=0>
	<?php 
		echo drawForm($fields, $data, $options); 
	?>
		<tr>
			<td colspan="2" align="center">
				<div class="btn" id="saveBtn" ><?=T('save');?></div>
				<div class="ok" id="<?=$formid;?>_savemsg"><?=T('save_msg');?></div>
			</td>
		</tr>
	</table>	
</form>

<script src="<?=BASE_URL;?>external/savectrls.js" type="text/javascript"></script>
<script>

	$("#<?=$formid;?>_savemsg").toggle();
	function saveFn(){ saveForm('<?=$formid;?>','<?=BASE_URL  . $class?>/save?ajax=1'); }		
	$('#saveBtn').click(function() { saveFn() });

</script>	