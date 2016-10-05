<div class="editCategoryField">
    <div class="breadcrumbs">
        <ul class="breadcrumb">
            <li>{foreach from=$ancestors item=ancestor}
			<a href="{page_path id='edit_category'}?sid={$ancestor.sid}">[[$ancestor.caption]]</a> &gt;
		{/foreach}
		[[$listing_field_info.caption]]</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <h1 class="lighter">[[Edit Category Field Info]]</h1>
        </div>
        {display_error_messages}
        <div class="row">
            <form method="post" class="form-horizontal" role="form">
            <input type="hidden" name="action" value="save_info">
            <input type="hidden" name="sid" value="{$field_sid}">
                {foreach from=$form_fields key=field_name item=form_field}
                    <div class="form-group">
                        <label class="col-sm-2 control-label">
                          [[$form_field.caption]]
                          {if $form_field.is_required}<i class="icon-asterisk smaller-60"></i>{/if}
                        </label>
                        
                        <div class="col-sm-8 {if $form_field.id == "type"}paddingTop{/if}">
                            {input property=$form_field.id}
                        </div>
                    </div>
                {/foreach}
                <div class="clearfix form-actions">
                    <input type="submit" value="[[Save:raw]]" class="btn btn-default">
                 </div>
            </form>

            {if $field_type eq 'list' || $field_type eq 'multilist'}
                <a class="btn btn-link" href="{page_path id='edit_listing_field_edit_list'}?field_sid={$field_sid}">[[Edit List Values]]</a>
            {elseif $field_type eq 'geo'}
                <a class="btn btn-link" href="{page_path id='geographic_data'}">[[Edit Geographic Locations]]</a>
            {elseif $field_type eq 'tree'}
                <a class="btn btn-link" href="{page_path id='edit_listing_field_edit_tree'}?field_sid={$field_sid}">[[Edit Tree Values]]</a>
            {/if}
        </div>
    </div>
</div>
