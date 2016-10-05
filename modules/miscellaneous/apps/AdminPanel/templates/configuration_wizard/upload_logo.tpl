<div class="UploadLogoStep">
    <div class="form-group">
        <div class="row">
            <label class="col-sm-4 control-label">
                [[Main Logo]]
            </label>

            <div class="col-sm-8">
                {if !empty($settings.main_logo)}
                    <img class="img-responsive" src="{$GLOBALS.site_url}/{$picturesDir}{$settings.main_logo}">
                    <br>
                {/if}
                <div>
                    <input type="file" name="main_logo" id="id-input-file-3" class="form-control-file">
                </div>
                {if !empty($settings.main_logo)}
                    <div>
                        <a href="?action=save&amp;delete_main_logo=1&amp;repeat=1">[[Delete Main Logo]]</a>
                    </div>
                {/if}
                <div class="help-block">
                    {$supportedFileFormats = 'ICO, PNG, GIF, JPG, JPEG'}
                    [[Supported file formats: $supportedFileFormats]]
                </div>
            </div>
        </div>

    </div>

    <div class="form-group">
        <div class="row">
            <label class="col-sm-4 control-label">
                [[Fixed Top Menu Logo]]
            </label>

            <div class="col-sm-8">
                {if !empty($settings.fixed_top_menu_logo)}
                    <img class="img-responsive" src="{$GLOBALS.site_url}/{$picturesDir}{$settings.fixed_top_menu_logo}">
                    <br>
                {/if}
                <div>
                    <input type="file" name="fixed_top_menu_logo" id="id-input-file-4" class="form-control-file">
                </div>
                {if !empty($settings.fixed_top_menu_logo)}
                    <div>
                        <a href="?action=save&amp;delete_fixed_top_menu_logo=1&amp;repeat=1">[[Delete]]</a>
                    </div>
                {/if}
                <div class="help-block">
                    [[Only for fixed top menu]]<br/>
                </div>
                <div class="help-block">
                    [[Supported file formats: $supportedFileFormats]]<br/>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label class="col-sm-4 control-label">
                [[Mobile Logo]]
            </label>

            <div class="col-sm-8">
                {if !empty($settings.mobile_logo)}
                    <img class="img-responsive" src="{$GLOBALS.site_url}/{$picturesDir}{$settings.mobile_logo}">
                    <br>
                {/if}
                <div>
                    <input type="file" name="mobile_logo" id="id-input-file-4" class="form-control-file">
                </div>
                {if !empty($settings.mobile_logo)}
                    <div>
                        <a href="?action=save&amp;delete_mobile_logo=1&amp;repeat=1">[[Delete Mobile Logo]]</a>
                    </div>
                {/if}
                <div class="help-block">
                    [[Supported file formats: $supportedFileFormats]]<br/>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.UploadLogoStep a').each(function () {
            //adding step value to action links
            $(this).attr("href", $(this).attr("href") + "&current_step=" + $('.UploadLogoStep').last().parent().parent().find('.panel-heading').data('step'));
        });
    });

</script>
