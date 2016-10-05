{foreach from=$errors item=error key=field_caption}
{if $error eq 'INVALID_PERIOD_FROM'}
<p class="error">[[Period "From" is not valid.]]</p>
{elseif $error eq 'INVALID_PERIOD_TO'}
<p class="error">[[Period "To" is not valid.]]</p>
{elseif $error eq 'PAYMENT_ALREADY_COMPLETED'}
<p class="error">[[The payment you are trying to endorse has already been finalized.]]</p>
{else}
<p class="error">{$error}</p>
{/if}
{/foreach}
