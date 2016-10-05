<div class="listingDetails">
	<div class="sellerInfo">
		{if $listing.user_sid.value != 0}
			<h1>[[Seller Info]]</h1>
			<span class="fieldValue fieldValueFirstNameLastName">{$listing.user.FirstName} {$listing.user.LastName}</span>
			<span class="fieldValue fieldValuePhoneNumber">{$listing.user.PhoneNumber}</span>
			{if $listing.user.DisplayEmail eq 1 }
				<span class="fieldValue fieldValueEmail">{mailto address=$listing.user.email encode="javascript"}</span>
			{/if}
			{if $listing.user.ProfilePicture.isNotEmpty}
				<span class="fieldValue fieldValueProfilePicture"><img src="{$listing.user.ProfilePicture.file_url}" alt="" /></span>
			{/if}
			<span class="fieldValue fieldValueAllAdsBySeller">
				<a href="{page_path id='search_results'}?action=search&amp;username[equal]={$listing.user.username}">[[All ads by this seller]]</a>
			</span>
		{else}
			[[This listing was posted by the administrator]]
		{/if}
	</div>
	{include file="classifieds^category_templates/display/subpages_links.tpl" currentPageId="seller-info" listing=$listing}
	{include file="classifieds^category_templates/display/search_results_controls.tpl"}
</div>
