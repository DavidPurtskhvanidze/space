{if $error eq 'WRONG_PARAMETERS_SPECIFIED'}
	<p class="error">[[Wrong parameters are specified]]</p>
{elseif $error eq 'PARAMETERS_MISSED'}
	<p class="error">[[The system cannot proceed as some key parameters are missing]]</p>
{elseif $error eq 'NOT_OWNER'}
	<p class="error">[[You are not the owner of this listing and cannot edit its pictures]]</p>
{elseif $error eq 'PICTURES_NOT_ALLOWED_BY_PACKAGE'}
	[[You cannot load pictures in accordance to the settings of your current Listing Package.]]
{/if}
