{assign var="bannerLinkId" value="bannerLink`$banner.uq_id`"}
{assign var="bannerContainer" value="bannerContainer`$banner.uq_id`"}
{assign var="bannerRotator" value="bannerRotator`$banner.uq_id`"}
{assign var="bannerCounter" value="bannerCounter`$banner.uq_id`"}

<div id="{$bannerContainer}" style="{if $banner.width}max-width:{$banner.width}px; {/if}vertical-align:middle; text-align:center;">
</div>
{require component="jquery" file="jquery.js"}
<script type="text/javascript">
function {$bannerRotator}(currDisplayOrder) {ldelim}
	$.ajax({ldelim}
		url: '{page_path module='banners' function='show_banner_group'}',
		data: {ldelim}
			group_name: '{$banner.name}',
			last_displayed: currDisplayOrder,
			json: 1
		{rdelim},
		cache: false,
		dataType: 'json',
		success: function(data) {ldelim}
			$('#{$bannerContainer}').html(data.content_data.src);

			if (data.content_data.url)
			{
				$('#{$bannerContainer} a').each(function(index,element) {ldelim}
					$(element).attr('href',data.content_data.url);
				{rdelim});
				$('#{$bannerContainer}').css('cursor', 'pointer');
				$('#{$bannerContainer}').bind('click',function(){ldelim}
					{$bannerCounter}(data.content_data.sid);
					window.location.href=data.content_data.url;
				{rdelim});
			}
			else
			{
				$('#{$bannerContainer} a').bind('click', function() {ldelim}
					{$bannerCounter}(data.content_data.sid);
					return true;
				{rdelim});
			}

			setTimeout("{$bannerRotator}("+data.content_data.display_order+")", data.content_data.delay * 1000);
		{rdelim}
	{rdelim});
{rdelim}

function {$bannerCounter}(bannerSid) {ldelim}
	$.ajax({ldelim}
		url: '{page_path module='banners' function='banner_click_counter'}',
		data: {ldelim}
			banner_sid: bannerSid
		{rdelim},
		cache: false,
		async: false
	{rdelim});
{rdelim}


$(document).ready(function() {
	{$bannerRotator}(0);
	setTimeout("{$bannerRotator}({$banner.content_data.display_order})", {$banner.content_data.delay} * 1000);
});

</script>
