<script type="text/javascript" src="{url file="field_types^ace-elements.min.js"}"></script>
{if $value.file_name ne null}
    <a href="{$value.file_url}">{$value.file_name|escape}</a>
    |
    <a href="{page_path module='classifieds' function='delete_uploaded_file'}?listing_id={$listing_id}&field_id={$id}">Delete</a>
    <br/>
    <br/>
{/if}
<input type="file" name="{$id}" id="{$id}-input-file" class="form-control-file {if $hasError}has-error tooltip-error{/if}" {if $hasError}data-rel="tooltip" data-placement="top" title="{$error}"{/if} />
<script type="text/javascript">
    $('#{$id}-input-file').ace_file_input({
        no_file:'No File ...',
        btn_choose:'Choose',
        btn_change:'Change',
        droppable:false,
        onchange:null,
        icon_remove: false,
        thumbnail:false, //| true | large
        blacklist:'exe|php|gif|png|jpg|jpeg'
        //whitelist:'gif|png|jpg|jpeg'
        //onchange:''
        //
    });
</script>
