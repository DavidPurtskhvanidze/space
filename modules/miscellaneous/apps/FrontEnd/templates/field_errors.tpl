{capture assign="contactAdminLink"}{page_path id='contact'}{/capture}
{foreach from=$field_errors item=error key=field_caption}
	<p class="error">
		{if $error eq 'EMPTY_VALUE'}
			[[Required field]] '[[$field_caption]]' [[is empty, please enter a value.]]
		{elseif $error eq 'NOT_UNIQUE_VALUE'}
			[[The value entered into]] '[[$field_caption]]' [[field is already used, please choose a different value.]]
		{elseif $error eq 'NOT_CONFIRMED'}
			[[The values entered into the]] '[[$field_caption]]' [[fields mismatch. Please enter the same value into both input boxes.]]
		{elseif $error eq 'DATA_LENGTH_IS_EXCEEDED'}
			[[The value entered into]] '[[$field_caption]]' [[field exceeds maximum allowed length.]]
		{elseif $error eq 'NOT_INT_VALUE'}
			[[The value entered into]] '[[$field_caption]]' [[is not an integer number.]]
		{elseif $error eq 'OUT_OF_RANGE'}
			[[The value entered into]] '[[$field_caption]]' [[is out of allowed range (per field settings).]]
		{elseif $error eq 'NOT_FLOAT_VALUE'}
			[[The value entered into]] '[[$field_caption]]' [[is not a float value.]]
		{elseif $error eq 'LOCATION_NOT_EXISTS'}
			[[The name of the location entered in]] '[[$field_caption]]' [[cannot be found within known geographic locations. If you are sure that there no typo in the name, please add this location on the 'Geographical Locations' page.]]
		{elseif $error eq 'NOT_VALID_ID_VALUE'}
			[[The value entered into]] '[[$field_caption]]' [[contains invalid characters. Only alphanumeric and underscore ('_') symbols are allowed for this field.]]
		{elseif $error eq 'INCORRECT_SECURITY_CODE'}
			[[The value entered into]] '[[$field_caption]]' [[is incorrect.]]
		{elseif $error eq 'NOT_SUPPORTED_IMAGE_FORMAT'}
			[[The image format uploaded to]] '[[$field_caption]]' [[is not supported.]]
		{elseif $error eq 'NOT_SUPPORTED_VIDEO_FORMAT'}
			[[The format of the file uploaded to]] '[[$field_caption]]' [[is not supported video file format.]]
		{elseif $error eq 'MAX_FILE_SIZE_EXCEEDED'}
			[[The size of the file uploaded to]] '[[$field_caption]]' [[exceeds the quota (per field settings).]]
		{elseif $error eq 'OUT_OF_MYSQL_MEDIUMINT_RANGE'}
			[[The value entered in to]] '[[{$field_caption}]]' [[field is out of acceptable range (from -2147483648 to 2147483647)]].
		{elseif $error == "USER_NOT_FOUND"}
			[[User(s) not found]]
		{elseif $error == "USERGROUP_NOT_FOUND"}
			[[Unknown User Group]]
		{elseif $error == 'UPLOAD_DIR_NOT_EXIST' || $error == 'UPLOAD_DIR_NOT_WRITABLE' || $error == 'UPLOAD_FILEGROUP_DIR_NOT_WRITABLE'} 
			[[The storage folder for images and video files is closed. You cannot add pictures and videos to your listing. Please <a href="$contactAdminLink">contact</a> site administrator and report this issue. Thank you!]]
		{else}
			'[[$field_caption]]' [[$error]]
		{/if}
	</p>
{/foreach}
