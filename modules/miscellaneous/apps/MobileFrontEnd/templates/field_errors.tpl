{foreach from=$field_errors item=error key=field_caption}
	{if $error eq 'EMPTY_VALUE'} <p class="error">[[Required field]] '[[$field_caption]]' [[is empty, please enter a value.]]</p>
	{elseif $error eq 'NOT_UNIQUE_VALUE'} <p class="error">[[The value entered into]] '[[$field_caption]]' [[field is already used, please choose a different value.]]</p>
	{elseif $error eq 'NOT_CONFIRMED'} <p class="error">[[The values entered into the]] '[[$field_caption]]' [[fields mismatch. Please enter the same value into both input boxes.]]</p>
	{elseif $error eq 'DATA_LENGTH_IS_EXCEEDED'} <p class="error">[[The value entered into]] '[[$field_caption]]' [[field exceeds maximum allowed length.]]</p>
	{elseif $error eq 'NOT_INT_VALUE'} <p class="error">[[The value entered into]] '[[$field_caption]]' [[is not an integer number.]]</p>
	{elseif $error eq 'OUT_OF_RANGE'} <p class="error">[[The value entered into]] '[[$field_caption]]' [[is out of allowed range (per field settings).]]</p>
	{elseif $error eq 'NOT_FLOAT_VALUE'} <p class="error">[[The value entered into]] '[[$field_caption]]' [[is not a float value.]]</p>
	{elseif $error eq 'LOCATION_NOT_EXISTS'} <p class="error">[[The name of the location entered in]] '[[$field_caption]]' [[cannot be found within known geographic locations. If you are sure that there no typo in the name, please add this location on the 'Geographical Locations' page.]]</p>
	{elseif $error eq 'NOT_VALID_ID_VALUE'} <p class="error">[[The value entered into]] '[[$field_caption]]' [[contains invalid characters. Only alphanumeric and underscore ('_') symbols are allowed for this field.]]</p>
	{elseif $error eq 'INCORRECT_SECURITY_CODE'}<p class="error">[[The value entered into]] '[[$field_caption]]' [[is incorrect.]]</p>
	{elseif $error eq 'NOT_SUPPORTED_IMAGE_FORMAT'}<p class="error">[[The image format uploaded to]] '[[$field_caption]]' [[is not supported.]]</p>
	{elseif $error eq 'NOT_SUPPORTED_VIDEO_FORMAT'}<p class="error">[[The format of the file uploaded to]] '[[$field_caption]]' [[is not supported video file format.]]</p>
	{elseif $error eq 'MAX_FILE_SIZE_EXCEEDED'}<p class="error">[[The size of the file uploaded to]] '[[$field_caption]]' [[exceeds the quota (per field settings).]]</p>
	{elseif $error eq 'OUT_OF_MYSQL_MEDIUMINT_RANGE'} <p class="error">[[The value entered in to]] '[[{$field_caption}]]' [[field is out of acceptable range (from -2147483648 to 2147483647)]].</p>
{else}
	<p class="error">'[[$field_caption]]' [[$error]]</p>
{/if}
{/foreach}
