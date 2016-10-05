<div class="addCategory">
	<div class="breadcrumbs">
    <ul class="breadcrumb">
      {foreach from=$ancestors item=ancestor}
        <li><a href="{page_path id='edit_category'}?sid={$ancestor.sid}">[[$ancestor.caption]]</a></li>
      {/foreach}
      <li>[[Add Category]]</li>
    </ul>
	</div>

  <div class="page-content">
    <div class="page-header">
      <h1>[[Add Category]]</h1>
    </div>

    <div class="row">
      <div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60  smaller-60 "></i>) are mandatory]]</div>

      {display_error_messages}

      <form method="post" class="form-horizontal" role="form">
        <input type="hidden" name="action" value="add">
        {input property="parent"}

        {foreach from=$form_fields key=field_name item=form_field}
          <div class="form-group">
          {if $form_field.id != "parent"}
            <label for="{$form_field.id}" class="col-sm-3 control-label">
              [[$form_field.caption]]
              {if $form_field.is_required}<i class="icon-asterisk smaller-60  smaller-60 "></i>{/if}
            </label>
            <div class="col-sm-8">
              {if $form_field.id == 'listing_caption_template_content'}
                {input property=$form_field.id template="textarea.tpl"}
                <div class="help-block">
                  [[The listing caption built of the specified fields is displayed on the Listing Details page and affects SEO results. Please refer to the article of the User Manual at User Manual -> Additional Features -> Listing Caption to learn more about its settings.]]
                </div>
              {elseif $form_field.id == 'listing_url_seo_data'}
                {input property=$form_field.id template="textarea.tpl"}
              {else}
                {input property=$form_field.id}
              {/if}
            </div>
          {/if}
          </div>
          <div class="space-4"></div>
        {/foreach}

        <div class="clearfix form-actions">
          <input type="submit" value="[[Add:raw]]" class="btn btn-default" />
        </div>

      </form>
    </div>
  </div>
</div>
