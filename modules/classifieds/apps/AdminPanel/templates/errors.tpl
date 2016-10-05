{foreach from=$errors item=error key=field_caption}
	{if $error eq 'EMPTY_VALUE'} <p class="error">[[Required field]] "{$field_caption}" [[is empty, please enter a value.]]</p>
	{elseif $error eq 'NOT_UNIQUE_VALUE'} <p class="error">[[The value entered into]] "{$field_caption}" [[field is already used, please choose a different value.]]</p>
	{elseif $error eq 'NOT_CONFIRMED'} <p class="error">[[The values entered into the]] "{$field_caption}" [[fields mismatch. Please enter the same value into both input boxes.]]</p>
	{elseif $error eq 'DATA_LENGTH_IS_EXCEEDED'} <p class="error">[[The value entered into]] "{$field_caption}" [[field exceeds maximum allowed length.]]</p>
	{elseif $error eq 'NOT_INT_VALUE'} <p class="error">[[The value entered into]] "{$field_caption}" [[is not an integer number.]]</p>
	{elseif $error eq 'OUT_OF_RANGE'} <p class="error">[[The value entered into]] "{$field_caption}" [[is out of allowed range (per field settings).]]</p>
	{elseif $error eq 'NOT_FLOAT_VALUE'} <p class="error">[[The value entered into]] "{$field_caption}" [[is not a float value.]]</p>
	{elseif $error eq 'LOCATION_NOT_EXISTS'} <p class="error">[[The name of the location entered in]] "{$field_caption}" [[cannot be found within known geographic locations. If you are sure that there is no typo in the name, please add this location on the "Geographical Locations" page.]]</p>
	{elseif $error eq 'NOT_VALID_ID_VALUE'} <p class="error">[[The value entered into]] "{$field_caption}" [[contains invalid characters. Only alphanumeric and underscore ("_") symbols are allowed for this field.]]</p>
	{elseif $error eq 'SEARCH_EXPIRED'} <p class="error">[[Unfortunately, your search criteria have expired. Please start the search over.]]</p>
	{/if}
{/foreach}
