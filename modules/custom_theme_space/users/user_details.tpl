{if $user.DealershipName.isNotEmpty}
    {assign var=dealership_name value=$user.DealershipName}
    <h1 class="title">[[$dealership_name]] Profile</h1>
{else}
    <h1 class="title">[[Profile]]</h1>
{/if}
{display_success_messages}

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="universal-user-box m-bottom-30 sweet-gray-bg">
            <div class="universal-user-box-head">
                {if $user.ProfilePicture.file_url && $user.ProfilePicture.ProfilePicture.url != '/ProfilePictures/ProfilePicture/missing.png'}
                    <img class="img-responsive center-block universal-user-box-avatar" src="{$user.ProfilePicture.ProfilePicture.url}" alt="[[Profile Picture:raw]]"/>
                {else}
                    <img src="{url file='main^user_big.png'}" alt="[[No photos:raw]]" class="noImageAvailable img-responsive center-block"/>
                {/if}
                {if !$user.Logo.file_url.isEmpty && $user.Logo.file_url != '/Logos/Logo/missing.png'}
                    {$user.Logo.file_url}
                    <img class="users-box-item-logo  img-responsive" src="{$user.Logo.file_url}" alt="{if $user.DealershipName.isNotEmpty}{$user.DealershipName}{/if}"/>
                {/if}
                <br>
            </div>
            <div class="universal-user-box-body">
                <ul class="list-unstyled">
                    {if !$user.DealershipName.isEmpty}
                        <li>
                            {$user.DealershipName}
                        </li>
                        <li>
                            {$user.FirstName} {$user.LastName}
                        </li>
                    {else}
                        <li>
                            {$user.FirstName}
                        </li>
                        <li>
                            {$user.LastName}
                        </li>
                    {/if}

                    <hr>

                    {if !$user.DealershipWebsite.isEmpty}
                        <li>
                            Website: {$user.DealershipWebsite}
                        </li>
                    {/if}

                    {if !$user.PhoneNumber.isEmpty}
                        <li>Phone: {$user.PhoneNumber}</li>
                    {/if}

                    {if $user.DisplayEmail.isTrue}
                        <li class="email-string">
                            <span title="Send Email">{mailto address=$user.email encode="javascript"}</span>
                        </li>
                    {/if}

                    {if !$user.Address.isEmpty}
                        <hr>
                        <li>
                            <address>
                                {$user.Address} <br> {$user.City} {$user.State}
                            </address>
                        </li>
                    {/if}
                </ul>
            </div>
            <div class="universal-user-box-footer">
                <div class="row">
                    <div class="{if $user.PhoneNumber != ''}col-sm-3 col-xs-6{else}col-sm-6 col-xs-12{/if}">
                        <a class="default-button wb" title="Contact Seller" onclick='return openDialogWindow("[[Contact Seller]]", this.href, 560)' href="{page_path id='users_contact'}{$user.id}/{$UriFilePart}">
                            <span class="glyphicon glyphicon-envelope"></span>
                        </a>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        {if $user.PhoneNumber != ''}
                            <a class="default-button wb" href="tel:{$user.PhoneNumber}" title="Call to {$user.PhoneNumber}">
                                <span class="glyphicon glyphicon-earphone"></span>
                            </a>
                        {/if}
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <a class="default-button wb" href="{page_path id='users_listings'}{$user.id}/{$UriFilePart}">
                            [[View Listings]]
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    {module name="facebook_comments" function="display_comments" url="{page_url id='users'}"|cat:$user.id}
</div>

{include file="miscellaneous^dialog_window.tpl"}
