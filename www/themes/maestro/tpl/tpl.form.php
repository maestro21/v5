<script language="javascript" src="<?=BASE_URL;?>external/nicEdit-latest.js"></script>
<?php  
$prefix = 'form';
foreach($fields as $key => $field) { 

$widget = $field[1];
$value = (isset($data[$key]) ? $data[$key] : "");
?>

<tr>
	<?php if($widget != WIDGET_HIDDEN) { ?>
	<td><?php echo T($key);?></td>
	<?php } ?>
	<td> 
	<?php switch($widget) { 

		case WIDGET_INFO: ?>
			<?php echo $value;?>
			<input type="hidden"
				value="<?php echo $value;?>" 
				name="<?php echo $prefix;?>[<?php echo $key;?>]" 
				id="<?php echo $key;?>" />
		
		
		<?php break;
		
		case WIDGET_TEXT: ?>
			<input type="text" 
				value="<?php echo $value;?>" 
				name="<?php echo $prefix;?>[<?php echo $key;?>]" 
				id="<?php echo $key;?>" />
		<?php break;
		
		case WIDGET_TEXTAREA: ?>
			<textarea cols="100" rows="20" 
				name="<?php echo $prefix;?>[<?php echo $key;?>]"
				id="<?php echo $key;?>"><?php echo $value;?></textarea>
		<?php break;
		
		case WIDGET_HTML: 
			$path = BASE_URL . 'external/maestroeditor/';
			include('external/maestroeditor/editor.php');
			maestroeditor(				
				$prefix . '[' . $key . ']',
				'test',
				$value,
				BASE_URL . 'external/maestroeditor/', 
				''
			);
			
		BREAK; /*?>
			<textarea cols="100" rows="20" 
				name="<?php echo $prefix;?>[<?php echo $key;?>]" 
				id="<?php echo $key;?>"><?php echo $value;?></textarea>
			<script type="text/javascript">
				<!--CKEDITOR.replace( "<?php echo $key;?>" );-->
				bkLib.onDomLoaded(function() {
					new nicEditor({fullPanel : true,maxHeight : 600}).panelInstance('<?php echo $key;?>');
				});
			</script>				
		<?php break;	*/		
		
		case WIDGET_BBCODE: ?>
			<textarea cols="100" rows="15" 
				name="<?php echo $prefix;?>[<?php echo $key;?>]"
				id="<?php echo $key;?>"><?php echo $value;?></textarea>
			<script type="text/javascript">
				CKEDITOR.config.toolbar_Full = [
					["Source"],
					["Undo","Redo"],
					["Bold","Italic","Underline","-","Link", "Unlink"], 
					["Blockquote", "TextColor", "Image"],
					["SelectAll", "RemoveFormat"]
				] ;
				CKEDITOR.config.extraPlugins = "bbcode";
				//<![CDATA["
					var sBasePath = document.location.pathname.substring(0,document.location.pathname.lastIndexOf("plugins")) ;
					var CKeditor = CKEDITOR.replace( "<?php echo $key;?>", { 
							customConfig : sBasePath + "plugins/bbcode/_sample/bbcode.config.js"
					}  );
				//]]>									
			</script>
		<?php break;			
		
		case WIDGET_PASS: ?>
			<input type="password" value="" name="<?php echo $prefix;?>[<?php echo $key;?>]" id="<?php echo $key;?>" />";
		<?php break;

		case WIDGET_HIDDEN: ?>
			<input type="hidden"
				value="<?php echo $value;?>" 
				name="<?php echo $prefix;?>[<?php echo $key;?>]" 
				id="<?php echo $key;?>" />
		<?php break;
		
		case WIDGET_CHECKBOX: ?>
			<input type="hidden" name="<?php echo $prefix;?>[<?php echo $key;?>]" value="">
			<input type="checkbox"  value=1 name="<?php echo $prefix;?>[<?php echo $key;?>]" id="<?php echo $key;?>" 
			<?php if($value == 1) echo " checked";?> />
		<?php break;
		
		case WIDGET_RADIO: ?>
		<?php 
			if(is_array($options) && sizeof($options) > 0) { 
				foreach (@$options[$key] as $kk => $vv){ ?>
					<?php echo T($vv);?>
					<input type="radio" 
						name="<?php echo $prefix;?>[<?php echo $key;?>]" 
						value="<?php echo $kk;?>" 
						<?php if($kk == $value) echo " checked";?> />
			<?php } ?>
		<?php } ?>
		<?php break;
		
		case WIDGET_SELECT: ?>
			<select name="<?php echo $prefix;?>[<?php echo $key;?>]" id="<?php echo $key;?>">
			<?php 
				if(is_array($options) && sizeof($options) > 0) { 
					foreach (@$options[$key] as $kk => $vv){ ?>
						<option value="<?php echo $kk;?>" 
							<?php if($kk == $value) echo " selected='selected'";?>><?php echo T($vv);?>
						</option>
				<?php } ?>
			<?php } ?>
			</select>
		<?php break;        

		case WIDGET_MULTSELECT: ?>
			<select multiple name="<?php echo $prefix;?>[<?php echo $key;?>][]" id="<?php echo $key;?>">
			<?php 
				$dat = array_flip(explode(",", $value));
				if(is_array($options) && sizeof($options) > 0) { 
					foreach (@$options[$key] as $kk => $vv){ ?>
						<option value="<?php echo $kk;?>" 
							<?php if(isset($dat[$kk])) echo " selected='selected'";?>><?php echo T($vv);?>
						</option>
				<?php } ?>
			<?php } ?>
			</select>
		<?php break;     
		case WIDGET_DATE: 
			preg_match_all("/[[:digit:]]{2,4}/", $value, $matches);	
			$nums = $matches[0]; ?>
			<input type="text" class="date year" name="<?php echo $prefix;?>[<?php echo $key;?>][y]" 
				value="<?php echo (isset($nums[0])?$nums[0]:date("Y"));?>" size="4">-
			<select name="<?php echo $prefix;?>[<?php echo $key;?>][m]>">
				<?php if(!isset($nums[1])) $nums[1] = date("m");
				for($i=1;$i<13;$i++) { ?>
					<option value="<?php echo $i;;?>"<?php if($i==@$nums[1]) echo ' selected="selected"';?>><?php echo T("mon_$i");?>
				</option>
				<?php } ?>	
			</select>					
			<input type="text" class="date" name=<?php echo $prefix;?>[<?php echo $key;?>][d] value="<?=(isset($nums[2])?$nums[2]:date("d"));?>" size=2> (YYYY-MM-DD)
		
		<?php break;
		
		
		case WIDGET_TIME: 
			preg_match_all("/[[:digit:]]{2,4}/", $value, $matches);	
			$nums = $matches[0]; ?>
			<input type="text" class="date" name=<?php echo $prefix;?>[<?php echo $key;?>][h] value="<?=(isset($nums[0])?$nums[0]:date("G"));?>" size=2>:
			<input type="text" class="date" name=<?php echo $prefix;?>[<?php echo $key;?>][mi] value="<?=(isset($nums[1])?$nums[1]:date("i"));?>" size=2>:
			<input type="text" class="date" name=<?php echo $prefix;?>[<?php echo $key;?>][s] value="<?=(isset($nums[2])?$nums[2]:date("s"));?>" size=2>(HH:MM:SS)
		
		<?php break;
		
		
		case WIDGET_DATETIME: 
			preg_match_all("/[[:digit:]]{2,4}/", $value, $matches);	
			$nums = $matches[0]; ?>
			<input type="text" class="date year" name="<?php echo $prefix;?>[<?php echo $key;?>][y]" 
				value="<?php echo (isset($nums[0])?$nums[0]:date("Y"));?>" size="4">-
			<select name="<?php echo $prefix;?>[<?php echo $key;?>][m]>">
				<?php if(!isset($nums[1])) $nums[1] = date("m");
				for($i=1;$i<13;$i++) { ?>
					<option value="<?php echo $i;;?>"<?php if($i==@$nums[1]) echo ' selected="selected"';?>><?php echo T("mon_$i");?>
				</option>
				<?php } ?>	
			</select>					
			<input type="text" class="date" name=<?php echo $prefix;?>[<?php echo $key;?>][d] value="<?=(isset($nums[2])?$nums[2]:date("d"));?>" size=2> (YYYY-MM-DD) &nbsp&nbsp&nbsp
			<input type="text" class="date" name=<?php echo $prefix;?>[<?php echo $key;?>][h] value="<?=(isset($nums[3])?$nums[3]:date("G"));?>" size=2>:
			<input type="text" class="date" name=<?php echo $prefix;?>[<?php echo $key;?>][mi] value="<?=(isset($nums[4])?$nums[4]:date("i"));?>" size=2>:
			<input type="text" class="date" name=<?php echo $prefix;?>[<?php echo $key;?>][s] value="<?=(isset($nums[5])?$nums[5]:date("s"));?>" size=2>(HH:MM:SS)
		
		<?php break;

		case WIDGET_CHECKBOXES: 
			$i = 0; 
			$dat = array_flip(explode(",",@$data[$key]));?>
			<div>
			<?php foreach (@$options[$key] as $kk => $vv){ 
				if($i % 10 == 0){  ?>
					</div><div style="float:left;border:1px black solid;">
				<?php } ?>
				<p><input type="checkbox" value="$kk" name="<?php echo $prefix;?>[<?php echo $key;?>][]" 
					<?php if(isset($dat[$kk])) echo " checked";?>><?php echo T($vv);?></p>
				<?php $i++;
			} ?>
			</div>
		<?php break;				
		
	} ?>
	</td>
	</tr>
<?php }?>