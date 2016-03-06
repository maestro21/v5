<?php 
/** File to include wysywig once **/

/**
	Toolbar array
	group => [
		tag => [function, display]
	]

**/ 
$editorBtns = [
	"html"	=> [
		"2text"	=> 	[ "switch2Text(editor_id)", "&nbsp;"],
		/*"b"		=>	[ "exec('bold')", "<b>B</b>"],
		"i"		=>	[ "exec('italic')", "<i>I</i>"],
		"u"		=>	[ "exec('underline')", "<u>U</u>"],
		"s"		=>	[ "exec('strikethrough')", "<strike>S</strike>"],*/
	],

	"main"	=> 	[
		"2html"	=> 	[ "switch2HTML(editor_id)", "&nbsp;"],
		"b"		=>	[ "wT(editor_id,'b')", "<b>B</b>"],
		"i"		=>	[ "wT(editor_id,'i')", "<i>I</i>"],
		"u"		=>	[ "wT(editor_id,'u')", "<u>U</u>"],
		"s"		=>	[ "wT(editor_id,'s')", "<strike>S</strike>"],
	],
	
	"advanced" => [
		"sub"	=>	[ "wT(editor_id,'sub')", "1<sub>2</sub>"],
		"sup"	=>	[ "wT(editor_id,'sup')", "1<sup>2</sup>"],
		"font"	=>	[ "toggle(editor_id,'font')", "&nbsp;</div>" . getDropdownFont()],
		"h"		=>	[ "toggle(editor_id,'headers')", "<b>H</b></div>" . getDropdownHeaders()],
	],
	
	"blocks" => [
		"quote"	=>	[ "wT(editor_id,'blockquote')", "&nbsp;"],
		"divs"	=>	[ "toggle(editor_id,'divs')", "P</div>" . getDropdownDivs()],
		"line"	=> 	[ "insHTML(editor_id,'hr')", "&nbsp;"],
	],
	
	"lists"	=> 	[
		"ul"	=> 	[ "wT(editor_id,'ul')", "&nbsp;"],
		"ol"	=>	[ "wT(editor_id,'ol')", "&nbsp;"],
		"li"	=>	[ "wT(editor_id,'li')", "&nbsp;"],
	],
	
	"table"	=>	[
		"table"	=>	[ "wT(editor_id,'table')", "&nbsp;"],
		"thead" =>	[ "wT(editor_id,'thead')", "&nbsp;"],
		"tr"	=>	[ "wT(editor_id,'tr')", "&nbsp;"],
		"td"	=>	[ "wT(editor_id,'td')", "&nbsp;"]
	],
	
	"insert" => [
		"img" 	=> [ "toggle(editor_id,'img')",  "&nbsp;</div>" . getDropdownImg()],
		"link"	=> [ "toggle(editor_id,'link')", "&nbsp;</div>" . getDropdownLink()],
		//"vid"	=> [],
	],
];




function getDropdownFont() 
{
	return '<div class="font-dialog dd">Color:   <input type="text" class="font-dialog-color"> <br>Family: <input type="text" class="font-dialog-family"> <br>Size: <input type="text" class="font-dialog-size"> <br><a href="javascript:;" class="submit">Add</a>';
}


function getDropdownHeaders() 
{
	$html = '<div class="headers-dialog headerlist dd">';
	for($i = 1; $i < 7; $i++){
		$html .= '<a href="javascript:fH(\\\'\' + editor_id + \'\\\',\\\'h' . $i  . '\\\');"><h' . $i . '>Header ' . $i . '</h' . $i . '></a>';
	}
	return $html;
}


function getDropdownDivs() 
{
	$divs = array('left', 'center', 'right', 'done');
	$html = '<div class="divs-dialog divlist dd">';
	foreach($divs as $div){
		$html .='<a href="javascript:fDiv(\'{key}\',\''.$div.'\');"><div class="'.$div.'">'.$div.'></div></a>';
	}
	return $html;
}


function getDropdownImg() 
{
	return '<div class="img-dialog dd">
		URL: <input type="text" class="url"><br>
		W: <input type="text" style="width:50px" class="width"> 
		H: <input type="text" style="width:50px"  class="height">
		<a href="javascript:;" class="submit">Add</a>';	
}

function getDropdownLink() 
{
	return '	
	<div class="link-dialog dd">
		URL <input type="text" class="url"> <br />
		Open in a new window <input type="checkbox" class="target-blank"> <br />
		<a href="javascript:;" class="submit">Add</a>';	
	return $html;
}
?>
<div class="hidden" id="toolbar_tpl">
	<?php foreach($editorBtns as $groupname => $group) { ?>
		<div class="group <?php echo $groupname;?>" id="{key}_group_<?php echo $groupname;?>">
		<?php foreach($group as $btnname => $btn) { ?>
			<div class="btn btn-<?php echo $btnname;?>" 
			id="{key}_<?php echo $groupname . '_' . $btnname;?>"><?php echo $btn[1];?></div>
		<?php } ?>
		</div>
	<?php } ?>
</div>
<script language="javascript">

	function switch2HTML(key) {
		$("#" + key + "_html").html($("#" + key + "_text").val().replace(/\\r\\n|\\n|\\r/g, '<br>'));
		$("#" + key + "_html").removeClass("hidden");
		$("#" + key + "_text").addClass("hidden");		
		$("#" + key + " .group").addClass("hidden");
		$("#" + key + " .html").removeClass("hidden");
		$("#" + key + "_text").hide();
	}
	
	function switch2Text(key) {
		$("#" + key + "_text").val($("#" + key + "_html").html().replace(/<br>/g, '\\\n'));
		$("#" + key + "_text").removeClass("hidden");
		$("#" + key + "_html").addClass("hidden");
		$("#" + key + " .group").removeClass("hidden");
		$("#" + key + " .html").addClass("hidden");
		$("#" + key + "_text").show();
	}

	function fH(key, tag) {
		wrapText(key, "<"+ tag +">", "</"+ tag +">");
		$('#' +  key + ' .headers-dialog').toggle();
	}
	
	function wT(key, tag){
		wrapText(key, "<"+ tag +">", "</"+ tag +">");
	}

	function fDiv(key, dclass){
		wrapText(key, '<div class="' + dclass + '">', "</div>");
		$('#' +  key + ' .divs-dialog').toggle();
	}

	function wrapText(key, openTag, closeTag) {
		var textArea = $('#' + key + '_text');
		var len = textArea.val().length;
		var start = textArea[0].selectionStart;
		var end = textArea[0].selectionEnd;
		var selectedText = textArea.val().substring(start, end);
		var replacement = openTag + selectedText + closeTag;
		textArea.val(textArea.val().substring(0, start) + replacement + textArea.val().substring(end, len));
	}

	function insHTML(key, html){
		var textArea = $('#' + key + '_text');
		var len = textArea.val().length;
		var start = textArea[0].selectionStart;
		var end = textArea[0].selectionEnd;
		textArea.val(textArea.val().substring(0, start) + html + textArea.val().substring(end, len));
	}

	function toggle(key, dd) {
		console.log('#' + key + ' .' + dd + '-dialog');
		$('#' + key + ' .' + dd + '-dialog').toggle();
	}

	function exec(command) { console.log(command);
		document.execCommand(command, null, null ); 	
	}


	function MaestroEditor(key) {
		var editor_id = "MaestroEditor_" + key;
		var toolbar = $("#toolbar_tpl").html().replace(/\{key\}/g, editor_id);
		console.log(toolbar);
		$('#' + editor_id + '_toolbar').html(toolbar);
		$("#" + editor_id + " .html").addClass("hidden");
		$(".dd").hide();
		
		
		/* Adding functionality to buttons */
		<?php foreach($editorBtns as $groupname => $group) { 
			foreach($group as $btnname => $btn) { ?>
				$( '#' +  editor_id + '_<?php echo $groupname . '_' . $btnname;?>').on( 'click', function() {
					<?php echo $btn[0];?>
				});
			<?php } 
		} ?>
		
		
		/* Adding functionality to dialogs */
		$('#' +  editor_id + ' .link-dialog .submit').on( 'click', function() {
			var url = $('#' +  editor_id + ' .link-dialog .url').val();
			var params = '';
			if($('#' +  editor_id + ' .link-dialog .target-blank').is(':checked')) params = ' target="_blank"';
			wrapText(editor_id, '<a href="' + url + '"' + params + '">', "</a>");
			$('#' +  editor_id + ' .link-dialog').toggle();
		});
		
		$('#' +  editor_id + ' .font-dialog .submit').on( 'click', function() {
			var html = '<font';
			if($('#' +  editor_id + ' .font-dialog-color').val() != '') html += ' color="' + $('#' +  editor_id + ' .font-dialog-color').val() + '"';
			if($('#' +  editor_id + ' .font-dialog-size').val() != '') html += ' size="' + $('#' +  editor_id + ' .font-dialog-size').val() + '"';
			if($('#' +  editor_id + ' .font-dialog-family').val() != '') html += ' face="' + $(' .font-dialog-family').val() + '"';
			html += '>';
			wrapText(editor_id, html, "</font>");
			$('#' +  editor_id + ' .font-dialog').toggle();
		});
		
		$('#' +  editor_id + ' .img-dialog .submit').on( 'click', function() {
			var url 	= $('#' +  editor_id + ' .img-dialog .url').val();
			var width 	= $('#' +  editor_id + ' .img-dialog .width').val();
			var height 	= $('#' +  editor_id + ' .img-dialog .height').val();
			var html 	= "<img src='" + url + "' width='" + width + "' height='" + height + "'>";
			insHTML(editor_id, html);
			$('#' +  editor_id + ' .img-dialog').toggle();
		});

	}
</script>
<style>
	<?php include($path2Include . 'style.css');?>
</style>

