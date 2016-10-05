<div class="breadcrumbs">
  <ul class="breadcrumb">
	  <li><a href="{page_path id='add_listing'}">[[Add Listing]]</a></li>
	  <li><a href="{page_path id='add_listing'}?category_id={$category_info.id}">[[$category_info.name]]</a></li>
  </ul>
</div>

<div class="page-content">
  <div class="page-header">
    <h1>[[Add Listing]]</h1>
  </div>
  <div class="row">
    {display_success_messages}

    {capture assign="activeListingUrl"}{page_path module='classifieds' function='listing_actions'}?action=Activate&listings[{$listingId}]=1{/capture}
    <p class="bigger-120">[[Click <a class="activateListingLink" href="$activeListingUrl">here</a> to activate this listing.]]</p>
  </div>
</div>
