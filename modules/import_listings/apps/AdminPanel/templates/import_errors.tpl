{foreach from=$errors item=value key=error}
{if $error eq 'INVALID_DELIMITER'}
<p class="error">[[CSV delimiter is invalid for this file.]]</p>
{elseif $error eq 'CANNOT_OPEN_FILE'}
<p class="error">[[Cannot open the file]] {$value}.</p>
{elseif $error eq 'UPLOAD_ERR_NO_FILE'}
<p class="error">[[File for import is not specified.]]</p>
{elseif $error eq 'FILE_EXTENSION_NOT_EQUAL_FILE_TYPE'}
<p class="error">[[Selected file type]] {$value.0} [[does not match the file extension]] {$value.1}.</p>
{elseif $error eq 'FILE_NOT_FOUND_IN_ARCHIVE'}
<p class="error">[[File with]] {$value} [[file type is not found in the archive.]]</p>
{else}
{$error}
{/if}
{/foreach}
