{if $listing.user_sid.value != 0}
	<div class="users-box-item sweet-gray-bg">
		<div class="users-box-item-head">
			<div class="row">
				<div class="col-xs-5">
					<a class="wb" href="{page_path id='users'}{$listing.user.sid}">
						{if $listing.user.ProfilePicture.ProfilePicture.name}
							<img class="img-responsive center-block users-box-item-avatar" src="{$listing.user.ProfilePicture.ProfilePicture.url}" alt="[[Profile Picture:raw]]" />
						{else}
							<img src="{url file='main^user_big.png'}" alt="[[No photos:raw]]" class="img-responsive users-box-item-avatar"/>
						{/if}
					</a>
				</div>
				<div class="col-xs-7">
					<ul class="users-box-item-info-list">
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
						<li class="email-string">
							{if $listing.user.DisplayEmail.isTrue}
								<span title="Send Email">{mailto address=$listing.user.email encode="javascript"}</span>
							{else}
								&nbsp;
							{/if}
						</li>
					</ul>
				</div>
				<div class="col-xs-12">
					<div class="users-box-item-connection-button-block row">
						<div class="col-xs-7">
							<a class="default-button wb" title="Contact Seller" onclick='return openDialogWindow("[[Contact seller]]", this.href, 560)' href="{page_path id='contact_seller'}?listing_id={$listing.id}">
								Contact Seller
							</a>
						</div>

						{if !$listing.user.PhoneNumber.isEmpty}
							{*<div class="col-sm-2">*}
								{*<span>or</span>*}
							{*</div>*}
							<div class="col-xs-5">
								<a class="default-button wb" href="tel:{$listing.user.PhoneNumber}" title="Call to {$listing.user.PhoneNumber}">
									Call
								</a>
							</div>

						{/if}
					</div>
				</div>
			</div>
		</div>
	</div>
	{include file="miscellaneous^dialog_window.tpl"}

{else}
	<div>[[This listing was posted by the administrator]]</div>
	<div class="listingOwnerControls">
		<a href="{page_path id='contact'}">[[Contact seller]]</a><br />
	</div>
{/if}


