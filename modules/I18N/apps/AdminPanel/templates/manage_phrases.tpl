<div class="breadcrumbs">
	<ul class="breadcrumb">
		<li>[[Manage Phrases]]</li>
	</ul>
</div>
<div class="page-content">
	<div class="page-header">
		<h1 class="lighter">[[Manage Phrases]]</h1>
	</div>

	<div class="row">
		<div class="searchForm">
			{display_error_messages}
			<form method="post" class="form-horizontal" role="form" action="">
                {CSRF_token}
				<input type="hidden" name="action" value="search_phrases">

				<div class="form-group">
					<label class="col-sm-2 control-label">
						[[Phrase ID]]
					</label>

					<div class="col-sm-8">
						{include 'field_types^input/string_with_autocomplete.tpl' id="phrase_id" value=$criteria.phrase_id autocomplete_service_name='I18N' autocomplete_method_name='PhraseIds'}
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">
						[[Domain]]
					</label>

					<div class="col-sm-8">
						<select name="domain" class="form-control">
							<option value="">[[Any:raw]]</option>
							{foreach from=$domains item=domain}
								<option value="{$domain}"{if $criteria.domain == $domain} selected{/if}>{$domain}</option>
							{/foreach}
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">
						[[Language]]
					</label>

					<div class="col-sm-8">
						<select name="language" class="form-control">
							{foreach from=$languages item=language}
								{if $criteria.language == $language.id}
									{assign var='chosen_language_id' value=$language.id}
									{assign var='chosen_language_caption' value=$language.caption}
								{/if}
								<option
										value="{$language.id}"{if $criteria.language == $language.id} selected="selected"{/if}>{$language.caption}</option>
							{/foreach}
						</select>
					</div>
				</div>
				<div class="clearfix form-actions">
					<input type="submit" value="[[Search:raw]]" class="btn btn-default">
				</div>
			</form>
		</div>
		<a class="btn btn-link" href="{page_path module='I18N' function='add_phrase'}">[[Add new phrase]]</a>
		<br/>

		<div class="row">
			<div class="col-xs-12">
				<table class="items phraseTable sortable table table-striped table-hover">
					<thead>
						<tr>
							<th>[[Phrase ID]]</th>
							<th>{$chosen_language_caption}</th>
							<th>[[Actions]]</th>
						</tr>
					</thead>
					<tbody>
					{foreach from=$phrases item=phrase}
						{if $phrase.domain != $domain}
							<tr class="groupCaption">
								<th colspan="4">{$phrase.domain}</th>
							</tr>
							{assign var="resetCycle" value=true}
						{else}
							{assign var="resetCycle" value=false}
						{/if}
						<tr>
							<td><a
										href="{page_path module='I18N' function='edit_phrase'}?phrase={$phrase.id|escape:"url"}&domain={$phrase.domain}"
										title="Edit">{$phrase.id|escape}</a></td>
							<td>
								<a href="#" class="editablePhrase"
									 data-value="{$phrase.translations.$chosen_language_id|escape}" data-pk="{$phrase.id|escape}"
									 data-domain="{$phrase.domain}"
									 title="[[Click to Modify:raw]]">{$phrase.translations.$chosen_language_id|escape}</a>
                                
							</td>
							<td class="WidthColumn">
								<a class="itemControls edit btn btn-xs btn-info"
									 href="{page_path module='I18N' function='edit_phrase'}?phrase={$phrase.id|escape:"url"}&domain={$phrase.domain}"
									 title="[[Edit:raw]]"><i class="icon-edit"></i></a>
								<a class="itemControls delete btn btn-xs btn-danger"
									 href="?action=delete_phrase&phrase={$phrase.id|escape:"url"}&domain={$phrase.domain}"
									 onclick="return confirm('[[Do you want to delete the phrase?:raw]]')" title="[[Delete:raw]]"><i class="icon-trash"></i></a>
							</td>
						</tr>
						{assign var=domain value=$phrase.domain}
					{/foreach}
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

{require component="jquery" file="jquery.js"}
{require component="jquery-ui" file="jquery-ui.js"}

{require component="X-editable-jqueryui" file="js/jqueryui-editable.min.js"}
{require component="X-editable-jqueryui" file="css/jqueryui-editable.css"}

<script type="text/javascript">
	$(document).ready(function () {
        makePhrazeEditable();
        function makePhrazeEditable(context)
        {
            context = context || "table.phraseTable";
            $('.editablePhrase', context).editable({
                ajaxOptions: {
                    type: 'post'
                },
                mode: 'inline',
                showbuttons: false,
                type: 'text',
                url: '{page_path module='I18N' function='edit_phrase'}',
                params: function (params) {
                    var dataToPost = {
                        phrase: params.pk,
                        domain: $(this).data('domain'),
                        lang: '{$chosen_language_id}',
                        translations: { '{$chosen_language_id}': params.value },
                        performActionAndStop: true,
                        action: 'update_phrase'
                    }
                    return dataToPost;
                },
                emptytext: '[[Add Translation:raw]]',
                emptyclass: 'emptyCaption',
                title: '[[Edit Translation:raw]]'
//                error: function (response, newValue) {
//
//                    // handler returns error messages as html code
//                    var $div = $("<div/>").html(response.responseText);
//
//                    // we know that messages are in the ".error li" elements
//                    return $(".error li", $div)
//                            .map(function () {
//                                return $.trim($(this).text());
//                            })
//                            .get()
//                            .join("\n");
//                },
//                success: function(response, newValue) {
//                }
            }).prop('title', '[[Click to Modify:raw]]');
        }

	});
</script>
