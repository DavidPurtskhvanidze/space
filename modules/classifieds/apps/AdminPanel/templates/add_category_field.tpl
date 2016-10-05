<div class="addCategoryField">
    <div class="breadcrumbs">
        <ul class="breadcrumb">
            <li>{foreach from=$ancestors item=ancestor}
			<a href="{page_path id='edit_category'}?sid={$ancestor.sid}">[[$ancestor.caption]]</a> &gt;
		{/foreach}
		[[Add Category Field]]</li>
        </ul>
    </div>
	<div class="page-content">
        <div class="page-header">
            <h1 class="lighter">[[Add Category Field]]</h1>
        </div>
        {display_error_messages}
        <div class="row">
            <div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60"></i>) are mandatory]]</div>

            <form method="post" class="form-horizontal" role="form">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="category_sid" value="{$category_sid}">
                {foreach from=$form_fields key=field_name item=form_field}
                    <div class="form-group">
                        <label class="col-sm-2 control-label">
                          [[$form_field.caption]]
                          {if $form_field.is_required}<i class="icon-asterisk smaller-60"></i>{/if}
                        </label>
                        <div class="col-sm-8">
                            {input property=$form_field.id}
                        </div>
                    </div>
                {/foreach}
                <div class="clearfix form-actions">
                   <input type="submit" value="[[Add:raw]]" class="btn btn-default">
                </div>
            </form>
        </div>
    </div>
</div>
