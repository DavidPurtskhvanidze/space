<div class="breadcrumbs">
    <ul class="breadcrumb">
        <li>[[Manage Languages]]</li>
    </ul>
</div>
<div class="page-content">
    <div class="page-header">
        <h1 class="lighter">[[Manage Languages]]</h1>
    </div>
        {display_error_messages}

        <a class="btn btn-link" href="{page_path module='I18N' function='add_language'}">[[Add a New Language]]</a>
        <br /><br />
        <div class="row">
            <div class="col-xs-12">
              {display_error_messages}
              {display_success_messages}
              
                <table class="items sortable table table-striped table-hover">
                 <thead>
                  <tr class="head">
										<th>[[Language Caption]]</th>
										<th>[[Active Language]]</th>
										<th>[[Status]]</th>
										<th>[[Actions]]</th>
                  </tr>
                 </thead>
                 <tbody>
                {foreach from=$langs item="lang" name=items_block}
                  <tr class="{cycle values="odd,even"}">
                    <td>{$lang.caption}</td>
                    <td>{if $lang.active}[[Yes]]{else}[[No]]{/if}</td>
										<td>
											{if $lang.is_default}
												<span class="selected">[[Default Language]]</span>
											{elseif $lang.active}
												<a class="itemControls" href="{page_path module='I18N' function='manage_languages'}?languageId={$lang.id}&action=set_default_language">[[Make Default]]</a>
											{/if}
										</td>

                    <td>
											<div class="btn-group">
											<a class="itemControls edit btn btn-xs btn-info" href="{page_path module='I18N' function='edit_language'}?languageId={$lang.id}" title="[[Edit:raw]]">
												<i class="icon-edit"></i>
											</a>

											{if !$lang.is_default}
												{assign var="langCaption" value=$lang.caption}
												<a class="itemControls delete btn btn-xs btn-danger" href="{page_path module='I18N' function='manage_languages'}?languageId={$lang.id}&action=delete_language" onclick='return confirm("[[Do you want to delete $langCaption language?:raw]]")' title="[[Delete:raw]]">
													<i class="icon-trash"></i>
												</a>
											{/if}
											<a class="itemControls btn btn-xs btn-primary" href="{page_path module='I18N' function='manage_phrases'}?language={$lang.id}&action=search_phrases" title="[[Translate Phrases:raw]]">
												<i class="icon-language-management"></i>
											</a>
										</div>
                  </tr>
                {/foreach}
                 </tbody>
                </table>
              
            </div>
          </div>
        
    </div>
</div>
