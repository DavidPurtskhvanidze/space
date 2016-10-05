{if $GLOBALS.settings.facebook_app_id}
    <script>
        window.fbAsyncInit = function() {
            // init the FB JS SDK
            FB.init({
                appId      : '{$GLOBALS.settings.facebook_app_id}',
                status     : true,
                xfbml      : true
            });
        };

        // Load the SDK asynchronously
        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) { return; }
                js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/all.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        function shareOnFacebook(name, link, caption, picture, description){
            FB.ui({
                method: 'feed',
                name: name,
                link: link,
                caption: caption,
                picture: picture,
                description: description
            }, function(response) {
                if(response && response.post_id){ }
                else{ }
            });
        }
    </script>

    {$listingTitle = $listing|cat:""|strip_tags:false}
    {assign var=listingUrl value="{page_url id='listing'}"|cat:$listing.id}
    {if isset($listing.Comments)&&($listing.Comments neq "")}
        {$listingDescription = $listing.Comments|strip_tags|escape:'htmlall'|truncate:100:"...":true}
    {elseif isset($listing.Description)&&($listing.Description neq "")}
        {$listingDescription = $listing.Description|strip_tags|escape:'htmlall'|truncate:100:"...":true}
    {elseif isset($listing.SellerComments)&&($listing.SellerComments neq "")}
        {$listingDescription =$listing.SellerComments|strip_tags|escape:'htmlall'|truncate:100:"...":true}
    {else}
        {$listingDescription = $title|strip_tags|escape:'htmlall'|truncate:100:"...":true}
    {/if}

    {if $listing.pictures.numberOfItems > 0}
        {$listingimage = $listing.pictures.collection[0].file.picture.url}
    {else}
        {$listingimage = "{url file='main^no_image_available_small.png'}"}
    {/if}
{/if}
<div class="listing-controls">
    <div class="row">
        <div class="col-xs-12">
            <ul class="list-inline pull-right">
                {if $listing.active.isTrue}
                    {if $GLOBALS.settings.facebook_app_id}
                        <li>
                            <a class="fb-share" href="#" onclick="shareOnFacebook('{page_url id="root"}', '{$listingUrl}', '{$listingTitle}', '{$listingimage}', '{$listingDescription}')"><i class="fa fa-facebook-f"></i><span>[[Share]]</span></a>
                        </li>
                    {/if}
                    <li>
                        <a href="?action_deactivate&amp;listings[{$listing.id}]=1&amp;searchId={$listing_search.id}" onclick="return confirm('[[Are you sure?]]')" title="[[Deactivate Listing:raw]]">
                            <i class="fa fa-eye-slash"></i>
                        </a>
                    </li>
                {elseif $listing.moderation_status.rawValue == '' || strcasecmp($listing.moderation_status.rawValue, 'APPROVED') == 0}
                    <li>
                        <a href="{page_path module='classifieds' function='manage_listing_options'}?listing_sid={$listing.id}&amp;searchId={$listing_search.id}" onclick="return openDialogWindow('[[Manage Listing Options]]', this.href, 400, true)" title="[[Activate Listing:raw]]">
                            <i class="fa fa-eye"></i>
                        </a>
                    </li>
                {/if}
                <li>
                    <a href="?action_delete&amp;listings[{$listing.id}]=1&amp;searchId={$listing_search.id}" onclick="return confirm('[[Are you sure?]]')" title="[[Delete Listing:raw]]">
                        <i class="fa fa-trash"></i>
                    </a>
                </li>
                <li>
                    <a href="{page_path id='listing_edit'}{$listing.id}/" title="[[Edit Listing:raw]]">
                        <i class="fa fa-pencil"></i>
                    </a>
                </li>

                {if $listing.active.isTrue}
                    <li>
                        <a href="{page_path module='classifieds' function='manage_listing_options'}?listing_sid={$listing.id}&amp;searchId={$listing_search.id}"
                           onclick="return openDialogWindow('[[Manage Listing Options]]', this.href, 400, true)" title="[[Manage Listing Options:raw]]">
                            <i class="fa fa-cog"></i>
                        </a>
                    </li>
                {/if}
                {if $REQUEST.listingsInBasket->inArray($listing.id.value)}
                    <li><a href="{page_path id='basket'}?listing_sid[equal]={$listing.id}">[[Pay For Listing]]</a></li>
                {/if}
            </ul>
        </div>
    </div>

</div>
