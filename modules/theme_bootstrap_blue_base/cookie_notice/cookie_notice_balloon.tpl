{strip}
<div class="cookieNoticeBalloon">
	<div class="cookieNoticeBalloonBody row">
		<div class="col-sm-8">
			[[We are using cookies to provide the best user experience for you on our website.]]&nbsp;<a href="{$GLOBALS.site_url}/cookie-policy/">[[Learn more]]</a>
		</div>
		<div class="col-sm-4">
			<div class="pull-right"><input class="btn h5 btn-long btn-orange" type="button" value="[[I Agree:Raw]]"></div>
		</div>
	</div>
</div>
{/strip}

{require component="jquery" file="jquery.js"}
{require component="jquery-cookie" file="jquery.cookie.js"}
<script type="text/javascript">
	$(document).ready(function() {
		if (!$.cookie("cookies_accepted"))
		{
			$(".cookieNoticeBalloon").show();
		}
		
		$(".cookieNoticeBalloon .btn").click(function() {
			$.cookie("cookies_accepted", true, { expires: 50 * 365, path: '{$urlPath}' });
			$(".cookieNoticeBalloon").hide();
			return false;
		});
	});
</script>
