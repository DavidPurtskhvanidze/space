{if $error eq 'PARAMETERS_MISSED'}
	<p class="error">[[The system cannot proceed as some key parameters are missing]]</p>
{elseif $error eq 'WRONG_PARAMETERS_SPECIFIED'}
	<p class="error">[[Wrong parameters are specified]]</p>
{elseif $error eq 'NOT_OWNER'}
	<p class="error">[[You are not the owner of this listing]]</p>
{else}
	<p class="error">
		{$error}
	</p>
{/if}
