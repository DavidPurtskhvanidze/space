{if $hasError}<div class="error validation">{$error}</div>{/if}
{if $value.file_name ne null}
<a href="{$value.file_url}">{$value.file_name|escape}</a> 

| <a href="{page_path module='classifieds' function='delete_uploaded_file'}?listing_id={$listing['id']}&amp;field_id={$id}">[[Delete]]</a>

<br/><br/>
{/if}
<input type="file" name="{$id}" id="{$id}" />
