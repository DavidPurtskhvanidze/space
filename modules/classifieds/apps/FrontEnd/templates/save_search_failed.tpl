{foreach from=$errors item=error}
    {if $error eq 'COOKIE_MAX_SIZE_EXCEEDED'}
        <p class="error">
        [[Maximum query storage capacity exceeded for you as an unregistered user. To save more searches, please login or register.]]
        </p>
	{elseif $error == 'EMPTY_SEARCH_NAME'}
		<p class="error emptySearchName">'[[FormFieldCaptions!Search Name]]' [[is empty, please enter a value.]]</p>
	{elseif $error == 'INVALID_SEARCH_ID_PROVIDED'}
		<p class="error invalidSearchIdProvided">[[Cannot save none existing search]]</p>
    {else}
		{$error}
    {/if}
{/foreach}
