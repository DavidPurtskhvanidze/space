$(document).ready(function(){
	$(".maxlength").each(function(){
		var maxlen = $(this).attr('maxlength');
		$(this).maxlength( {maxCharacters: maxlen, statusText: '', statusClass: 'chararactersLeftNumber'});
	});
});
