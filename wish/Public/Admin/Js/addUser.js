$(function(){
	$('.add-role').click(function(){
	  var obj = $(this).parents('tr').clone();
	  obj.find('.add-role').remove();
	  $('#last').before(obj);
});
});
