<div class="adminpanel">
	<div class="wrap">		
		<ul class="dropdown">
			<li><a href="modules">Modules</a>
				<?php $modules = cache('modules'); 
				if($modules) {?>
					<ul class="sub_menu">
						<?php foreach($modules as $module) { ?>
							<li><a href="<?php echo BASE_URL . $module['name'];?>/admin"><?php echo T($module['name']);?></a></li>
						<?php } ?>	
					</ul>
				<?php } ?>
			</li>		
		</ul>
	</div>
</div>

<script>
$(function(){
    $("ul.dropdown li").hover(function(){    
        $(this).addClass("hover");
        $('ul:first',this).css('visibility', 'visible');    
    }, function(){    
        $(this).removeClass("hover");
        $('ul:first',this).css('visibility', 'hidden');    
    });    
    $("ul.dropdown li ul li:has(ul)").find("a:first").append(" &raquo; ");

});

</script>