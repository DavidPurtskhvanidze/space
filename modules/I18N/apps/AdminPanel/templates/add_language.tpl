<div class="breadcrumbs">
    <ul class="breadcrumb">
        <li><a href="{page_path module='I18N' function='manage_languages'}">[[Manage Languages]]</a> &gt; [[Add language]]</li>
    </ul>
</div>

    <div class="page-content">
        <div class="page-header">
            <h1 class="lighter">[[Add language]]</h1>
        </div>
<div class="row">
    <div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60"></i>) are mandatory]]</div>
      {display_error_messages}

    <form method="post" class="form-horizontal" role="form">
        {CSRF_token}
        <input type="hidden" name="action" value="add_language">
        <div class="form-group">
            <label class="col-sm-3 control-label">
              [[Language ID]]
              <i class="icon-asterisk smaller-60"></i>
            </label>
            <div class="col-sm-8">
                <input type="text" name="languageId" value="{$request_data.languageId}" class="form-control">
                <div class="help-block">
                  [[Use two-letter language ID (en, fr, es).<br />Full List: <a href="$languageIdList">ISO_639-1.html</a>]]
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">
              [[Language Caption]]
              <i class="icon-asterisk smaller-60"></i>
            </label>
            <div class="col-sm-8">
                <input type="text" name="caption" value="{$request_data.caption}" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">
              [[Active]]
            </label>
            <div class="col-sm-8">
                <div class="checkbox">
                    <input type="hidden" name="active" value="0">
                    <label>
                        <input class="ace ace-switch ace-switch-6" type="checkbox" name="active"{if $request_data.active} checked {/if} value="1"/>
                        <span class="lbl"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">
              [[Theme]]
            </label>
            <div class="col-sm-8">
                <select name="theme" class="form-control">
                    <option value="">[[No theme]]</option>
                    {foreach from=$themes item=theme}
                        <option value="{$theme}"{if $request_data.theme == $theme} selected {/if}>{$theme}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">
              [[Admin Theme]]
            </label>
            <div class="col-sm-8">
                 <select name="admin_theme" class="form-control">
                    <option value="">[[No theme]]</option>
                    {foreach from=$admin_themes item=theme}
                        <option value="{$theme}"{if $request_data.theme == $theme} selected {/if}>{$theme}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        {if $GLOBALS.mobile_front_end_url}
            <div class="form-group">
                <label class="col-sm-3 control-label">
                  [[Mobile Theme]]
                </label>
                <div class="col-sm-8">
                    <select name="mobile_theme" class="form-control">
                        <option value="">[[No theme]]</option>
                        {foreach from=$mobile_themes item=theme}
                            <option value="{$theme}"{if $lang.mobile_theme == $theme} selected {/if}>{$theme}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
        {/if}
        <div class="form-group">
            <label class="col-sm-3 control-label">
              [[Date Format]]
            </label>
            <div class="col-sm-8">
                <input type="text" name="date_format" value="{$request_data.date_format|default:'%Y-%m-%d'}" class="form-control">
                <div class="help-block">
                    [[DateFormatExample]]
                  </div>
            </div>
            
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">
              [[Decimal Separator]]
            </label>
            <div class="col-sm-8">
                <select name="decimal_separator" class="form-control">
                     <option value=".">[[dot]]</option>
                     <option value=","{if $request_data.decimal_separator == ','} selected {/if}>[[comma]]</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">
              [[Thousand Separator]]
            </label>
            <div class="col-sm-8">
                <select name="thousands_separator" class="form-control">
                    <option value=".">[[dot]]</option>
                    <option value=","{if $request_data.thousands_separator == ',' or empty($request_data.thousands_separator)} selected {/if}>[[comma]]</option>
                    <option value=" "{if $request_data.thousands_separator == ' '} selected {/if}>[[space]]</option>
                </select>
            </div>
        </div>
        <div class="clearfix form-actions">
           <input type="submit" value="[[Add:raw]]" class="btn btn-default">
        </div>
    </form>
    </div>
</div>
