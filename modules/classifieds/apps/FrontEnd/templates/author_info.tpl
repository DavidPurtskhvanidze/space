{if $listing.user_sid.value != 0}
	<b>{$listing.user.FirstName} {$listing.user.LastName}</b><br />
	{$listing.user.PhoneNumber}<br />
	{if $listing.user.DisplayEmail.isTrue}
		<span class="fieldValue fieldValueEmail">[[Send]] {mailto address=$listing.user.email encode="javascript" text="E-mail"}</span><br />
	{/if}
	{if !$listing.user.ProfilePicture.isEmpty}<br /><img src="{$listing.user.ProfilePicture.file_url}" alt="[[Profile Picture:raw]]" /><br />{/if}
	<div class="listingOwnerControls">
		<a onclick='return openDialogWindow("[[Contact seller]]", this.href, 560)' href="{page_path id='contact_seller'}?listing_id={$listing.id}">[[Contact seller]]</a><br />
		<a href="{page_path id='search_results'}?action=search&amp;username[equal]={$listing.user.username}">[[All ads by this seller]]</a>
	</div>
	{if $listing.user.Logo.exists && $listing.user.Logo.isNotEmpty}
		<a class="dealer-logo" href="{$listing.user.DealershipWebsite.value}">
			<img src="{$listing.user.Logo.file_url}" alt="{$listing.user.DealershipName.value}" title="{$listing.user.DealershipName.value}"/>
		</a>
	{/if}
	{include file="miscellaneous^dialog_window.tpl"}
{else}
	<div>[[This listing was posted by the administrator]]</div>
	<div class="listingOwnerControls">
		<a href="{page_path id='contact'}">[[Contact seller]]</a><br />
	</div>
{/if}
