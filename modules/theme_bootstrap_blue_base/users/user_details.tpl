<div class="viewUserProfilePage">
    <div class="container">
        {if $user.DealershipName.isNotEmpty}
            {assign var=agency_name value=$user.DealershipName}
            <h1 class="page-title">[[$agency_name]]</h1>
        {else}
            <h1 class="page-title">[[Profile]]</h1>
        {/if}

        {display_success_messages}
    </div>
    <div class="search-result-container">
        {strip}
            <div class="container">
                <div class="seller-info">
                    <div class="row">
                        <div class="col-md-2 col-sm-4">
                            <div class="image center">
                                {if $user.ProfilePicture.ProfilePicture.name}
                                    <img src="{$user.ProfilePicture.ProfilePicture.url}" alt="[[Profile Picture:raw]]" />
                                {else}
                                    <img src="{url file='main^user_big.png'}" alt="[[No photos:raw]]" class="noImageAvailable" />
                                {/if}
                            </div>
                        </div>
                        <div class="col-md-10 col-sm-8">
                            <div class="row">
                                <div class="col-xs-12 col-sm-8 vcenter">
                                    <h3 class="h4">{$agency_name}</h3>
                                    <span class="fieldValue fieldValueFullName grey-text">{if !$user.FirstName.isEmpty}{$user.FirstName} {/if}{if !$user.LastName.isEmpty}{$user.LastName}{/if}</span>
                                </div>
                                <div class="col-sm-4 hidden-xs text-right vcenter seller-control-link">
                                    <a href="{page_path id='users_listings'}{$user.id}/{$user.DealershipName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.FirstName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.LastName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}.html"><span>[[Listings]]</span>&nbsp;<i class="fa fa-th-list vcenter"></i></a>
                                </div>
                            </div>

                            <hr/>

                            <div class="row">
                                <div class="col-xs-12 col-sm-8 vcenter">
                                    <i>
                                        <span class="fieldValue fieldValueLocation">{if !$user.Address.isEmpty}{$user.Address}{/if}&nbsp;{if !$user.City.isEmpty}{$user.City}{/if}&nbsp;{if !$user.State.isEmpty}{$user.State}{/if}{if $user.ZipCode.isNotEmpty}, {$user.ZipCode}{/if}</span>
                                    </i>
                                    {if !$user.DealershipWebsite.isEmpty}
                                        <div class="AgencyWebsite">
                                            <a  href="{$user.DealershipWebsite}">{$user.DealershipWebsite}</a>
                                        </div>
                                    {/if}
                                    {if $user.DisplayEmail.isTrue}
                                        <div class="Email">
                                            <span class="fieldValue fieldValueEmail">{mailto address=$user.email encode="javascript"}</span>
                                        </div>
                                    {/if}

                                </div>
                                <div class="col-xs-12 col-sm-4 vcenter">
                                    <div class="row">
                                        <div class="col-xs-12 visible-xs">
                                            <br/>
                                            {if !$user.PhoneNumber.isEmpty}
                                                <span class="h4">{$user.PhoneNumber}</span>
                                            {/if}
                                        </div>

                                        {if $user.Logo.isNotEmpty && $user.Logo.Logo.name}
                                            <div class="col-xs-12">
                                                <div class="hidden-xs text-right">
                                                    <img src="{$user.Logo.Logo.url}" alt="{if $user.DealershipName.isNotEmpty}{$user.DealershipName}{/if}"/>
                                                </div>
                                                <div class="visible-xs">
                                                    <img src="{$user.Logo.Logo.url}" alt="{if $user.DealershipName.isNotEmpty}{$user.DealershipName}{/if}"/>
                                                </div>
                                            </div>
                                        {/if}

                                    </div>

                                </div>
                            </div>

                            <hr/>
                            <div class="row">
                                <div class="seller-control-link text-center visible-xs">
                                    <a href="{page_path id='users_listings'}{$user.id}/{$user.DealershipName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.FirstName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.LastName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}.html"><i class="fa fa-th-list"></i> [[Listings]]</a>
                                </div>

                                <div class="col-sm-6 col-xs-12 vcenter hidden-xs">
                                    {if !$user.PhoneNumber.isEmpty}
                                        <span class="h4">{$user.PhoneNumber}</span>
                                    {/if}
                                </div>
                                <div class="col-sm-6 col-xs-12 vcenter contact-seller">
                                    {if !$user.DealershipName.isEmpty}
                                        <a class="btn btn-orange h5" onclick='return openDialogWindow("[[Contact Agent]]", this.href, 560)' href="{page_path id='users_contact'}{$user.id}/{$user.DealershipName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.FirstName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.LastName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}.html">[[Contact Agent]]</a>
                                    {else}
                                        <a class="btn btn-orange h5" onclick='return openDialogWindow("[[Contact User]]", this.href, 560)' href="{page_path id='users_contact'}{$user.id}/{$user.FirstName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.LastName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}.html">[[Contact User]]</a>
                                    {/if}
                                </div>
                            </div>
                        </div>
                        {include file="miscellaneous^dialog_window.tpl"}
                    </div>
                </div>
            </div>
        {/strip}
        <div class="container">
            {module name="facebook_comments" function="display_comments" url="{page_url id='users'}"|cat:$user.id}
        </div>
    </div>
</div>
