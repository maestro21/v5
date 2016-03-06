<li>
	<a href="<?=BASE_URL;?><?php echo $data['fullurl'];?>"><?php echo $data['name'];?></a> 
	<?php if(isset($data['children']) && !empty($data['children'])) { ?>
	<ul>
		<?php echo $data['children'];?>
	</ul>
	<? } ?>
</li>