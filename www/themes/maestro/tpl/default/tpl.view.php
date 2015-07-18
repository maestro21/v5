<table>
<?php foreach ($data as $k => $v){ ?>
	<tr>
		<td><?=T($k);?></td>
		<td><?=(isset($options[$k])?T($options[$k][$v]):$v);?></td>		
	</tr>	
<?php	
} ?>
</table>