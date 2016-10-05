<div class="form-group">
    <label class="col-sm-4 control-label">
      [[Watermark Image]]
    </label>
    <div class="col-sm-8">
        {if $settings.watermark_picture}
            <div class="watermarkImageView">
                <img src="{$GLOBALS.site_url}/{$picturesDir}{$settings.watermark_picture}">
            </div>
		{/if}
        <br />
        <div class="watermarkInputControl">
            <input type="file" name="watermark_picture" id="id-input-file-2" class="form-control-file">
        </div>
        <div class="watermarkInputControl">
            <a href="?action=save&amp;delete_watermark=1" id="DeleteWatermark" class="btn btn-default">[[Delete Watermark Picture]]</a>
        </div>
        <div class="help-block">
            [[Supported file formats : JPEG, GIF, PNG8<br />Please refer to the article of the User Manual at User Manual -> Additional Features -> Watermark to learn more about watermark settings.]]
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[Watermark Opacity]]
    </label>
    <div class="col-sm-8">
        <input type="text" name="watermark_transparency" value="{$settings.watermark_transparency}" class="form-control">
        <div class="help-block">
            [[This parameter sets the opacity of the watermark image (in percent). The watermark image is placed on top of the uploaded image. Zero value makes the watermark completely transparent, while the "100" value makes the uploaded picture invisible behind the solid watermark image. Supported watermark formats are JPEG, GIF, PNG-8 with transparent backgrounds. Due to a <a href="http://bugs.php.net/bug.php?id=23815">known PHP bug</a>, php disregards transparency settings for PNG-24 images.]]
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[Watermark Position]]
    </label>
    <div class="col-sm-8">
        <select name="watermark_position" value="{$settings.watermark_position}" class="form-control">
            <option value="top-left" {if $settings.watermark_position == 'top-left'} selected{/if}>[[Top Left]]</option>
            <option value="top-center" {if $settings.watermark_position == 'top-center'} selected{/if}>[[Top Center]]</option>
            <option value="top-right" {if $settings.watermark_position == 'top-right'} selected{/if}>[[Top Right]]</option>
            <option value="middle-left" {if $settings.watermark_position == 'middle-left'} selected{/if}>[[Middle Left]]</option>
            <option value="middle-center" {if $settings.watermark_position == 'middle-center'} selected{/if}>[[Middle Center]]</option>
            <option value="middle-right" {if $settings.watermark_position == 'middle-right'} selected{/if}>[[Middle Right]]</option>
            <option value="bottom-left" {if $settings.watermark_position == 'bottom-left'} selected{/if}>[[Bottom Left]]</option>
            <option value="bottom-center" {if $settings.watermark_position == 'bottom-center'} selected{/if}>[[Bottom Center]]</option>
            <option value="bottom-right" {if $settings.watermark_position == 'bottom-right'} selected{/if}>[[Bottom Right]]</option>
        </select>
        <div class="help-block">
            [[This parameter sets the position of the watermark image.]]
        </div>
    </div>
</div>
