
<section class="add-listing-block addListingForm">
    <h1 class="title">
        [[Listing Information]]
    </h1>
    {addListingForm formTemplate=$inputFormTemplate id="listingInputForm"}

    {*{require component="jquery" file="jquery.js"}*}
    {*{require component="jquery-maxlength" file="jquery.maxlength.js"}*}
    {*{require component="js" file="script.maxlength.js"}*}
</section>

{*<div class="addListingForm listingForm form">*}
    {*<div class="nav-tabs">*}
        {*<div class="row">*}
            {*<div class="col-md-6">*}
                {*<h4 class="addListingHeader  text-uppercase pull-left">*}
                    {*<strong>[[Listing Information]]</strong>*}
                {*</h4>*}
            {*</div>*}
            {*<div class="col-md-6">*}
                {*<div class="category pull-right"*}
                     {*title="{foreach from=$ancestors item=ancestor name="ancestors_cycle"}[[$ancestor.caption]]/{/foreach}">*}
                    {*[[FormFieldCaptions!Category]]:*}
                    {*{if count($ancestors) > 2}*}
                        {*{foreach from=$ancestors item=ancestor name="ancestors_cycle"}*}
                            {*{if $smarty.foreach.ancestors_cycle.first}[[$ancestor.caption]]/.../{/if}*}
                            {*{if $smarty.foreach.ancestors_cycle.last}[[$ancestor.caption]]{/if}*}
                        {*{/foreach}*}
                    {*{else}*}
                        {*{foreach from=$ancestors item=ancestor name="ancestors_cycle"}*}
                            {*[[$ancestor.caption]]/*}
                        {*{/foreach}*}
                    {*{/if}*}
                    {*<a class="btn btn-primary" href="{page_path id='listing_add'}?listing_package_sid={$listing_package_sid}">[[Change Category]]</a>*}
                {*</div>*}
            {*</div>*}
        {*</div>*}
    {*</div>*}




	{*{addListingForm formTemplate=$inputFormTemplate id="listingInputForm"}*}

	{*{require component="jquery" file="jquery.js"}*}
	{*{require component="jquery-maxlength" file="jquery.maxlength.js"}*}
	{*{require component="js" file="script.maxlength.js"}*}

{*</div>*}
