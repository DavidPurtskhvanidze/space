{if $errorCode == 'UPLOAD_ERR_INI_SIZE'}
	[[The uploaded file '$fileId' exceeds the upload_max_filesize directive in php.ini.]]
{elseif $errorCode == 'UPLOAD_ERR_FORM_SIZE'}
	[[The uploaded file '$fileId' exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.]]
{elseif $errorCode == 'UPLOAD_ERR_PARTIAL'}
	[[The uploaded file '$fileId' was only partially uploaded.]]
{elseif $errorCode == 'UPLOAD_ERR_NO_FILE'}
	[[No file '$fileId' was uploaded]]
{elseif $errorCode == 'UPLOAD_ERR_NO_TMP_DIR'}
	[[Missing a temporary folder.]]
{elseif $errorCode == 'UPLOAD_ERR_CANT_WRITE'}
	[[Failed to write file to disk.]]
{elseif $errorCode == 'UPLOAD_ERR_EXTENSION'}
	[[A PHP extension stopped the file upload.]]
{elseif $errorCode == 'UPLOAD_CUSTOM_ERR_NOT_UPLOADED_FILE'}
	[[The file '$fileId' was not uploaded via HTTP POST]]
{elseif $errorCode == 'MOVE_ERR_DESTINATION_DIR_NOT_WRITABLE'}
	[[Cannot move uploaded file '$fileId' as the destination directory '$directory' is not writable. Please set the permission of the directory to 777.]]
{elseif $errorCode == 'MOVE_ERR_DESTINATION_FILE_NOT_WRITABLE'}
	[[Cannot move uploaded file '$fileId' as the destination file '$file' is already exists and is not writable. Please set the permission of the file to 666.]]
{else}
	{$errorCode}
{/if}
