<div class="cookieNoticeBalloon">
	<div class="cookieNoticeBalloonBody">
		<p class="noticeText">[[We use cookies!]]<p>
		<input class="acceptCookies" type="button" value="[[I Agree:Raw]]">
		<span class="noticeLink"><a href="{$GLOBALS.site_url}/cookie-policy/">[[Learn more]]</a></span>
	</div>
</div>

{require component="jquery" file="jquery.js"}
{require component="jquery-cookie" file="jquery.cookie.js"}
<script type="text/javascript">
	$(document).ready(function() {
		if (!$.cookie("cookies_accepted"))
		{
			$(".cookieNoticeBalloon").show();
		}
		
		$(".cookieNoticeBalloon .acceptCookies").click(function() {
			$.cookie("cookies_accepted", true, { expires: 50 * 365, path: '{$urlPath}' });
			$(".cookieNoticeBalloon").hide();
			return false;
		});
	});
</script>
