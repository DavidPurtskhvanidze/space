<div class="listingPackageChoice">
    <h1 class="listingPackgeChoiceHeader">[[Select a Package]]</h1>
    <form method="post" action="">
     {CSRF_token}
	{foreach from=$listing_packages item="listing_package" name="listing_packages"}
        <div class="packageOption">
	        <label>
		        <input type="radio" value="{$listing_package.sid}" name="listing_package_sid"/>
		        <span class="packageName">[[$listing_package.name]]</span> <br/>
		        <span class="packageDescription">[[$listing_package.description]]</span>
	        </label>
        </div>
	{/foreach}
        <div>
            <input type="submit" value="[[Next >>:raw]]" class="button"/>
            <input type="hidden" name="category_id" value="{$REQUEST.category_id|default:''}"/>
        </div>
    </form>
</div>
