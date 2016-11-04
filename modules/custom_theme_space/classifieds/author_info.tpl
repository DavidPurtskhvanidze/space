{if $listing.user_sid.value != 0}
	<div class="universal-user-box sweet-gray-bg">
		<div class="universal-user-box-head">
			<div class="text-center">
				<a class="center-block wb" href="{page_path id='users'}{$listing.user.sid}">
					{if $listing.user.ProfilePicture.ProfilePicture.name && $listing.user.ProfilePicture.ProfilePicture.url != '/Logos/Logo/missing.png'}
						<img class="img-responsive center-block universal-user-box-avatar" src="{$listing.user.ProfilePicture.ProfilePicture.url}" alt="[[Profile Picture:raw]]" />
					{else}
						<img src="{url file='main^user_big.png'}" alt="[[No photos:raw]]" class="img-responsive users-box-item-avatar"/>
					{/if}
					<span class="full-prof-text hidden-xs">View <br> Full Profile</span>
				</a>
				<a class="visible-xs-block default-button wb" href="{page_path id='users'}{$listing.user.sid}">
					[[View Full Profile]]
				</a>
			</div>
		</div>
		<div class="universal-user-box-body">
			<ul class="list-unstyled">
				<li>
					{if !$listing.user.DealershipName.isEmpty}
						{$listing.user.DealershipName.value}
					{else}
						{$listing.user.FirstName}
					{/if}
				</li>
				<li>
					{if !$listing.user.DealershipName.isEmpty}{$listing.user.FirstName}{/if}
					{$listing.user.LastName}
				</li>
				<li>
					{$listing.user.PhoneNumber}
				</li>
				{if $listing.user.DisplayEmail.isTrue}
					<li class="email-string">
						<span title="Send Email">{mailto address=$listing.user.email encode="javascript"}</span>
					</li>
				{/if}
			</ul>
		</div>
		<div class="universal-user-box-footer">
			<a class="default-button wb" title="Contact Seller" onclick='return openDialogWindow("[[Contact seller]]", this.href, 560)' href="{page_path id='contact_seller'}?listing_id={$listing.id}">
				Contact Seller
			</a>

			{if !$listing.user.PhoneNumber.isEmpty}
			<a class="default-button wb" href="tel:{$listing.user.PhoneNumber}" title="Call to {$listing.user.PhoneNumber}">
				Call
			</a>
			{/if}
		</div>
	</div>
	{include file="miscellaneous^dialog_window.tpl"}

{else}
	<div>[[This listing was posted by the administrator]]</div>
	<div class="listingOwnerControls">
		<a href="{page_path id='contact'}">[[Contact seller]]</a><br />
	</div>
{/if}


