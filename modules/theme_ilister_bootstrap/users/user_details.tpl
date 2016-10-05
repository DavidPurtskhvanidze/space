<div class="viewUserProfilePage">

    {if $user.DealershipName.isNotEmpty}
        {assign var=dealership_name value=$user.DealershipName}
        <h1>[[$dealership_name]]</h1>
    {else}
        <h1>[[Profile]]</h1>
    {/if}

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
            <div class="row">
                <div class="col-md-6">
                    <h3>{$dealership_name}</h3>

                    <ul class="list-unstyled">
                        <li>
                            {if !$user.DealershipName.isEmpty}
                                <span class="fieldCaption fieldCaptionFullName">[[Contact Person]]:</span>
                            {/if}
                            <span class="fieldValue fieldValueFullName">{if !$user.FirstName.isEmpty}{$user.FirstName} {/if}{if !$user.LastName.isEmpty}{$user.LastName}{/if}</span>
                            |
                            {if !$user.DealershipName.isEmpty}
                                <a onclick='return openDialogWindow("[[Contact Dealer]]", this.href, 560)' href="{page_path id='users_contact'}{$user.id}/{$user.DealershipName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.FirstName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.LastName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}.html">[[Contact Dealer]]</a>
                            {else}
                                <a onclick='return openDialogWindow("[[Contact User]]", this.href, 560)' href="{page_path id='users_contact'}{$user.id}/{$user.FirstName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.LastName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}.html">[[Contact User]]</a>
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
                            <span class="viewAllListings"><a href="{page_path id='users_listings'}{$user.id}/{$user.DealershipName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.FirstName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.LastName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}.html">[[View All Listings]]</a></span>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    {if $user.Logo.exists && $user.Logo.isNotEmpty}
                        <div class="dealer-logo">
                            <img src="{$user.Logo.file_url}" alt="{if $user.DealershipName.isNotEmpty}{$user.DealershipName}{/if}"/>
                        </div>
                    {/if}
                </div>
            </div>
		</div>
		{include file="miscellaneous^dialog_window.tpl"}
	</div>
    <div class="row">
        {module name="facebook_comments" function="display_comments" url="{page_url id='users'}"|cat:$user.id}
    </div>
</div>
