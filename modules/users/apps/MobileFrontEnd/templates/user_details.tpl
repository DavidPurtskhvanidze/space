<div class="viewUserProfilePage">
	{if !$user.DealershipName.isEmpty}
		{assign var=dealership_name value=$user.DealershipName}
		<h1>[[$dealership_name Profile]]</h1>
	{else}
		<h1>[[Profile]]</h1>
	{/if}

	<div class="fieldValue fieldValueProfilePicture">
		{if $user.ProfilePicture.exists && $user.ProfilePicture.ProfilePicture.name}
			<img src="{$user.ProfilePicture.ProfilePicture.url}" alt="[[Profile Picture:raw]]" />
		{else}
			<img src="{url file='main^user_big.png'}" alt="[[No photos:raw]]" class="noImageAvailable" />
		{/if}
	</div>
	<div class="details">
		<ul>
			<li>
                {if !$user.DealershipName.isEmpty}
				    <span class="fieldCaption fieldCaptionFullName">[[Contact Person]]:</span>
                {/if}
				<span class="fieldValue fieldValueFullName">{if !$user.FirstName.isEmpty}{$user.FirstName} {/if}{if !$user.LastName.isEmpty}{$user.LastName}{/if}</span>
				|
                {if !$user.DealershipName.isEmpty}
                    <a href="{page_path id='users_contact'}{$user.id}/{$user.DealershipName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.FirstName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.LastName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}.html">[[Contact Agent]]</a>
                {else}
                    <a href="{page_path id='users_contact'}{$user.id}/{$user.FirstName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.LastName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}.html">[[Contact User]]</a>
                {/if}
			</li>
			<li>
				<span class="fieldValue fieldValueLocation">{if !$user.Address.isEmpty}{$user.Address}{/if}&nbsp;{if !$user.City.isEmpty}{$user.City}{/if}&nbsp;{if !$user.State.isEmpty}{$user.State}{/if}{if $user.ZipCode.isNotEmpty}, {$user.ZipCode}{/if}</span>
			</li>
			<li>
				<span class="fieldValue fieldValueDealershipWebsite">{if !$user.DealershipWebsite.isEmpty}<a href="{$user.DealershipWebsite}">{$user.DealershipWebsite}</a>{/if}</span>
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
			<li>
				<span class="viewAllListings">
					<a href="{page_path id='users_listings'}{$user.id}/{$user.DealershipName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.FirstName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.LastName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}.html">[[View All Listings]]</a>
				</span>
			</li>
		</ul>
	</div>
</div>
