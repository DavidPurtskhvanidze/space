{if empty($width)}{assign var="width" value="425"}{/if}
{if empty($height)}{assign var="height" value="344"}{/if}
<div class="youTubeVideo">
	<object width="{$width}" height="{$height}" type="application/x-shockwave-flash" data="http://www.youtube.com/v/{$videoId}&amp;fs=1&amp;rel=0">
		<param name="movie" value="http://www.youtube.com/v/{$videoId}&amp;fs=1&amp;rel=0" />
		<param name="allowFullScreen" value="true" />
		<param name="allowscriptaccess" value="always" />
		<param name="wmode" value="opaque" />
	</object>
</div>
