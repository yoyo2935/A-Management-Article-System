$(function(){
	$('input[level=1]').click(function(){
	var inputs = $(this).parents('.app').find('input');
	$(this).attr('checked')?inputs.attr('checked','checked'):inputs.removeAttr('checked');
			});
	$('input[level=2]').click(function(){
	var inputs = $(this).parents('dl').find('input');
	$(this).attr('checked')?inputs.attr('checked','checked'):inputs.removeAttr('checked');
			});
			});
