{if $listing.user_sid.value != 0}
    <div class="seller-info">
        <div class="h4 text-center">[[Seller]]</div>
        <div class="row">
            <div class="col-xs-8">
                <div class="space-20"></div>
                <a class="author" href="{page_path id='users'}{$listing.user.sid}">{$listing.user.FirstName} {$listing.user.LastName}</a><br/>
                <br/>
                {*if $listing.user.DisplayEmail.isTrue*}
                    <div class="seller-control-link">
                        <a  onclick='return openDialogWindow("[[Contact seller]]", this.href, 560)' href="{page_path id='contact_seller'}?listing_id={$listing.id}"><i class="fa fa-envelope-o"></i> [[Contact seller]]</a>
                    </div>
                {*/if*}
                <div class="seller-control-link">
                    <a href="{page_path id='search_results'}?action=search&amp;username[equal]={$listing.user.username}"><i class="fa fa-briefcase"></i> [[All ads by this seller]]</a>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="image center">
                    {if $listing.user.ProfilePicture.ProfilePicture.name}
                        <img src="{$listing.user.ProfilePicture.ProfilePicture.url}" alt="[[Profile Picture:raw]]" class="img-responsive" />
                    {else}
                        <img src="{url file='main^user_big.png'}" alt="[[No photos:raw]]" class="img-responsive"/>
                    {/if}
                </div>
            </div>
        </div>
        <hr/>
        {strip}
        <div class="row">
            <div class="phone">
                <div class="col-xs-8 h4 vcenter">{$listing.user.PhoneNumber}</div>
                <div class="col-xs-4 text-center vcenter">
                    <a href="tel:{$listing.user.PhoneNumber}">
                        <i class="fa fa-phone circle fa-2x"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- <div class="col-xs-8 vcenter"> -->
                <!-- <a class="contact-seller" onclick='return openDialogWindow("[[Contact seller]]", this.href, 560)' href="{page_path id='contact_seller'}?listing_id={$listing.id}">[[Contact seller]]</a> -->
            <!-- </div> -->
            <div class="col-xs-12 vcenter text-center dealer-logo">
                {if $listing.user.Logo.exists && $listing.user.Logo.isNotEmpty}
                    {if !$listing.user.DealershipWebsite.isEmpty}
                        <a class="dealer-logo text-center" href="{$listing.user.DealershipWebsite.value}">
                    {/if}
                    <img src="{$listing.user.Logo.file_url}" alt="{$listing.user.DealershipName.value}" title="{$listing.user.DealershipName.value}"/>
                    {if !$listing.user.DealershipWebsite.isEmpty}
                        </a>
                    {/if}
                {/if}
            </div>
        </div>
            <div class="space-20"></div>
        {/strip}
    </div>
	{include file="miscellaneous^dialog_window.tpl"}

{else}
	<div>[[This listing was posted by the administrator]]</div>
	<div class="listingOwnerControls">
		<a href="{page_path id='contact'}">[[Contact seller]]</a><br />
	</div>
{/if}
