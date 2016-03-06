<div class="adminpanel">
	<div class="wrap">		
		<ul class="dropdown">
			<li><a href="modules">Modules</a>
				<?php $modules = cache('modules'); 
				if($modules) {?>
					<div class="dropdownmenu">
						<?php foreach($modules as $module) { 
							if($module['status'] > 1) {?>
							<a href="<?php echo BASE_URL . $module['name'];?>/admin"><?php echo T($module['name']);?></a>
						<?php } 
						}?>	
					</div>
				<?php } ?>
			</li>		
		</ul>
	</div>
</div>

<script>
$(function(){
    $("ul.dropdown li").mouseenter(function(){    
        $(this).addClass("hover");       
    });
	
	$(".dropdownmenu").mouseleave(function(){    
        $(this).parent().removeClass("hover"); 
    });
});

</script>