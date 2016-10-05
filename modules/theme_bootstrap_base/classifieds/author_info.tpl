{if $listing.user_sid.value != 0}

	<div class="row">
		<div class="col-sm-5">
			{if $listing.user.ProfilePicture.ProfilePicture.name}
				<img src="{$listing.user.ProfilePicture.ProfilePicture.url}" alt="[[Profile Picture:raw]]" class="img-responsive" />
            {else}
                <img src="{url file='main^user_big.png'}" alt="[[No photos:raw]]" class="img-responsive"/>
            {/if}
		</div>
		<div class="col-sm-7">
			<ul class="list-unstyled">
				<li>
					<h4>{$listing.user.FirstName} {$listing.user.LastName}</h4>
				</li>
				<li>
					<span class="glyphicon glyphicon-earphone"></span> {$listing.user.PhoneNumber}
				</li>
				{if $listing.user.DisplayEmail.isTrue}
					<li>
						<span class="fieldValue fieldValueEmail">{mailto address=$listing.user.email encode="javascript" text="Send E-mail"}</span>
					</li>
				{/if}
				<li>
					<a onclick='return openDialogWindow("[[Contact seller]]", this.href, 560)' href="{page_path id='contact_seller'}?listing_id={$listing.id}">[[Contact seller]]</a>
				</li>
				<li>
					<a href="{page_path id='search_results'}?action=search&amp;username[equal]={$listing.user.username}">[[All ads by this seller]]</a>
				</li>
				{if $listing.user.Logo.exists && $listing.user.Logo.isNotEmpty}
				<li>
					{if !$listing.user.DealershipWebsite.isEmpty}
						<a class="dealer-logo" href="{$listing.user.DealershipWebsite.value}">
				    {/if}
							<img src="{$listing.user.Logo.file_url}" alt="{$listing.user.DealershipName.value}" title="{$listing.user.DealershipName.value}"/>
					{if !$listing.user.DealershipWebsite.isEmpty}
						</a>
					{/if}
				</li>
				{/if}
			</ul>
		</div>
	</div>
	{include file="miscellaneous^dialog_window.tpl"}

{else}
	<div>[[This listing was posted by the administrator]]</div>
	<div class="listingOwnerControls">
		<a href="{page_path id='contact'}">[[Contact seller]]</a><br />
	</div>
{/if}
