<h1><?php echo $title;?></h1>
<?php echo drawBtns($buttons['admin']);?>
<table cellpadding=0 cellspacing=0>
	<thead>
	<tr>
		<td>
		<a href="<?=BASE_URL.$class?>filter/sort_<?=$class;?>/<?='id_'.getFilterState($class,'id');?>">id</a><?=filterImg($class,'id');?>
		</td>
		<?php foreach ($fields as $k=>$v){?>
			<td><a href="<?=BASE_URL.$class?>filter/sort_<?=$class;?>/<?=$k.'_'.getFilterState($class,$k);?>"><?=T($k);?></a><?=filterImg($class,$k);?></td>
		<? }?>
		<td><?=T('options');?></td>
	</tr>
	</thead>
	<?php  foreach (@$data as $row){ 
		$id = $row['id']; unset($row['id']); ?>
		<tr>
		<td>
			<a href="<?=BASE_URL.$class?>/view/<?=$id;?>" target="_blank">#<?php echo $id;?></a>
		</td>
		<?php 
		foreach($fields as $field => $f){
			$k = $field; $v = $row[$field];
			echo "<td>".fType($v, $f[1], @$options[$k])."</td>";	
		}?>
		<td width=150>
			<a href="<?=BASE_URL.$class?>/edit/<?=$id;?>" target="_blank"><?=T('edit');?></a>
			<a href="javascript:void(0)" onclick="conf('<?=BASE_URL.$class?>/del/<?=$id;?>', '<?=T('del conf');?>')"><?=T('del');?></a>	
		</td>
		</tr>		
	<?}?> 
</table>