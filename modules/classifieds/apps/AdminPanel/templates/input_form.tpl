<div class="addListing">
	<div class="breadcrumbs">
    <ul class="breadcrumb">
      <li><a href="{page_path id='add_listing'}">[[Add Listing]]</a></li>
      <li>[[Categories!{$category_info.name}]]</li>
    </ul>
	</div>

  <div class="page-content">
    <div class="page-header">
      <h1>[[Add Listing]]</h1>
    </div>
      <div class="alert alert-warning">[[After a listing was created you need to activate it in order to see a listing on your site.]]</div>
    
    <div class="row">

    <div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60  smaller-60 "></i>) are mandatory]]</div>

    {display_error_messages}

      <form class="form form-horizontal" method="post" enctype="multipart/form-data" role="form">
          <input type="hidden" name="category_id" value="{$category_id}">
          {foreach from=$form_fields item=form_field}
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">
                [[FormFieldCaptions!{$form_field.caption}]]
                {if $form_field.is_required}<i class="icon-asterisk smaller-60  smaller-60 "></i>{/if}
              </label>
              <div class="col-sm-8">
                {input property=$form_field.id}
              </div>
            </div>
          {/foreach}

            {module name="listing_repost" function="display_add_listing_settings"}

            <div class="clearfix form-actions">
              <input name="action_add" type="submit" value="[[Add:raw]]" class="btn btn-default">
              <input name="action_add_pictures" type="submit" value="[[Add Pictures:raw]]" class="btn btn-default">
            </div>
      </form>
    </div>
  </div>
</div>
