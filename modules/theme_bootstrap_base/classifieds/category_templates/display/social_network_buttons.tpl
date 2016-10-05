<div class="row">
    <div class="col-xs-3">
        {require component="js" file="toggleListingAction.js"}
        <div class="row">
            <div class="listing-controls">
                <div class="col-xs-6">
                    <label>
                        <input title="[[Save Ad:raw]]" type="checkbox" name="saveAddSwitch" onchange="toggleListingAction(this, '{page_path id='listing_save'}', '{page_path module='classifieds' function='delete_saved_listing'}')" value="{$listing.id}"{if $listing.saved.isTrue} checked="checked"{/if} />
                        <span class="checked glyphicon glyphicon-heart" title="[[Saved:raw]]" data-toggle="tooltip" date-placement="top"></span>
                        <span class="unchecked glyphicon glyphicon-heart" title="[[Save Ad:raw]]" data-toggle="tooltip" date-placement="top"></span>
                    </label>
                </div>
                <div class="col-xs-6">
                    <label>
                        <input title="[[Compare Ad:raw]]" type="checkbox" name="compareAddSwitch" onchange="toggleListingAction(this, '{page_path id='listing_compare'}', '{page_path module='classifieds' function='remove_from_comparison'}')" value="{$listing.id}"{if $listing.inComparison.isTrue} checked="checked"{/if} />
                        <span class="checked glyphicon glyphicon-bookmark" title="[[In Comparison:raw]]" data-toggle="tooltip" date-placement="top"></span>
                        <span class="unchecked glyphicon glyphicon-bookmark" title="[[Compare Ad:raw]]" data-toggle="tooltip" date-placement="top"></span>
                    </label>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="Pinterest">
                    <a class="addthis_button_pinterest_pinit"></a>
                    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=xa-5170da8b1f667e6d"></script>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-9">
        {$title = $listing|cat:""|strip_tags:false}
        {meta tagProperty = 'og:title' tagContent=$title}
        {meta tagProperty = 'og:url' tagContent=$GLOBALS['site_url']|cat:$GLOBALS['current_page_uri']}
        {meta tagProperty = 'og:type' tagContent="website"}

        {if isset($listing.Comments)&&($listing.Comments neq "")}
            {meta tagProperty = 'og:description' tagContent=$listing.Comments|strip_tags|escape:'htmlall'}
        {elseif isset($listing.Description)&&($listing.Description neq "")}
            {meta tagProperty = 'og:description' tagContent=$listing.Description|strip_tags|escape:'htmlall'}
        {elseif isset($listing.SellerComments)&&($listing.SellerComments neq "")}
            {meta tagProperty = 'og:description' tagContent=$listing.SellerComments|strip_tags|escape:'htmlall'}
        {else}
            {meta tagProperty = 'og:description' tagContent=$title}
        {/if}

        {if $listing.pictures.numberOfItems > 0}
            {meta tagProperty = 'og:image' tagContent=$listing.pictures.collection.0.file.picture.url}
        {else}
            {meta tagProperty = 'og:image' tagContent="{url file='main^no_image_available_small.png'}"}
        {/if}

        {meta tagProperty = 'og:image:width' tagContent=$listing.pictures.collection.0.picture_width}
        {meta tagProperty = 'og:image:height' tagContent=$listing.pictures.collection.0.picture_height}
        <div class="row">
            <div class="col-xs-6">
                <div class="facebook">
                    {capture assign='listingUrl'}{page_url id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html{/capture}
                    <iframe src="http://www.facebook.com/plugins/like.php?href={$listingUrl|urlencode}&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:24px;" allowTransparency="true"></iframe>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="twitter">
                    <a href="https://twitter.com/share" class="twitter-share-button" data-url="{$listingUrl}" data-count-url="{$listingUrl}">Tweet</a>
                    {literal}<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>{/literal}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <div class="g+">
                    <script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
                    <g:plusone size="medium"></g:plusone>
                </div>
            </div>

            <div class="col-xs-6">
                <div class="Pinterest">
                    <script src="//platform.linkedin.com/in.js" type="text/javascript">
                        lang: en_US
                    </script>
                    <script type="IN/Share" data-counter="right"></script>
                </div>
            </div>
        </div>
    </div>
</div>

