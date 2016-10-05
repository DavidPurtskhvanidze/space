{if !isset($style)}
    {$style  = $default_style}
{/if}
<div class="form-control-file{if $hasError} has-error tooltip-error form-control-file{/if}" {if $hasError}data-rel="tooltip" data-placement="top" title="{$error}"{/if}>
    {if !empty($value.$style.name)}
        <a href="{page_path module='publications' function='delete_uploaded_file'}?article_sid={$article.sid}"
           onclick="return confirm('[[Are you sure you want to delete this picture?:raw]]')">[[Delete]]</a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <img src="{$value.$style.url}" alt="" border="0"/>
        <br/>
        <br/>
    {/if}
    <input type="file" id="{$id}-input-file" name="{$id}"/>
</div>


<script type="text/javascript">
    $('#{$id}-input-file').ace_file_input({
        no_file: '[[No File ...]]',
        btn_choose: '[[Choose]]',
        btn_change: '[[Change]]',
        droppable: false,
        onchange: null,
        icon_remove: false,
        thumbnail: true, //| true | large
        blacklist: 'gif|png|jpg|jpeg'
    });
</script>
