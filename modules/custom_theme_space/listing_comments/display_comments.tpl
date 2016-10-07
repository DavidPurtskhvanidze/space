<div class="container">
    <ul class="list-inline underline-hover-effect">
        <li>
            <a href="{page_path id='listing'}{$listingSid}/">[[Back to the Listing]]</a>
        </li>
        {assign var='current_uri' value=$GLOBALS.current_page_uri|cat:'?'|cat:$smarty.server.QUERY_STRING}
        {assign var='current_uri' value=$current_uri|urlencode}
        <li>
            <a href="{page_path id='comment_add'}?listingSid={$listingSid}&amp;commentSid={$commentSid}&amp;returnBackUri={$current_uri}&amp;activeTab=commentsBlock">[[Add a Comment]]</a>
        </li>
    </ul>

    <h1>[[Comments]]</h1>

    <p>[[Below are comments on listing #$listingSid, $listing]]</p>

    {if $messages}{include file="message.tpl"}{/if}

    {include file="comments.tpl"}
</div>