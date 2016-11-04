<h1 class="title">
    [[Edit Listing]]
</h1>
<div class="editListing listingForm form">

    {*<div class="nav-tabs">*}
    {*[[FormFieldCaptions!Category]]:*}
    {*{foreach from=$ancestors item =ancestor name="ancestors_cycle"}*}
    {*[[$ancestor.caption]]/*}
    {*{/foreach}*}
    {*</div>*}


    {editListingForm formTemplate=$inputFormTemplate id="listingInputForm"}

    {require component="jquery" file="jquery.js"}
    {require component="jquery-maxlength" file="jquery.maxlength.js"}
    {require component="js" file="script.maxlength.js"}
</div>