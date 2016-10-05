<div class="form-group">
    <label class="col-sm-4 control-label">
      [[Favicon Icon]]
    </label>
    <div class="col-sm-8">
        {if !empty($settings.favicon_icon)}
            <img src="{$GLOBALS.site_url}/{$picturesDir}{$settings.favicon_icon}">
        {/if}
        <div>
            <input type="file" name="favicon_icon" id="id-input-file-2" class="form-control-file">
        </div>
        {if !empty($settings.favicon_icon)}
            <div>
                <a href="?action=save&amp;delete_favicon=1">[[Delete Favicon Icon]]</a>
            </div>
        {/if}
        <div class="help-block">
            {$supportedFileFormats = 'ICO, PNG, GIF'}
            [[Supported file formats: $supportedFileFormats]].<br/>[[Please note that Internet Explorer prior to version 11.0 supports only ICO format for the favicon.]]
        </div>
    </div>
</div> 
