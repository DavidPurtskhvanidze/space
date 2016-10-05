{foreach from=$errors item=error key=field_caption}
	{if $error eq 'AUTHORIZATION_FAILED'} <p class="error">[[You have no rights to create the period.]]</p>
	{elseif $error eq 'EMAIL_IS_EMPTY'} <p class="error">[[The email field is empty. Please type in your email address.]]</p>
	{elseif $error eq 'EMAIL_NOT_VALID'} <p class="error">[[Your email address is not properly formatted. Please type your email address in the appropriate format (yourname@email.com).]]</p>
	{elseif $error eq 'NAME_IS_EMPTY'} <p class="error">[[Your NAME field is empty.]]</p>
	{elseif $error eq 'LISTING_SID_IS_EMPTY'} <p class="error">[[Listing ID is not specified.]]</p>
	{elseif $error eq 'FIELD_SID_IS_EMPTY'} <p class="error">[[Calendar ID is not specified.]]</p>
	{elseif $error eq 'PERIOD_FROM_IS_EMPTY'} <p class="error">[[The beginning of the period is not specified or empty.]]</p>
	{elseif $error eq 'PERIOD_TO_IS_EMPTY'} <p class="error">[[The end of the period is not specified or empty.]]</p>
	{elseif $error eq 'UNKNOWN_DATE_FORMAT_IN_PERIOD_FROM'} <p class="error">[[The beginning of the period contains unknown date format. Please put your date in yyyy-mm-dd.]]</p>
	{elseif $error eq 'UNKNOWN_DATE_FORMAT_IN_PERIOD_TO'} <p class="error">[[The end of the period contains unknown date format. Please put your date in yyyy-mm-dd.]]</p>
	{elseif $error eq 'LISTING_NOT_FOUND'} <p class="error">[[Listing was not found.]]</p>
	{elseif $error eq 'FIELD_NOT_FOUND'} <p class="error">[[Calendar was not found.]]</p>
	{elseif $error eq 'PERIODS_INTERSECTS'} <p class="error">[[This listing is not available for some time within the requested booking period. Please choose another period that does not overlap with the existing booking(s) marked on the calendar.]]</p>
	{elseif $error eq 'FROM_MUST_BE_BEFORE_TO'} <p class="error">[[Start date of the period exceeds the end date.]]</p>
	{elseif $error eq 'DELETE_AUTHORIZATION_FAILED'} <p class="error">[[You have no rights to delete the period.]]</p>
	{elseif $error eq 'PERIOD_NOT_EXISTS'} <p class="error">[[Deleted period does not exist.]]</p>
	{/if}
{/foreach}
