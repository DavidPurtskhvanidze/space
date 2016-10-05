<div class="editPhrase">
    <div class="breadcrumbs">
        <ul class="breadcrumb">
            <li><a href="{page_path module='I18N' function='manage_phrases'}">[[Manage Phrases]]</a> &gt; [[Edit phrase]]</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <h1>[[Edit phrase]]</h1>
        </div>
	
<div class="row">
<div class="searchForm">
	{display_error_messages}
	<form method="post" enctype="multipart/form-data" class="form-horizontal" role="form">
        {CSRF_token}
		<input type="hidden" name="phrase" value="{$phrase.id|escape}">
		<input type="hidden" name="domain" value="{$phrase.domain}">
		<input type="hidden" name="lang" value="{$chosen_lang}">
		<input type="hidden" name="action" value="update_phrase">
        <div class="form-group">
            <label class="col-sm-2 control-label">
              [[Domain]]
            </label>
            <div class="col-sm-8">
                {$phrase.domain}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">
              [[Phrase ID]]
            </label>
            <div class="col-sm-8">
                {$phrase.id|escape}
            </div>
        </div>
            {foreach from=$langs item=lang}
				{if empty($chosen_lang) || (!empty($chosen_lang) && $chosen_lang == $lang.id)}
                    <div class="form-group">
                        <label class="col-sm-2 control-label">
                          {$lang.caption}
                        </label>
                        <div class="col-sm-8">
                            {assign var="lang_id" value=$lang.id}
                            <textarea name="translations[{$lang.id}]" class="form-control">{$phrase.translations.$lang_id|escape}</textarea>
                        </div>
                    </div>
                {/if}
			{/foreach}
            
            <div class="clearfix form-actions">
                <input type="submit" value="[[Search:raw]]" class="btn btn-default">
            </div>		
	</form>
</div>
</div>
</div>
</div>
