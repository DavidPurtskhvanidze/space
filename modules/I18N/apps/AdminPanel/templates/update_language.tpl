<div class="breadcrumbs">
	<ul class="breadcrumb">
		<li><a href="{page_path module='I18N' function='manage_languages'}">[[Manage Languages]]</a> &gt; [[Edit language]]</li>
	</ul>
</div>
<div class="page-content">
	<div class="page-header">
		<h1 class="lighter">[[Edit language]]</h1>
	</div>
	<div class="row">
		<div class="alert alert-info">[[Fields marked with an asterisk (<i
					class="icon-asterisk smaller-60  smaller-60 "></i>) are mandatory]]
		</div>
		{display_error_messages}
		<form method="post" class="form-horizontal" role="form">
            {CSRF_token}
			<input type="hidden" name="languageId" value="{$lang.id}">
			<input type="hidden" name="action" value="update_language">

			<div class="form-group">
				<label class="col-sm-3 control-label">
					[[Language Caption]]
					<i class="icon-asterisk smaller-60  smaller-60 "></i>
				</label>

				<div class="col-sm-8">
					<input type="text" name="caption" value="{$lang.caption}" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">
					[[Active Language]]
				</label>

				<div class="col-sm-8">
					<div class="checkbox">
                        <input type="hidden" name="active" value="0"/>
                        <label>

							<input class="ace  ace-switch ace-switch-6" type="checkbox" name="active"{if $lang.active} checked {/if}
										 value="1"/>
							<span class="lbl"></span>

						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">
					[[Front-End Theme]]
					<i class="icon-asterisk smaller-60  smaller-60 "></i>
				</label>

				<div class="col-sm-8">
					<select name="theme" class="form-control">
						<option value="">[[No theme:raw]]</option>
						{foreach from=$themes item=theme}
							<option value="{$theme}"{if $lang.theme == $theme} selected="selected"{/if}>{$theme}</option>
						{/foreach}
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">
					[[Admin Theme]]
					<i class="icon-asterisk smaller-60  smaller-60 "></i>
				</label>

				<div class="col-sm-8">
					<select name="admin_theme" class="form-control">
						<option value="">[[No theme:raw]]</option>
						{foreach from=$admin_themes item=theme}
							<option value="{$theme}"{if $lang.admin_theme == $theme} selected{/if}>{$theme}</option>
						{/foreach}
					</select>
				</div>
			</div>
			{if $GLOBALS.mobile_front_end_url}
				<div class="form-group">
					<label class="col-sm-3 control-label">
						[[Mobile Theme]]
						<i class="icon-asterisk smaller-60  smaller-60 "></i>
					</label>

					<div class="col-sm-8">
						<select name="mobile_theme" class="form-control">
							<option value="">[[No theme:raw]]</option>
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
					<i class="icon-asterisk smaller-60  smaller-60 "></i>
				</label>

				<div class="col-sm-8">
					<input type="text" name="date_format" value="{$lang.date_format}" class="form-control">

					<div class="help-block">
						[[DateFormatExample]]
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">
					[[Decimal Separator]]
					<i class="icon-asterisk smaller-60  smaller-60 "></i>
				</label>

				<div class="col-sm-8">
					<select name="decimal_separator" class="form-control">
						<option value=".">[[dot:raw]]</option>
						<option value=","{if $lang.decimal_separator == ','} selected {/if}>[[comma:raw]]</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">
					[[Thousand Separator]]
					<i class="icon-asterisk smaller-60  smaller-60 "></i>
				</label>

				<div class="col-sm-8">
					<select name="thousands_separator" class="form-control">
						<option value=".">[[dot:raw]]</option>
						<option value=","{if $lang.thousands_separator == ','} selected {/if}>[[comma:raw]]</option>
						<option value=" "{if $lang.thousands_separator == ' '} selected {/if}>[[space:raw]]</option>
					</select>
				</div>
			</div>
			<div class="clearfix form-actions">
				<input type="submit" value="[[Save:raw]]" class="btn btn-default">
			</div>
		</form>
	</div>
	<div class="phrasesControls">
		<a class="btn btn-link"
			 href="{page_path module='I18N' function='manage_phrases'}?language={$lang.id}&action=search_phrases">[[Translate
			Phrases]]</a>
		<br/>
		<a class="btn btn-link" href="{page_path module='I18N' function='import_language'}">[[Import translations]]</a>
		<br/>
		<a class="btn btn-link" href="{page_path module='I18N' function='export_language'}">[[Export translations]]</a>
		<br/>
	</div>
</div>
    
