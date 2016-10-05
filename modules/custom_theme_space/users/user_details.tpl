<div class="users-box-item users-box-item-full">

    {if $user.DealershipName.isNotEmpty}
        {assign var=dealership_name value=$user.DealershipName}
        <h1 class="title">[[$dealership_name]] Profile</h1>
    {else}
        <h1 class="title">[[Profile]]</h1>
    {/if}
    {display_success_messages}

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="row">
                <div class="col-sm-4">
                    <div class="users-box-item-photo">
                        {if $user.ProfilePicture.exists && $user.ProfilePicture.ProfilePicture.name}
                            <img class="img-responsive center-block" src="{$user.ProfilePicture.ProfilePicture.url}" alt="[[Profile Picture:raw]]" />
                        {else}
                            <img src="{url file='main^user_big.png'}" alt="[[No photos:raw]]" class="noImageAvailable img-responsive center-block" />
                        {/if}
                        {if $user.Logo.file_url != '/Logos/Logo/missing.png'}
                            <img class="users-box-item-logo img-responsive" src="{$user.Logo.file_url}" alt="{if $user.DealershipName.isNotEmpty}{$user.DealershipName}{/if}"/>
                        {/if}
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="users-box-item-head">
                        <div class="row">
                            <div class="col-xs-8">
                                <div class="users-box-item-head-user-name">
                                    {$user.DealershipName}
                                </div>
                                <div class="users-box-item-head-user-full-name">
                                    {$user.FirstName} {$user.LastName}
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <a class="users-box-item-head-control" href="{page_path id='users_listings'}{$user.id}/{$UriFilePart}">
                                    <span>View Listings</span><span><img src="{url file="main^img/list-icn.png"}" alt=""></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="users-box-item-footer">
                        <div class="row">
                            <div class="col-xs-7">
                                <address class="">
                                    {$user.Address} <br> {$user.City} {$user.State}
                                </address>
                                <div><b>{$user.PhoneNumber}</b></div>
                                {if $user.DisplayEmail.isTrue}
                                    <div title="Send Email">{mailto address=$user.email encode="javascript"}</div>
                                {/if}
                                <div title="{$user.DealershipWebsite}"><a href="{$user.DealershipWebsite}">{$user.DealershipName} [[Website]]</a></div>
                            </div>
                            <div class="col-xs-5">
                                <a class="users-box-item-footer-contact-seller-btn default-button wb text-center" title="Contact Seller" onclick='return openDialogWindow("[[Contact Seller]]", this.href, 560)' href="{page_path id='users_contact'}{$user.id}/{$UriFilePart}">
                                    [[Send E-Mail]]
                                </a>
                                {if $user.PhoneNumber.isNotEmpty}
                                    <a class="users-box-item-footer-call-seller-btn default-button wb text-center" href="tel:{$user.PhoneNumber}" title="Call to {$user.PhoneNumber}">
                                        [[Call]]
                                    </a>
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {if !$user.Logo.isEmpty}
        <div class="dealer-logo">
            <img src="{$user.Logo.file_url}" alt="{if $user.DealershipName.isNotEmpty}{$user.DealershipName}{/if}"/>
        </div>
    {/if}
</div>
<div class="row">
    {module name="facebook_comments" function="display_comments" url="{page_url id='users'}"|cat:$user.id}
</div>

{include file="miscellaneous^dialog_window.tpl"}
