<div class="navbar navbar-default navbar-demo-patch">
	<div class="container-fluid">

		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#demo-patch-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<div class="navbar-brand">
				<form method="get" action="{$GLOBALS.site_url}">
					[[Theme]]
					<select name="theme" onchange="this.form.submit()">
						{foreach from=$themes item=theme}
							<option value="{$theme}" {if $theme == $GLOBALS.current_theme} selected="selected"{/if}>{$theme}</option>
						{/foreach}
					</select>
				</form>
			</div>
		</div>

		<div class="collapse navbar-collapse" id="demo-patch-collapse">
			<ul class="nav navbar-nav navbar-right">
				<li><a href="{$buyNowLink}" rel="nofollow" class="buyNow">[[Buy Now]]</a></li>
				<li>
					<!-- BEGIN FUSION TAG CODE -->
					<a href="javascript: void(0);" rel="nofollow" onclick="window.open('http://customers.worksforweb.com/visitor/index.php?/Default/LiveChat/Chat/Request/_sessionID=/_promptType=chat/_proactive=0/_filterDepartmentID=/_randomNumber=9kvb1stkaywl4wj118brhzywdwaqn44o/_fullName=/_email=/', 'livechatwin', 'toolbar=0,location=0,directories=0,status=1,menubar=0,scrollbars=0,resizable=1,width=600,height=680');" class="onlineChat">[[Online Chat]]</a>
					<!-- END FUSION TAG CODE -->
				</li>
				<li><a onclick="window.open(this.href, '_blank'); return false;" href="http://www.worksforweb.com/company/contact-us/pre-sales-inquiry/" rel="nofollow" class="createSalesTicket">[[Create a Sales Ticket]]</a></li>
				<li><a onclick="window.open(this.href, '_blank'); return false;" href="http://www.stellarsurvey.com/s.aspx?u=DB8BFE42-D3E7-4C7C-92CF-B983D818C931" rel="nofollow" class="evaluateThisProduct">[[Evaluate This Product]]</a></li>
				<li><a onclick="window.open(this.href, '_blank'); return false;" href="{$GLOBALS.site_url}/doc/UserManual/" rel="nofollow" class="userManual">[[User Manual]]</a></li>
				{assign var="productName" value=$GLOBALS.settings.product_name}
				<li><a href="{$GLOBALS.mobile_front_end_url}" class="mobileWebsite" rel="nofollow">[[$productName Mobile Website]]</a></li>
				<li><a onclick="window.open(this.href, '_blank'); return false;" href="{$GLOBALS.site_url}/admin/" rel="nofollow" class="adminPanel">[[Admin Panel]]</a></li>
				{if !empty($googlePlayLink)}
					<li><a href="{$googlePlayLink}" rel="nofollow" class="googlePlay">Android App</a></li>
				{/if}
			</ul>
		</div>
	</div>
</div>
