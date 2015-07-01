<?php switch ($widget){
	case WIDGET_TEXT: ?>
		<input type="text" 
			value="<?php if(isset($data[$key])) echo $data[$key];?>" 
			name="<?php echo $prefix;?>[<?php echo $key;?>]" 
			id="<?php echo $key;?>" />
	<?php break;
	
	case WIDGET_TEXTAREA: ?>
		<textarea cols="100" rows="20" 
			name="<?php echo $prefix;?>[<?php echo $key;?>]"
			id="<?php echo $key;?>"><?php if(isset($data[$key])) echo $data[$key];?></textarea>
	<?php break;
	
	case WIDGET_HTML: ?>
		<textarea cols="100" rows="20" 
			name="<?php echo $prefix;?>[<?php echo $key;?>]" 
			id="<?php echo $key;?>"><?php if(isset($data[$key])) echo $data[$key];?></textarea>
		<script type="text/javascript">
			CKEDITOR.replace( "<?php echo $key;?>" );
		</script>				
	<?php break;			
	
	case WIDGET_BBCODE: ?>
		<textarea cols="100" rows="15" 
			name="<?php echo $prefix;?>[<?php echo $key;?>]"
			id="<?php echo $key;?>"><?php if(isset($data[$key])) echo $data[$key];?></textarea>
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
			value="<?php if(isset($data[$key])) echo $data[$key];?>" 
			name="<?php echo $prefix;?>[<?php echo $key;?>]" 
			id="<?php echo $key;?>" />";
	<?php break;
	
	case WIDGET_CHECKBOX: ?>
		<input type=hidden name="<?php echo $prefix;?>[<?php echo $key;?>]" value=""><input type=checkbox  value=1 ".(@$data[<?php echo $key;?>]==1?"checked":"")." name="<?php echo $prefix;?>[<?php echo $key;?>]" id=$k />
";
	<?php break;
	
	case WIDGET_RADIO: ?>
	<?php foreach (@$options[$key>] as $kk => $vv){ ?>
			<?php echo T($vv);?>
			<input type="radio" 
				name="<?php echo $prefix;?>[<?php echo $key;?>]" 
				value="<?php echo $kk;?>" 
				<?php if($kk==@$data[$key;]?) echo " checked";?> />
	<?php } ?>
	<?php break;
	
	case WIDGET_SELECT: ?>
		<select name=<?php echo $prefix;?>[<?php echo $key;?>] id=<?php echo $key;?>>
		<?php foreach (@$options[<?php echo $key;?>] as $kk => $vv){ ?>
			$return .="<option value="$kk"".($kk==@$data[<?php echo $key;?>]?" selected="selected"":"").">".T($vv)."</option>";
		}
		$return .="</select>";
	<?php break;        

	case WIDGET_MULTSELECT: ?>
		$class = "tainfo";
		$return .="<select multiple name=<?php echo $prefix;?>[<?php echo $key;?>][] id=$k>";
		$dat = array_flip(explode(",",@$data[<?php echo $key;?>]));
		foreach (@$options[<?php echo $key;?>] as $kk => $vv){
			$return .="<option value="$kk"".(isset($dat[$kk])?" selected="selected"":"").">".T($vv)."</option>";
		}
		$return .="</select>";
	<?php break;     

	case WIDGET_DATE: ?>
		preg_match_all("/[[:digit:]]{2,4}/",@$data[<?php echo $key;?>],$matches);	
		$nums = $matches[0]; 
		$return .="<input type="text" class="date year" name=<?php echo $prefix;?>[<?php echo $key;?>][y] value="".(isset($nums[0])?$nums[0]:date("Y"))."" size=4>-";
		$return .="<select name=<?php echo $prefix;?>[<?php echo $key;?>][m]>"; if(!isset($nums[1])) $nums[1] = date("m");
		for($i=1;$i<13;$i++) $return .= "<option value="$i"".($i==@$nums[1]?" selected="selected"":"")."">".T("mon_$i")."</option>";			
		$return.="</select>-";
				
		$return .="<input type="text" class="date" name=<?php echo $prefix;?>[<?php echo $key;?>][d] value="".(isset($nums[2])?$nums[2]:date("d"))."" size=2> &nbsp&nbsp&nbsp";
		$return .="<input type="text" class="date" name=<?php echo $prefix;?>[<?php echo $key;?>][h] value="".(isset($nums[3])?$nums[3]:date("G"))."" size=2>:";
		$return .="<input type="text" class="date" name=<?php echo $prefix;?>[<?php echo $key;?>][mi] value="".(isset($nums[4])?$nums[4]:date("i"))."" size=2>:";
		$return .="<input type="text" class="date" name=<?php echo $prefix;?>[<?php echo $key;?>][s] value="".(isset($nums[5])?$nums[5]:date("s"))."" size=2>(HH:MM:SS)";
	
	<?php break;

	case WIDGET_CHECKBOXES: ?>
		$class = "tainfo";
		$return .="<div>";
		$i = 0; 
		$dat = array_flip(split(",",@$data[<?php echo $key;?>]));// inspect($dat);
		foreach (@$options[<?php echo $key;?>] as $kk => $vv){
			if($i % 10 == 0){ $return .="</div><div style="float:left;border:1px black solid;">"; }// var_dump(isset($dat[$kk]));
			$return .="<p><input type="checkbox" value="$kk" name="<?php echo $prefix;?>[<?php echo $key;?>][]"".(isset($dat[$kk])?" checked":"").">".T($vv)."</p>";
			$i++;
		}
		$return .="</div>";
	<?php break;				
	
} ?>