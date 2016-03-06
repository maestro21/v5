<?php 

function maestroeditor($name, $key, $value, $path2Icons = '', $path2Include = '') {
	require_once('include.php');?>

	<div class="MaestroEditor" id="MaestroEditor_<?php echo @$key;?>">
		<div id="MaestroEditor_<?php echo @$key;?>_toolbar" class="toolbar"></div>
		<div class="area hidden" id="MaestroEditor_<?php echo @$key;?>_html" contenteditable></div>
		<textarea class="area" name="<?php echo $name;?>" id="MaestroEditor_<?php echo @$key;?>_text"><?php echo @$value;?></textarea>
	</div>
	<script language="javascript">
		MaestroEditor('<?php echo @$key;?>');
	</script>

<?php } ?>