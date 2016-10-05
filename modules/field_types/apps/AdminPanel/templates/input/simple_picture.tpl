{$supportedImageFormats = 'gif, png, jpg, jpeg'}
<script type="text/javascript" src="{url file="field_types^ace-elements.min.js"}"></script>

{if !isset($style)}
    {$style  = $default_style}
{/if}

{strip}
    <div class="form-control-file{if $hasError} has-error tooltip-error form-control-file{/if}" {if $hasError}data-rel="tooltip" data-placement="top" title="{$error}"{/if}>
        {if $value.$style.name ne null}
            <div class="image center">
                <img class="img-responsive" src="{$value.$style.url}" alt="" border="0" />
            </div>
            <div class="space-20"></div>
        {/if}
        <input type="file" id="{$id}-input-file" name="{$id}" />
    </div>
    <span class="help-block bg-info alert">
    [[Supported image formats are: $supportedImageFormats]]
</span>
{/strip}

<script type="text/javascript">
    $('#{$id}-input-file').ace_file_input({
        no_file:'',
        btn_choose:'[[Choose]]',
        btn_change:'[[Change]]',
        droppable:false,
        onchange:null,
        icon_remove: false,
        thumbnail:false, //| true | large
        blacklist:'gif|png|jpg|jpeg'
    });
</script>
