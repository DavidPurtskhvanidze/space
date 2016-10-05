{assign var="bannerLinkId" value="bannerLink`$banner.uq_id`"}
{assign var="bannerImgId" value="bannerImg`$banner.uq_id`"}
{assign var="bannerRotator" value="bannerRotator`$banner.uq_id`"}
{assign var="bannerCounter" value="bannerCounter`$banner.uq_id`"}

<div>
	<a id="{$bannerLinkId}" onclick='javascript:window.open(this.href, "_blank"); return false;' href="{$banner.content_data.url}" style="border: none">
		<img id="{$bannerImgId}" class="img-responsive" src="{$GLOBALS.site_url}/{$banner.content_data.media_file}" alt="{$banner.content_data.caption}" />
	</a>
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
			$('#{$bannerImgId}').attr("src", '{$GLOBALS.site_url}/' + data.content_data.media_file);
			$('#{$bannerImgId}').attr("alt", data.content_data.caption);

			$('#{$bannerLinkId}').attr("href", data.content_data.url);

			$('#{$bannerLinkId}').unbind('click');
			$('#{$bannerLinkId}').bind('click', function() {ldelim}
				var currDocCursor = document.body.style.cursor;
				var currObjCursor = $('#{$bannerImgId}').css('cursor');

				document.body.style.cursor = 'wait';
				$('#{$bannerImgId}').css('cursor', 'wait');

				{$bannerCounter}(data.content_data.sid);

			    document.body.style.cursor = currDocCursor;
			    $('#{$bannerImgId}').css('cursor', currObjCursor);

				return true;
			{rdelim});

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


$(document).ready(function() {ldelim}
	$('#{$bannerLinkId}').bind('click', function() {ldelim}
		{$bannerCounter}({$banner.content_data.sid});

		return true;
	{rdelim});

	setTimeout("{$bannerRotator}({$banner.content_data.display_order})", {$banner.content_data.delay} * 1000);
{rdelim});

</script>
