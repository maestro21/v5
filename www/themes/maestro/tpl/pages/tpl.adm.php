<li>
	<a href="<?=BASE_URL;?><?php echo $data['fullurl'];?>" target="_blank"><?php echo $data['name'];?></a> 
	<span style="font-weight:normal !important; font-size:10px">
	<a href="<?=BASE_URL;?>pages/edit/<?=$data['id'];?>" target="_blank">[y]</a>
	<a href="<?=BASE_URL;?>pages/del/<?=$data['id'];?>">[x]</a>
	</span>
	<?php if(!empty($data['children'])) { ?>
	<ul>
		<?php echo $data['children'];?>
	</ul>
	<? } ?>
</li>	