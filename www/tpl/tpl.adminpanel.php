<div class="maestro">
	<div class="wrap">		
		<ul class="dropdown">
			<li><a href="modules">Modules</a>
				<ul class="sub_menu">
        			 <li><a href="modules">Modules</a></li>
				</ul>
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