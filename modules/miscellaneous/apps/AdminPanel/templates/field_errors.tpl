{foreach from=$errors item=error key=field_caption}
	<p class="error">
		{if $error eq 'EMPTY_VALUE'}
			[[Required field]] "[[$field_caption]]" [[is empty, please enter a value.]]
		{elseif $error eq 'NOT_UNIQUE_VALUE'}
			[[The value entered into]] "[[$field_caption]]" [[field is already used, please choose a different value.]]
		{elseif $error eq 'NOT_CONFIRMED'}
			[[The values entered into the]] "[[$field_caption]]" [[fields mismatch. Please enter the same value into both input boxes.]]
		{elseif $error eq 'DATA_LENGTH_IS_EXCEEDED'}
			[[The value entered into]] "[[$field_caption]]" [[field exceeds maximum allowed length.]]
		{elseif $error eq 'NOT_INT_VALUE'}
			[[The value entered into]] "[[$field_caption]]" [[is not an integer number.]]
		{elseif $error eq 'OUT_OF_RANGE'}
			[[The value entered into]] "[[$field_caption]]" [[value is out of allowed range (per field settings).]]
		{elseif $error eq 'NOT_FLOAT_VALUE'}
			[[The value entered into]] "[[$field_caption]]" [[is not a float value.]]
		{elseif $error eq 'LOCATION_NOT_EXISTS'}
			[[The name of the location entered in]] "[[$field_caption]]" [[cannot be found within known geographic locations. If you are sure that there is no typo in the name, please add this location on the "Geographical Locations" page.]]
		{elseif $error eq 'NOT_VALID_ID_VALUE'}
			[[The value entered into]] "[[$field_caption]]" [[contains invalid characters. Only alphanumeric and underscore ("_") symbols are allowed for this field.]]
		{elseif $error eq 'NOT_SUPPORTED_VIDEO_FORMAT'}
			[[Format of the file uploaded to the]] "[[$field_caption]]" [[is not supported. Supported formats are: 3gp, asf, asx, avi, mov, mp4, mpg, qt, rm, swf, wmv.]]
		{elseif $error eq 'MAX_FILE_SIZE_EXCEEDED'}
			[[File uploaded to the]] "[[$field_caption]]" [[exceeds the maximum allowed size (per field settings).]]
		{elseif $error eq 'OUT_OF_MYSQL_MEDIUMINT_RANGE'}
			[[The value entered into]] "[[$field_caption]]" [[field is out of acceptable range (from -2147483648 to 2147483647).]]
		{elseif $error eq 'NO_IMAGE_FILE'}
			[[The picture file]] "[[$field_caption]]" [[does not exist.]]
		{elseif $error eq 'FILE_NOT_SPECIFIED'}
			'[[$field_caption]]' [[file is not specified]]
		{elseif $error eq 'PARAMS_MISSING'}
			[[The system cannot proceed as some key parameters are missing.]]
		{elseif $error eq 'WRONG_PARAMS'}
			[[Wrong parameters are specified]]
		{elseif $error eq 'WRONG_DATE_FORMAT'}
			[[The date value entered into]] "[[$field_caption]]" [[is not recognized as a valid date.]]
		{elseif $error eq 'VALUE_ALREADY_EXISTS'}
			[[Field]] "[[$field_caption]]" [[already exists, please enter another value.]]
		{elseif $error eq "USER_GROUP_SID_NOT_SET"}
			[[User group SID is not set.]]
		{elseif $error eq 'UNDEFINED_USER_GROUP'}
			[[Not existent or empty user group is specified in the field]] "[[$field_caption]]".
		{elseif $error eq 'INVALID_IP_ADDRESS'}
			[[Invalid IP address/range.]]
		{elseif $error eq 'INVALID_IP_MASK'}
			[[Invalid IP mask.]]
		{elseif $error eq 'ERROR_INVALID_SEARCH_IP'}
			[[Invalid IP / IP Range used for search.]]
		{elseif $error eq 'IP_RANGE_SID_NOT_SET'}
			[[IP range SID is not set.]]
		{elseif $error eq 'CAN_NOT_BLOCK_LOCAL_IP'}
			[[The entered IP range contains your IP address. You are trying to block yourself. The range can not be blocked.]]
		{elseif $error eq 'UPLOAD_DIR_NOT_EXIST'}
			[[The directory "files" does not exist. Please create it and set its permissions to 777, and afterwards you'll be able to proceed.]]
		{elseif $error eq 'UPLOAD_DIR_NOT_WRITABLE'}
			[[The directory "files" is not writable. Please set its permissions to 777 and afterwards you'll be able to proceed.]]
		{elseif $error eq 'UPLOAD_FILEGROUP_DIR_NOT_WRITABLE'}
			[[One or several subderictories of the "files" folder is/are not writable. Please set the permissions of the "files" subderictories to 777 and afterwards you'll be able to proceed.]]
		{else}
			[[{$error}]]
		{/if}
	</p>
{/foreach}
