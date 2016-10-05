<div class="form-group">
    <label class="col-sm-4 control-label bolder">
      [[Listing Pictures]]
    </label>
    <div class="col-sm-8">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[Listing Picture Width]]
    </label>
    <div class="col-sm-8">
        <input type="text" name="listing_picture_width" value="{$settings.listing_picture_width}" class="form-control">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[Listing Picture Height]]
    </label>
    <div class="col-sm-8">
        <input type="text" name="listing_picture_height" value="{$settings.listing_picture_height}" class="form-control">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[Listing Thumbnail Width]]
    </label>
    <div class="col-sm-8">
        <input type="text" name="listing_thumbnail_width" value="{$settings.listing_thumbnail_width}" class="form-control">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[Listing Thumbnail Height]]
    </label>
    <div class="col-sm-8">
        <input type="text" name="listing_thumbnail_height" value="{$settings.listing_thumbnail_height}" class="form-control">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">
        [[Listing Large Picture Width]]
    </label>
    <div class="col-sm-8">
        <input type="text" name="listing_big_picture_width" value="{$settings.listing_big_picture_width}" class="form-control">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">
        [[Listing Large Picture Height]]
    </label>
    <div class="col-sm-8">
        <input type="text" name="listing_big_picture_height" value="{$settings.listing_big_picture_height}" class="form-control">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[JPEG Image Quality]]
    </label>
    <div class="col-sm-8">
        <input type="text" name="jpeg_image_quality" value="{$settings.jpeg_image_quality}" class="form-control">
        <div class="help-block">
            [[The highest quality is 10, the lowest is 0]]
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[Listing Picture Storage Method]]
    </label>
    <div class="col-sm-8">
        <select name="listing_picture_storage_method" class="form-control">
            <option value="file_system">[[File System:raw]]</option>
            <option value="database"{if $settings.listing_picture_storage_method == 'database'} selected{/if}>[[Database:raw]]</option>
        </select>
        <div class="help-block">
            [[File System Storage recommended for minimizing the db size]]
        </div>
    </div>
</div>

<div class="form-group">
	<label class="col-sm-4 control-label bolder">
		[[Article Pictures]]
	</label>
	<div class="col-sm-8">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label">
		[[Article Picture Width]]
	</label>
	<div class="col-sm-8">
		<input type="text" name="article_picture_width" value="{$settings.article_picture_width}" class="form-control">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label">
		[[Article Picture Height]]
	</label>
	<div class="col-sm-8">
		<input type="text" name="article_picture_height" value="{$settings.article_picture_height}" class="form-control">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label">
		[[Article Picture Storage Method]]
	</label>
	<div class="col-sm-8">
		<select name="article_picture_storage_method" class="form-control">
			<option value="file_system">[[File System:raw]]</option>
			<option value="database"{if $settings.article_picture_storage_method == 'database'} selected{/if}>[[Database:raw]]</option>
		</select>
		<div class="help-block">
			[[File System Storage recommended for minimizing the db size]]
		</div>
	</div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label bolder">
      [[Watermark]]
    </label>
    <div class="col-sm-8">
    </div>
</div>
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
<div class="form-group">
    <label class="col-sm-4 control-label bolder">
      [[Favicon]]
    </label>
    <div class="col-sm-8">
    </div>
</div>
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
            [[Supported file formats: $supportedFileFormats]].<br/>[[Please note that Internet Explorer prior to version 11.0 supports only ICO format for the favicon. This parameter sets the position of the watermark image.]]
        </div>
    </div>
</div> 
<div class="form-group">
    <label class="col-sm-4 control-label bolder">
      [[Misc]]
    </label>
    <div class="col-sm-8">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[User Input Escaping]]
    </label>
    <div class="col-sm-8">
        <select name="escape_html_tags" class="form-control">
            <option value="">[[No escaping (unsafe, XSS-vulnerable):raw]]</option>
            <option value="htmlentities"{if $settings.escape_html_tags == 'htmlentities'} selected{/if}>[[Escape all HTML tags:raw]]</option>
            <option value="htmlpurifier"{if $settings.escape_html_tags == 'htmlpurifier'} selected{/if}>[[Remove potentially unsafe tags with HTML Purifier:raw]]</option>
        </select>
        <div class="help-block">
            [[Letting your users add HTML tags to listing data entries can enhance the visual presentation of info, but can be potentially insecure. Use HTML Purifier or ASCII code converter to maximize security. Escaping of all HTML tags is not compatible with WYSIWYG editor. Use HTML purifier if WYSIWYG mode is on.]]
        </div>
    </div>
</div>         
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[Google Maps API key]]
    </label>
    <div class="col-sm-8">
        <input size="80" type="text" name="google_maps_API_key" value="{$settings.google_maps_API_key}" class="form-control">
        <div class="help-block">
            <a href="https://code.google.com/apis/console/">[[Sign Up for the Google Maps API following the API Access section on the left]]</a>
        </div>
    </div>
</div>         
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[Enable WYSIWYG Editor]]
    </label>
    <div class="col-sm-8">
        <div class="checkbox">
            <input type="hidden" name="enable_wysiwyg_editor" value="0">
            <label>
                <input class="ace ace-switch ace-switch-6" type="checkbox" name="enable_wysiwyg_editor" value="1"{if $settings.enable_wysiwyg_editor} checked{/if}>
                <span class="lbl"></span>
            </label>
        </div>

        <div class="help-block">
            [[WYSIWYG Editor is not compatible with escaping all HTML tags.]]
        </div>
    </div>
</div>         
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[Enable Share Block]]
    </label>
    <div class="col-sm-8">
        <div class="checkbox">
            <input type="hidden" name="enable_share_block" value="0">
            <label>
                <input class="ace ace-switch ace-switch-6" type="checkbox" name="enable_share_block" value="1"{if $settings.enable_share_block} checked{/if}>
                <span class="lbl"></span>
            </label>
        </div>

    </div>
</div>         
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[Task Scheduler]]
    </label>
    <div class="col-sm-8">
        {capture assign="returnBackUri"}{page_path id='settings'}{/capture}
            <a href="{page_path module='miscellaneous' function='task_scheduler' app='FrontEnd'}?showlog&amp;returnBackUri={$returnBackUri|urlencode}">[[Run Task Scheduler]]</a>
		{assign var="userManualTaskSchedulerUrl" value=$GLOBALS.site_url|cat:"/../doc/UserManual/task_scheduler.htm"}
        <div class="help-block">
            [[The task_scheduler.php script is used to execute periodic tasks like listing auto-extension, subscription expiration and sending notifications to users and site administrator.<br />You can learn more about task_scheduler.php in the <a href="$userManualTaskSchedulerUrl">User Manual article</a> (User manual -> Reference -> Module and Functions -> Miscellaneous -> task_scheduler)]]
        </div>
    </div>
</div>
{if count($paymentMethods) > 1}
   <div class="form-group">
        <label class="col-sm-4 control-label">
          [[Payment Method]]
        </label>
        <div class="col-sm-8">
            <select name="payment_method" class="form-control">
				{foreach from=$paymentMethods key=className item=caption}
                    <option value="{$className}"{if $settings.payment_method == $className} selected{/if}>[[$caption:raw]]</option>
				{/foreach}
            </select>
            <div class="help-block">
                [[Change of payment method may take up to 1-2 minutes. Please do not stop the script and do not close your browser.]]
            </div>
        </div>
    </div>
{/if}  
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[Enable autocomplete for keyword search]]
    </label>
    <div class="col-sm-8">
        <div class="checkbox">
            <input type="hidden" name="autocomplete_enable_in_keyword_search" value="0">
            <label>
                <input class="ace ace-switch ace-switch-6" type="checkbox" name="autocomplete_enable_in_keyword_search" value="1"{if $settings.autocomplete_enable_in_keyword_search} checked{/if}>
                <span class="lbl"></span>
            </label>
        </div>

    </div>
</div>       
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[Response for deleted and inactive listings]]
    </label>
    <div class="col-sm-8">
        {$options = ''}
			{if $settings.display_default_response_on_listing_not_found_and_deactivated == '1'}{$options=' checked="CHECKED"'}{/if}
			<label><input type="radio" name="display_default_response_on_listing_not_found_and_deactivated" value="1"{$options} />[[The default response in according to HTTP/1.1 Status Code standards <a href="http://tools.ietf.org/html/rfc2616" title="RFC 2616">RFC 2616</a>:]]</label>
				<ul>
					<li>[[404 Not Found - for deleted listings]]</li>
					<li>[[403 Forbidden - for inactive listings]]</li>
				</ul>
			{$options = ''}
			{if $settings.display_default_response_on_listing_not_found_and_deactivated == '0'}{$options=' checked="CHECKED"'}{/if}
			<label><input type="radio" name="display_default_response_on_listing_not_found_and_deactivated" value="0"{$options} />[[303 See Other - both for deleted and inactive listings, redirecting to a page with the response code 200 OK:]]</label>
			<input type="text" name="redirect_uri_on_listing_not_found_and_deactivated" value="{$settings.redirect_uri_on_listing_not_found_and_deactivated}" class="form-control">
			<a href="{$GLOBALS.front_end_url}{$settings.redirect_uri_on_listing_not_found_and_deactivated}" class="viewThisPage">[[view this page]]</a>
        <div class="alert alert-warning">
            {$siteBackendUrl = $GLOBALS.site_url}
				[[Please specify the <a href="$siteBackendUrl{page_uri id='site_pages'}">Site Page</a> URI for the redirect as follows: /example-page/ <br /> You should create this page before using it.]]
        </div>
    </div>
</div>

<div class="form-group">
	<label class="col-sm-4 control-label bolder">
		[[Secure admin logging]]
	</label>
	<div class="col-sm-8">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label">
		[[Number of failed login attempts]]
	</label>
	<div class="col-sm-8">
		<input type="text" name="lf_limit" value="{$settings.lf_limit}" class="form-control">
		<div class="help-block">[[Number of failed authorizations after which the system will block further logging into the admin panel.]]</div>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label">
		[[Failed login monitoring interval (mins)]]
	</label>
	<div class="col-sm-8">
		<input type="text" name="lf_time" value="{$settings.lf_time}" class="form-control">
		<div class="help-block">[[Time interval during which the system counts failed login attempts]]</div>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label">
		[[Login suspension duration (mins)]]
	</label>
	<div class="col-sm-8">
		<input type="text" name="lf_time_block" value="{$settings.lf_time_block}" class="form-control">
		<div class="help-block">[[Time interval during which any further login attempts to the admin panel are impossible]]</div>
	</div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label bolder">
        [[Under Construction Mode]]
    </label>
    <div class="col-sm-8">
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">
        [[Under Construction Mode]]
    </label>
    <div class="col-sm-8">
        <div class="checkbox">
            <input type="hidden" name="under_construction_mode" value="0">
            <label>
                <input class="ace ace-switch ace-switch-6" type="checkbox" name="under_construction_mode" value="1"{if $settings.under_construction_mode} checked{/if}>
                <span class="lbl"></span>
            </label>
        </div>

    </div>
</div>

<div class="clearfix form-actions ClearBoth">
   <input type="submit" class="btn btn-default" value="[[Save:raw]]">
</div>
<script type="text/javascript">
$('#id-input-file-1 , #id-input-file-2').ace_file_input({
					no_file:'[[No File ...]]',
		btn_choose:'[[Choose]]',
		btn_change:'[[Change]]',
		droppable:false,
		onchange:null,
		icon_remove: false,
		thumbnail:false, //| true | large
		blacklist:'csv|xls'
	});
</script>
