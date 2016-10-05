{$supportedImageFormats = 'gif, png, jpg, jpeg'}
<script type="text/javascript" src="{url file="field_types^ace-elements.min.js"}"></script>
{if $value.file_name ne null}
    <a href="{page_path module='users' function='delete_uploaded_file'}?field_id={$id}">[[Delete]]</a>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <img src="{$value.file_url}" alt="" border="0" />
    <br /><br />
{/if}
<input type="file" id="{$id}-input-file" name="{$id}" class="{if $hasError}has-error tooltip-error{/if}" {if $hasError}data-rel="tooltip" data-placement="top" title="{$error}"{/if}/>
<span class="help-block">
    [[Supported image formats are: $supportedImageFormats]]
</span>
<script type="text/javascript">
    $('#{$id}-input-file').ace_file_input({
        no_file:'[[No File ...]]',
        btn_choose:'[[Choose]]',
        btn_change:'[[Change]]',
        droppable:false,
        onchange:null,
        icon_remove: false,
        thumbnail:false, //| true | large
        blacklist:'gif|png|jpg|jpeg'
    });
</script>
