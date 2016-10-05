{capture assign="UriFilePart"}{$user.DealershipName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.FirstName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.LastName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}.html{/capture}
{strip}
<div class="item seller-info">
    <div class="row">
        <div class="col-sm-4 col-md-12">
            <div class="row">
                <div class="col-md-6 col-xs-6 col-sm-12">
                    <div class="image center avatar">
                        {if $user.ProfilePicture.ProfilePicture.name}
                            <img src="{$user.ProfilePicture.ProfilePicture.url}" alt="[[Profile Picture:raw]]"/>
                        {else}
                            <img src="{url file='main^user_big.png'}" alt="[[No photos:raw]]" class="noImageAvailable"/>
                        {/if}
                    </div>
                </div>
                <div class="col-md-6 col-xs-6 hidden-sm">
                    <h3><a class="h4" href="{page_path id='users'}{$user.id}/{$UriFilePart}">{$user.DealershipName}</a></h3>
                    <span class="fieldValue FullName">{$user.FirstName} {$user.LastName}</span>
                </div>
            </div>
        </div>
        <div class="col-sm-8 col-md-12">
            <div class="row visible-sm">
                <div class="col-sm-7">
                    <h3><a class="h4" href="{page_path id='users'}{$user.id}/{$UriFilePart}">{$user.DealershipName}</a></h3>
                    <span class="fieldValue FullName">{$user.FirstName} {$user.LastName}</span>
                </div>
                <div class="col-sm-5 text-right seller-control-link">
                    <p></p>
                    <p>
                        <a href="{page_path id='users_listings'}{$user.id}/{$UriFilePart}" >
                            [[Listings]]  <i class="fa fa-th-list"></i>
                        </a>
                    </p>
                    <p>
                        <a href="{page_path id='users'}{$user.id}/{$UriFilePart}">
                            [[View Full Profile]]  <i class="fa fa-user"></i>
                        </a>
                    </p>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-md-12 col-sm-6"><i>{$user.Address}&nbsp;</i></div>
                <div class="col-md-12 col-sm-6 state"><i>{$user.City} {$user.State}&nbsp;</i></div>
                {if $user.DealershipWebsite.exists && $user.DealershipWebsite.isNotEmpty}
                    <div class="col-md-12 col-sm-6"><span class="fieldValue AgencyWebsite"><a href="{$user.DealershipWebsite}">{$user.DealershipName} [[Website]]</a></span></div>
                {else}
                    <div class="col-md-12 col-sm-6"><span class="fieldValue AgencyWebsite">&nbsp;</span></div>
                {/if}

                <div class="col-md-12 col-sm-6">
                    <span class="fieldValue Email">
                        {if $user.DisplayEmail.isTrue}
                            {mailto address=$user.email encode="javascript"}
                        {else}
                            <a href="#">&nbsp;</a>
                        {/if}
                    </span>
                </div>
                <div class="col-xs-12 hidden-sm phone-number">
                    <span class="h5">{$user.PhoneNumber}&nbsp;</span>
                </div>
            </div>

            <div class="row seller-control-link visible-xs visible-md visible-lg">
                <div class="col-xs-12"><hr/></div>
                <div class="col-lg-6">
                    <a href="{page_path id='users_listings'}{$user.id}/{$UriFilePart}" >
                        <i class="fa fa-th-list"></i>  [[Listings]]
                    </a>
                </div>
                <p class="visible-sx"></p>
                <p class="visible-sx"></p>
                <div class="col-lg-6">
                    <a href="{page_path id='users'}{$user.id}/{$UriFilePart}">
                        <i class="fa fa-user"></i> [[View Full Profile]]
                    </a>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-sm-7 col-xs-12 col-md-12 hidden-xs hidden-md hidden-lg vcenter">
                    <span class="h5">{$user.PhoneNumber}</span>
                </div>
                <div class="col-sm-5 col-xs-12 col-md-12 vcenter contact-seller">
                    <div class="space-20"></div>
                    <a class="btn btn-orange h5" onclick='return openDialogWindow("[[Contact Seller]]", this.href, 560)' href="{page_path id='users_contact'}{$user.id}/{$UriFilePart}">[[Contact Seller]]</a>
                </div>
            </div>
        </div>
    </div>
</div>
{/strip}
