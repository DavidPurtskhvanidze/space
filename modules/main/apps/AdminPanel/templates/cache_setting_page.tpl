{capture assign="returnBackUri"}{page_path id='settings'}{/capture}
<div class="form-group">
    <label class="col-sm-4 control-label">
        [[Smarty Cache]]
    </label>
    <div class="col-sm-8">
        <a href="{page_path module='miscellaneous' function='clear_cache'}?cache_type=smarty&returnBackUri={$returnBackUri|urlencode}">[[Clear Cache]]</a>
        {assign var="userManualClearCacheUrl" value=$GLOBALS.site_url|cat:"/../doc/UserManual/smarty_and_block_cache.htm"}
        <div class="help-block">
            [[The link "Clear Cache" deletes temporary cache files of Smarty template processor. You may need to use this feature when you suspect that the changes which you made in templates are not actually displayed on your website.<br />You can learn more in the <a href="$userManualClearCacheUrl">User Manual article</a> (User manual -> Reference -> System Settings -> Cache)]]
        </div>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">
        [[Block Cache]]
    </label>
    <div class="col-sm-8">
        <a href="{page_path module='miscellaneous' function='clear_cache'}?cache_type=blocks&returnBackUri={$returnBackUri|urlencode}">[[Clear Cache]]</a>
        <div class="help-block">
            [[The Block Cache option is designed to speed up loading of the front page. The contents of various blocks on the front page is cached (e.g. Recent Ads, Browse by block, etc.). When the cache option is ON, data in those blocks is cached and the homepage load speed increases.<br /> You can set cache refresh intervals independently for each block in the relevant templates]]
        </div>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">
        [[Block Cache on the main page]]
    </label>
    <div class="col-sm-8">
        <div class="checkbox">
            <input type="hidden" name="cache_blocks_main_page" value="0">
            <label>
                <input class="ace ace-switch ace-switch-6" type="checkbox" name="cache_blocks_main_page" value="1"{if $settings.cache_blocks_main_page} checked{/if}>
                <span class="lbl"></span>
            </label>
        </div>
    </div>
</div>

<div class="clearfix form-actions ClearBoth">
    <input type="submit" class="btn btn-default" value="[[Save:raw]]">
</div>
