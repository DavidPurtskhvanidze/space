<div class="editCategory">
	<div class="breadcrumbs">
    <ul class="breadcrumb">
		{foreach from=$ancestors item=ancestor name=ancestor_cycle}
			<li><a href="{page_path id='edit_category'}?sid={$ancestor.sid}">[[$ancestor.caption]]</a></li>
		{/foreach}
		  <li>[[Category Settings]]</li>
    </ul>
	</div>

  <div class="page-content">
    <div class="page-header">
      {if $category.sid == 0}
        {capture assign="categoryName"}[[Root]]{/capture}
      {else}
        {capture assign="categoryName"}[[$category.name]]{/capture}
      {/if}
      <h1 class="ligter">[[$categoryName Category Settings]]</h1>
    </div>

    <div class="row">
      <div class="col-xs-12">

        <a class="btn btn-link" href="{page_path module='classifieds' function='category_fields'}?sid={$category.sid}">[[Edit $categoryName Category Fields]]</a>

        {display_error_messages}

        <div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60  smaller-60 "></i>) are mandatory]]</div>

        <form method="POST" role="form" class="form-horizontal">

          <input type="hidden" name="action" value="save_info">
          <input type="hidden" name="sid" value="{$category.sid}">
          {foreach from=$form_fields key=field_name item=form_field}
            {if $form_field.caption != ''}
            <div class="form-group">
            {/if}
            {if $form_field.caption != ''}
              <label for="" class="col-sm-3 control-label">
                [[$form_field.caption]]
                {if $form_field.is_required}<i class="icon-asterisk smaller-60  smaller-60 "></i>{/if}
              </label>
              <div class="col-sm-8 {$form_field.id}">
                {if $form_field.id == 'listing_caption_template_content'}
                  {input property=$form_field.id template="textarea.tpl"}
                  <div class="help-block">
                    [[This is what visitors see as a listing caption on the Listing Details page. The inputs built of the specified fields have an effect on SEO. Please refer to the User Manual article at User Manual -> Additional Features -> Listing Caption to learn more about the appropriate use of this feature.]]
                  </div>
                {elseif $form_field.id == 'listing_url_seo_data'}
                  {input property=$form_field.id template="textarea.tpl"}
                  <div class="help-block">
                    [[This is what goes into a URL for each listing. The inputs built of the specified fields have an effect on SEO. Please refer to the User Manual article at User Manual -> Additional Features -> SEO Data Included in Listing URL to learn more about the appropriate use of this feature.]]
                  </div>
                {else}
                    {input property=$form_field.id}
                {/if}
              </div>
            {elseif $form_field.id != "browsing_settings"}
              <div class="col-sm-9">
                  {input property=$form_field.id}
              </div>
            {/if}
            {if $form_field.caption != ''}
            </div>
            {/if}
            <div class="space-4"></div>

          {/foreach}

          <div class="clearfix form-actions">
            <input type="submit" name="handler_save_category" value="[[Save:raw]]" class="btn btn-default">
          </div>

          {if $category.sid ne 0}
            {input property="browsing_settings" template="browsing_settings_for_category.tpl"}
          {/if}

        </form>
      </div>
    </div>
  </div>
</div>
