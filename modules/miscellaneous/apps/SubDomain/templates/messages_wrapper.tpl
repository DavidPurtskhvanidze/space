{$id = uniqid()}
<div class="{$id}">
	{include file="$typeWrapperTemplate"}
</div>
{require component="jquery" file="jquery.js"}
<script>
var pictureUrl = '{url file="main^icons/cross.png"}';
$(document).ready(function(){
	var html = '<a href="#" class="closeButton" title="[[Close:raw]]">';
	html += '<img src="' + pictureUrl + '" /></a>';
	$('.{$id} .messagesWrapper').each(function(){
		if ($(this).find('.closeButton').length < 1)
			$(this).append(html);
	});
	$(document).on('click','.{$id} .messagesWrapper .closeButton',function(){
		$('.{$id} .messagesWrapper').remove();
		return false;
	});
});
</script>
