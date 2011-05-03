<?php 
	global $CONFIG;
	
?>

<a href="#" id="smartfiles_select_all" class="pagination_next"><?php echo elgg_echo("smartfiles:select_all"); ?></a>
<a href="#" id="smartfiles_deselect_all" class="pagination_next"><?php echo elgg_echo("smartfiles:deselect_all"); ?></a>

<span style="float: left;">
&nbsp;<strong>Selected embeds:</strong>&nbsp;
</span>
<a href="#" id="smartfiles_refresh" class="pagination_next"><?php echo elgg_echo("smartfiles:embed:refresh:bulk"); ?></a>
<a href="#" id="smartfiles_disable" class="pagination_next"><?php echo elgg_echo("smartfiles:embed:disable:bulk"); ?></a>
<a href="#" id="smartfiles_enable" class="pagination_next"><?php echo elgg_echo("smartfiles:embed:enable:bulk"); ?></a>
<script type="text/javascript">
jQuery(document).ready(function () {
    $("a#smartfiles_select_all").click(function() {
		$('input[name^=file_guids]').attr('checked', 'checked');
		return false;
    });

    $("a#smartfiles_deselect_all").click(function() {
		$('input[name^=file_guids]').removeAttr('checked');
		return false;
    });

    $("a#smartfiles_refresh").click(function() {
    	do_embed_func("refresh");
    });

    $("a#smartfiles_disable").click(function() {
    	do_embed_func("disable");
    });

    $("a#smartfiles_enable").click(function() {
    	do_embed_func("enable");
    });
    
    function do_embed_func(action_str) {
	    var form = document.createElement("form");
	    form.setAttribute("method", "post");
	    form.setAttribute("action", "<?php echo $CONFIG->wwwroot; ?>action/smartfiles/admin/embedded_files/" + action_str);
	
	    $('input[name^=file_guids]').each(function() {
	        form.appendChild(this);
	    });

	    document.body.appendChild(form);
	    form.submit();
    }
    
});
</script>
