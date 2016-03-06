<p><?php echo T('addlink_disclaimer');?>:</p>
<textarea class="link_disclaimer">
javascript:(function(){ var i = document.createElement("img"); i.src = "<?php echo G('importlinks_url');?>?do=save&stream=<?php echo G('importlinks_stream');;?>&url="+encodeURIComponent(location.href)})();
</textarea>
<br>
	<h1><?php echo $title;?></h1> 
	<div class="btn" id="links_btn_importLink" ><?=T('Import Links');?></div>
	<a href="javascript:void(0);" onclick="modal('<?php echo BASE_URL . "links/edittags";?>')" target="_blank"><?=T('Edit tags');?></a>
	<div class="ok" id="links_msg_importLink"><?=T('import_msg');?></div>
<p>

<form method="GET" id="links_form_addLink" class="content" action="<?=BASE_URL?>api.php?do=saveLink">
	<?php echo T('add link');?> 
	<input type="text" name="url" class="addlink">
	<input type="hidden" name="do" value="savelink">
	<div class="btn" id="links_btn_addLink" ><?=T('save');?></div>
	<div class="ok" id="links_msg_save"><?=T('save_msg');?></div>
</form>
</p>
<hr>
<div class="linkList">
	<table>
	<?php //inspect($data); 

	$date = '';
	foreach($data as $k => $v) {  
		$id = $v['id'];
		$datetime = explode(' ',$v['time']);
		if($date != $datetime[0]) {
			$date = $datetime[0];
			echo "</table><h2>" . fDate($date) . "</h2><table cellpadding=0 cellspacing=0>";
		}
		$time = $datetime[1];
	?>
		<tr<?php if(empty($v['tags'])) echo " class='notags'";?>>
			<td class="date" width="50px">
				<?php echo fTime($time);?> 
			</td>
			<td width="700px">
				<?php if($v['title']) { 
					if($v['title'] == '%IMG%') { ?>
						<div class="links_list_img">
							<img src="<?php echo $v['url'];?>">
						</div>
					<?php } else { ?>
						<a href="<?php echo $v['url'];?>" target="blank"><?php echo $v['title'];?><br><i class="link_desc"><?php echo $v['url'];?></i>
						</a>
					<?php } ?>
				<?php } else { ?>
					<a href="<?php echo $v['url'];?>" target="blank"><?php echo $v['url'];?></a>	
				<?php } ?>
			</td>
			<td width="150px" class="date">
				<?php echo T('Tags');?>:
				<?php if($v['tags']) {
					$tags = explode(',', $v['tags']);
					foreach($tags as $tag) { ?>
						<a href="<?php echo BASE_URL . "links/admin/0/$tag";?>"><?php echo $tag;?></a>
					<?php }
				} ?>
			</td>
			<td width="100px" class="date">
				<a href="javascript:void(0)" onclick="modal('<?=BASE_URL.$class?>/edit/<?=$id;?>?ajax=1')" target="_blank"><?=T('edit');?></a>
				<a href="javascript:void(0)" onclick="conf('<?=BASE_URL.$class?>/del/<?=$id;?>', '<?=T('del conf');?>')"><?=T('del');?></a>	
			</td>
		</tr>
		
	<? } ?>
	</table>
</div>
<style>
	.addlink {
		width: 500px;
		height:20px;
	}	
	.link_disclaimer {
		width:900px;
	}
	

	.link_desc {
		color:gray;
		font-weight:normal;
		font-size:10px;
		text-decoration:none;
		font-style:normal;
	}
	
	.linkList td {
		vertical-align:top;
	}
	
	.linkList .date {
		padding-top:10px;
	}
	
	
	.notags {
		background-color:lightgray;
	}
	
	
	/* image positioning */
	div.links_list_img{
		width: 500px;
		height:100px;
		overflow:hidden;
		display:flex;		
	}
	div.links_list_img img {
		max-width:500px;
	}
	
</style>

<script language="javascript">

	$("#links_msg_importLink").hide();
	$("#links_msg_save").hide();
	function saveFn(){ sendGetForm('links_form_addLink','<?=BASE_URL?>api.php'); }		
	$('#links_btn_addLink').click(function() { saveFn() });
	
	$('#links_btn_importLink').click(function() {
		$.get('<?=BASE_URL . $class?>/importLinks?ajax=true')
		.done(function( data ) {
			$("#links_msg_importLink").show(500);
			setTimeout(function() {
				window.location.reload();
			},3000);	
		});
	});
	
	$.get('<?=BASE_URL . $class?>/importLinks?ajax=true');
	

</script>


