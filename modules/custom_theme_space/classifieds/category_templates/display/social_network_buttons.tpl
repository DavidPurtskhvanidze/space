
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


        <div class="twitter social-button">
            <a href="https://twitter.com/share" class="twitter-share-button" data-url="{$listingUrl}" data-count-url="{$listingUrl}">Tweet</a>
            {literal}<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>{/literal}
        </div>
        <div class="Pinterest social-button">
            <script src="//platform.linkedin.com/in.js" type="text/javascript">
                lang: en_US
            </script>
            <script type="IN/Share" data-counter="right"></script>
        </div>
        <div class="gplus g+ social-button">
            <script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
            <g:plusone size="medium"></g:plusone>
        </div>
        <div class="facebook social-button">
            {capture assign='listingUrl'}{page_url id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html{/capture}
            <iframe src="http://www.facebook.com/plugins/like.php?href={$listingUrl|urlencode}&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:24px;" allowTransparency="true"></iframe>
        </div>


