<h1><?php echo $title;?></h1> <a href="<?=BASE_URL?>daily/add"><?php echo T('write');?></a>
<?php if($data) foreach($data as $day) { ?>
	<div>
	<h2><?php echo fdate($day['day']); ?></h2> 
		<a href="<?=BASE_URL.$class?>/edit/<?=$day['day'];?>" target="_blank"><?=T('edit');?></a>
		<a href="javascript:void(0)" onclick="conf('<?=BASE_URL.$class?>/del/<?=$day['id'];?>', '<?=T('del conf');?>')"><?=T('del');?></a>
	<div><?php echo nl2br($day['text']);?></div>
	</div>
	<hr>
<?php } ?>