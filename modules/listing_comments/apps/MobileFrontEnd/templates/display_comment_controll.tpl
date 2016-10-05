{assign var="uriParametersPart" value=$listing.id|cat:"/"}
{if $controll == 'DISPLAY_COMMENTS_ON_SUBPAGE_MENU'}
	<a href="{page_path id='listing'}comments/{$uriParametersPart}">[[Comments]]<span class="bullet">»</span></a>
{/if}
