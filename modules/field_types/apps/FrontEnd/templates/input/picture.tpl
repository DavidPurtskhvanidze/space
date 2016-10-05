{if $value.file_name ne null}
<p>
	<a href="{page_path module='users' function='delete_uploaded_file'}?field_id={$id}">[[Delete]]</a>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<img src="{$value.file_url}" alt="{$value.file_name}" />
</p>
{/if}
<input type="file" name="{$id}" class="form-control-file {if $hasError}has-error{/if}" {if $hasError}data-error="{$error}"{/if} />
