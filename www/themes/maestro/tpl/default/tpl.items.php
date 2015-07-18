<h1><?php echo T($class);?></h1>
<div class="list list-<?php echo $class;?>">
	<?php  foreach (@$data as $row){ $id = $row['id']; unset($row['id']);  ?>
		<div class="item item-<?php echo $class;?>">
			<div class="param-<?php echo $id;?>">
				<a href="<?=BASE_URL.$class?>/view/<?=$id;?>" target="_blank">#<?php echo $id;?></a>
			</div>
			<?php
			foreach($fields as $field => $f){
				$k = $field; $v = $row[$field]; ?>
				<div class="param-<?php echo $k;?>">	
					<?php echo fType($v, $f[1], @$options[$k], $field);	?>
				</div>	
			<?php }?>	
		</div>	
	<?php }?>
</div>