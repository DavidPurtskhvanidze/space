<div>
	<h1 class="choosePackageHeader">[[Select a Package]]</h1>
	<br/>

	<form method="get" action="">
        {CSRF_token}
		<ul class="list-unstyled">
		{foreach from=$listing_packages item="listing_package" name="listing_packages"}
			<li>
				{$radioId="package"|cat:$listing_package.sid}
				<input type="radio" value="{$listing_package.sid}" name="package_sid" id="{$radioId}"/>
				<label for="{$radioId}">[[$listing_package.name]]</label>

				<div class="description">
					[[$listing_package.description]]
				</div>
			</li>
		{/foreach}
		</ul>
		<div>
		{foreach from=$predefinedRequestData item="value" key="name"}
			<input type="hidden" name="{$name}" value="{$value}"/>
		{/foreach}
			<input type="hidden" name="package_chosen" value="1"/>
			<input type="submit" value="[[Next >>:raw]]" class="btn btn-default"/>
		</div>
	</form>
</div>
