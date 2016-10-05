{if $hasError}<div class="error validation">{$error}</div>{/if}
{if $value.file_name ne null}
<a href="{page_path module='users' function='delete_uploaded_file'}?field_id={$id}">[[Delete]]</a>
&nbsp;&nbsp;&nbsp;&nbsp;
<img src="{$value.file_url}" alt="" border="0" />
<br/><br/>
{/if}
<input type="file" name="{$id}" id="{$id}" />
