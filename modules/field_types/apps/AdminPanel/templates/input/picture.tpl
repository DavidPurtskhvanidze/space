{$supportedImageFormats = 'gif, png, jpg, jpeg'}
<script type="text/javascript" src="{url file="field_types^ace-elements.min.js"}"></script>

{if !isset($style)}
    {$style  = $default_style}
{/if}

{strip}
    <div class="form-control-file{if $hasError} has-error tooltip-error form-control-file{/if}" {if $hasError}data-rel="tooltip" data-placement="top" title="{$error}"{/if}>
        {if $value.$style.name ne null}
            <div class="image center">
                <img src="{$value.$style.url}" alt="" border="0" />
            </div>
            <div class="space-20"></div>
        {/if}
        <div class="row">
            <div class="{if $value.$style.name ne null} col-xs-8 {else}col-xs-12 col-sm-12{/if} vcenter">
                <input type="file" id="{$id}-input-file" name="{$id}" />
            </div>
            {if $value.$style.name ne null}
                <div class="col-xs-4 vcenter text-right">
                    <a href="{page_path module='users' function='delete_uploaded_file'}?field_id={$id}"><i class="fa fa-trash"></i>    [[Delete]]</a>
                </div>
            {/if}
        </div>
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
