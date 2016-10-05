<div class="viewUserProfilePage">
    <h1>[[Profile]]</h1>

	{display_success_messages}

	<div class="row">
		<div class="col-md-2">
			{if $user.ProfilePicture.exists && $user.ProfilePicture.ProfilePicture.name}
				<img src="{$user.ProfilePicture.ProfilePicture.url}" alt="[[Profile Picture:raw]]" />
			{else}
				<img src="{url file='main^user_big.png'}" alt="[[No photos:raw]]" class="noImageAvailable" />
			{/if}
		</div>
		<div class="col-md-10">

			<ul class="list-unstyled">
				<li>
					<span class="fieldValue fieldValueFullName">{if !$user.FirstName.isEmpty}{$user.FirstName} {/if}{if !$user.LastName.isEmpty}{$user.LastName}{/if}</span>
                    <a onclick='return openDialogWindow("[[Contact User]]", this.href, 560)' href="{page_path id='users_contact'}{$user.id}/{$user.FirstName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.LastName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}.html">[[Contact User]]</a>
				</li>
				<li>
					<span class="fieldValue fieldValueLocation">{if !$user.Address.isEmpty}{$user.Address}{/if}&nbsp;{if !$user.City.isEmpty}{$user.City}{/if}&nbsp;{if !$user.State.isEmpty}{$user.State}{/if}{if $user.ZipCode.isNotEmpty}, {$user.ZipCode}{/if}</span>
				</li>
				{if $user.DisplayEmail.isTrue}
					<li>
						<span class="fieldValue fieldValueEmail">{mailto address=$user.email encode="javascript"}</span>
					</li>
				{/if}
				<li>
					<span class="fieldCaption fieldCaptionPhoneNumber">[[Phone]]:</span>
					<span class="fieldValue fieldValuePhoneNumber">{if !$user.PhoneNumber.isEmpty}{$user.PhoneNumber}{else}&nbsp;{/if}</span>
				</li>
			</ul>

		</div>
		{include file="miscellaneous^dialog_window.tpl"}
	</div>
	<div class="row">
		{module name="facebook_comments" function="display_comments" url="{page_url id='users'}"|cat:$user.id}
	</div>
</div>
