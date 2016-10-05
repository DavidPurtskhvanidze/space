{if $value.file_name ne null}
	<a href="{$value.file_url}">{$value.file_name|escape}</a>
	|
	<a href="{page_path module='classifieds' function='delete_uploaded_file'}?listing_id={$listing_id}&field_id={$id}">Delete</a>
	<br/>
	<br/>
{/if}
<div>
	<input type="file" name="{$id}" id="id-input-file-2" class="form-control-file {if $hasError}has-error{/if}" {if $hasError}data-error="{$error}"{/if}/>
</div>
<small>[[Supported video formats are: $supportedVideoFormats]]</small>
<script type="text/javascript">
$('#id-input-file-1 , #id-input-file-2').ace_file_input({
					no_file:'No File ...',
					btn_choose:'Choose',
					btn_change:'Change',
					droppable:false,
					onchange:null,
					thumbnail:false //| true | large
					//whitelist:'gif|png|jpg|jpeg'
					//blacklist:'exe|php'
					//onchange:''
					//
				});
</script>
