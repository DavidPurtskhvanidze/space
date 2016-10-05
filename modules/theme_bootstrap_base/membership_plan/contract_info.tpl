<div class="currentSubscriptionInfo">
	<h1>[[Your Current Subscription]]</h1>
    {$listing_add_url = {page_path id='listing_add'}}
    <div class="message">[[You have been subscribed to the Membership Plan below and now you can <a href="$listing_add_url">add listings</a>.]]</div>
	{assign var="contactFormPageUri" value=$GLOBALS.site_url|cat:"/contact/"}

	<div class="expirationAndAutoExtensionWrapper">
		<div class="expiration">
			<h3>[[Expires]]: {if !empty($contract.expired_date)}{tr type='date'}{$contract.expired_date}{/tr}{else}[[Never]]{/if}</h3>
		</div>
	{if $is_auto_extension_available}
		<div class="autoExtension">
			{if $contract.auto_extend}
				<span class="state">[[Auto-extension: enabled.]]</span>
				<a href="{$GLOBALS.site_url}{$GLOBALS.current_page_uri}?action=change_auto_extend&amp;auto_extend=0">[[Disable auto-extension]]</a>
			{else}
				<span class="state">[[Auto-extension: disabled.]]</span>
				<a href="{$GLOBALS.site_url}{$GLOBALS.current_page_uri}?action=change_auto_extend&amp;auto_extend=1">[[Enable auto-extension]]</a>
			{/if}
			<p class="hint">([[If you enable this option and have enough funds on your credit balance, your subscription will be automatically extended.]])</p>
		</div>
	{/if}
	</div>

	<h3>[[Membership Plan]]: "[[$contract.name]]"</h3>
	<div class="details">
		<ul class="list-unstyled">
			<li><strong>[[Price]]:</strong> {$contract.price}</li>
			<li><strong>[[Type]]:</strong> [[{$contract.type}]]</li>
			<li><strong>[[Max Number of Listings]]:</strong> {$contract.classifieds_listing_amount}</li>
		</ul>
	</div>
    <div class="description plan-description">
        <p>[[$contract.description]] </p>
    </div>
	{include file="packages.tpl" packages=$contract.packages}
</div>
