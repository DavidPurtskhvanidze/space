<div class="listingPackgeChoice">
    <h1 class="listingPackgeChoiceHeader">[[Select a Package]]</h1>

{display_error_messages}
{if !$contract_expired}
    <div class="alert alert-info">
        [[Please keep in mind that the conditions of new Listing Package may reduce the number of listing pictures and prohibit video uploads.]]
    </div>
    <form method="post" action="">
		{foreach from=$listing_packages item="listing_package" name="listing_packages"}
            <div class="custom-form-control">
				<input type="radio" value="{$listing_package.sid}" id="listing_package_{$listing_package.sid}" name="listing_package_sid"/>
                <label class="radio" for="listing_package_{$listing_package.sid}">
                    [[PhrasesInTemplates!{$listing_package.name}]]
                    <br>
                </label>
                <span class="listingPackageDescription">[[PhrasesInTemplates!{$listing_package.description}]]<span>
            </div>
            <div class="space-20"></div>
		{/foreach}
        <p>
            <input type="submit" value="[[Next >>:raw]]" class="h6 btn btn-orange"/>
            <input type="hidden" name="listing_sid" value="{$listing_sid}"/>
            <input type="hidden" name="return_uri" value="{$return_uri}"/>
            <input type="hidden" name="action" value="package_selected"/>
            {CSRF_token}
        </p>
    </form>
	{else}
    <div class="hint">
        [[Your subscription has expired. Please]]
        <a href="{page_path id='user_subscription'}" class="respondInMainWindow">[[subscribe]]</a>
        [[to a Membership Plan to enable Listing Reactivation.]]
    </div>
{/if}
</div>
