<div class="container">
    <h1 class="savedSearchesHeader title">[[Saved Searches]]</h1>

    {foreach from=$errors item=error}
        <p class="error">
            {if $error == 'EMPTY_USER_EMAIL'}
                {capture assign="profilePageLink"}{page_path id='user_profile'}{/capture}
                [[Your Profile is missing the email address. Notifications require your valid email address in order to work properly. Please <a href="$profilePageLink">update your Profile information</a> by adding your email address.]]
            {else}
                [[$error]]
            {/if}
        </p>
    {/foreach}

    <div class="well">
    <div class="savedSearches underline-hover-effect">
        {foreach from=$saved_searches item=saved_search}
            <ul class="item list-inline">
                <li class="name">{$saved_search.name}</li>
                <li class="action"><a href="?action=delete&amp;search_id={$saved_search.id|escape:"url"}">[[Delete]]</a></li>
                <li class="action"><a href={$GLOBALS.site_url}{$saved_search.search_results_uri}?{$saved_search.query_string}&amp;action=search">[[Launch search]]</a></li>
                <li class="action">
                    {if $user_logged_in}
                        {if $saved_search.auto_notify}
                            <a href="?action=disable_notify&amp;search_id={$saved_search.id|escape:"url"}">[[Disable auto-notification]]</a>
                        {else}
                            <a href="?action=enable_notify&amp;search_id={$saved_search.id|escape:"url"}">[[Enable auto-notification]]</a>
                        {/if}
                    {/if}
                </li>
            </ul>
        {foreachelse}
            <p class="error">[[You have not saved any searches yet.]]</p>
        {/foreach}
    </div>
        </div>

</div>