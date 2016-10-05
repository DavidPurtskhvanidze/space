{if $errors}
{foreach from=$errors key=error_code item=error_message}
    <p class="error">
        {if $error_code == 'UNDEFINED_LISTING_ID'} [[Listing ID is not defined]]
        {elseif $error_code == 'WRONG_LISTING_ID_SPECIFIED'} [[There is no listing in the system with the specified ID]]
        {elseif $error_code == 'LISTING_IS_NOT_ACTIVE'} [[Listing with specified ID is not active]]
        {/if}
    </p>
    {/foreach}
{else}
    {include file="listing_details.tpl" listing=$listing }
{/if}
