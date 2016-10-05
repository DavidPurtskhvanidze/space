<div class="editListing listingForm form">

    <div class="nav-tabs">
        <div class="row">
            <div class="col-md-6">
                <h4 class="addListingHeader  text-uppercase pull-left"><strong>[[Edit Listing]]</strong></h4>
            </div>
            <div class="col-md-6">
                <div class="category pull-right">
                    [[FormFieldCaptions!Category]]:
                    {foreach from=$ancestors item =ancestor name="ancestors_cycle"}
                        [[$ancestor.caption]]/
                    {/foreach}
                </div>
            </div>
        </div>
    </div>


	{editListingForm formTemplate=$inputFormTemplate id="listingInputForm"}

	{require component="jquery" file="jquery.js"}
	{require component="jquery-maxlength" file="jquery.maxlength.js"}
	{require component="js" file="script.maxlength.js"}
</div>
