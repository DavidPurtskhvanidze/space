<div class="sellerInfo">
	{if $listing.user_sid.value != 0}
		<ul class="userDetails">
			{if $listing.user.ProfilePicture.isExist && $listing.user.ProfilePicture.isNotEmpty}
				<li class="fieldValue ProfilePicture"><img src="{$listing.user.ProfilePicture.file_url}" alt="[[Profile Picture:raw]]" /></li>
			{/if}
			<li class="fieldValue FirstName">{$listing.user.FirstName}</li>
			<li class="fieldValue LastName">{$listing.user.LastName}</li>
			<li class="fieldValue PhoneNumber">{$listing.user.PhoneNumber}</li>
			{if !$listing.user.Address.isExist}
				<li class="fieldValue Address">{$listing.user.Address}</li>
			{/if}
		</ul>
		<ul class="userActions">
			{if !$listing.user.DealershipWebsite.isEmpty}
				<li class="fieldValue fieldValueDealershipWebsite">
					<a href="{$listing.user.DealershipWebsite}">
						{$listing.user.DealershipName} [[Website]]
					</a>
				</li>
			{/if}
			{if $listing.user.DisplayEmail.isTrue}
				<li class="userAction Email">[[Send]] {mailto address=$listing.user.email encode="javascript" text="E-mail"}</li>
			{/if}
			<li class="userAction Contact"><a onclick='return openDialogWindow("[[Contact seller]]", this.href, 560)' href="{page_path id='contact_seller'}?listing_id={$listing.id}">[[Contact seller]]</a></li>
			<li class="userAction userAds"><a href="{page_path id='search_results'}?action=search&amp;username[equal]={$listing.user.username}">[[All ads by this seller]]</a></li>
		</ul>
		{if $listing.user.Logo.exists && $listing.user.Logo.isNotEmpty}
			<a class="dealer-logo" href="{$listing.user.DealershipWebsite.value}">
				<img src="{$listing.user.Logo.file_url}" alt="{$listing.user.DealershipName.value}" title="{$listing.user.DealershipName.value}"/>
			</a>
		{/if}
		{include file="miscellaneous^dialog_window.tpl"}
	{else}
		<div>[[This listing was posted by the administrator]]</div>
		<div class="listingOwnerControls">
			<a href="{page_path id='contact'}">[[Contact seller]]</a>
		</div>
	{/if}
</div>
