<div class="addListingForm listingForm form">
	<h1 class="addListingHeader">[[Enter Your Ad Information]]</h1>

	<div class="category">
		[[FormFieldCaptions!Category]]:
		{foreach from=$ancestors item =ancestor name="ancestors_cycle"}
			[[$ancestor.caption]]/
		{/foreach}
		(<a href="{page_path id='listing_add'}?listing_package_sid={$listing_package_sid}">[[Change Category]]</a>)
	</div>

	{addListingForm formTemplate=$inputFormTemplate id="listingInputForm"}

	{require component="jquery" file="jquery.js"}
	{require component="jquery-maxlength" file="jquery.maxlength.js"}
	{require component="js" file="script.maxlength.js"}

</div>
