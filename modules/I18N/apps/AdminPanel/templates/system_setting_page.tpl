<div class="form-group">
    <label class="col-sm-5 control-label bolder">
      [[Language and Regional Settings Configuration]]
    </label>
    <div class="col-sm-8">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[Default Domain]]
    </label>
    <div class="col-sm-8">
        <select name="i18n_default_domain" class="form-control">
			{foreach from=$i18n_domains item=domain}
                <option value="{$domain}"{if $settings.i18n_default_domain == $domain} selected{/if}>{$domain}</option>
			{/foreach}
        </select>
        <div class="help-block">
            [[Please refer to User Manual -> Multi-Language Support for info on domains]]
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[Default Language]]
    </label>
    <div class="col-sm-8">
        <select disabled="disabled" class="form-control">
			{foreach from=$i18n_languages item=language}
                <option value="{$language.id}"{if $settings.i18n_default_language == $language.id} selected{/if}>{$language.caption}</option>
			{/foreach}
        </select>
        <div class="help-block">
            [[To change default language go to Language Management -> Manage Languages]]
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">
        [[Sort translated list and tree values]]
    </label>
    <div class="col-sm-8">
        <div class="checkbox">
            <input type="hidden" name="i18n_sort_translated_list_and_tree_values" value="0" />
            <label>
                <input class="ace ace-switch ace-switch-6" type="checkbox" name="i18n_sort_translated_list_and_tree_values" value="1"{if $settings.i18n_sort_translated_list_and_tree_values} checked="checked"{/if}/>
                <span class="lbl"></span>
            </label>
        </div>
        <div class="help-block">
            [[Sort translated list and tree values in ABC ascending order]]
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[Mark Phrases That Are Not Translated]]
    </label>
    <div class="col-sm-8">
        <select name="i18n_display_mode_for_not_translated_phrases" class="form-control">
            <option value="default">[[defaultPhraseHighlight]]</option>
            <option value="highlight"{if $settings.i18n_display_mode_for_not_translated_phrases == 'highlight'} selected{/if}>[[highlight]]</option>
        </select>
        <div class="help-block">
            [[Set to highlight only when checking the interface translation to find phrases that are not translated. After the translation is finalized, set to default]]
        </div>
    </div>
</div>                
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[Radius Search Unit]]
    </label>
    <div class="col-sm-8">
        <select name="radius_search_unit" class="form-control">
            <option value="miles">[[Miles:raw]]</option>
            <option value="kilometers"{if $settings.radius_search_unit == 'kilometers'} selected{/if}>[[Kilometers:raw]]</option>
        </select>
    </div>
</div>                
<div class="clearfix form-actions ClearBoth">
   <input type="submit" value="[[Save:raw]]" class="btn btn-default">
</div>                
   
