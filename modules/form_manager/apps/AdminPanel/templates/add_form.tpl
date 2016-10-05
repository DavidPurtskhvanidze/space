<div class="searchForm editListValues">
	<div class="breadcrumbs">
    <ul class="breadcrumb">
		  <li><a href="{page_path module='form_manager' function='manage_forms'}">[[Manage Forms]]</a></li>
      <li>[[Add Form]]</li>
    </ul>
	</div>
  <div class="page-content">
    <div class="page-header">
      <h1>[[Add Form]]</h1>
    </div>
  	{display_error_messages}
  	{display_success_messages}

  	<div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60 smaller-60"></i>) are mandatory]]</div>
  	 <form class="form form-horizontal" method="post" enctype="multipart/form-data" role="form">
      <div class="form-group">
        <label for="" class="col-sm-3 control-label">[[ID]] <i class="icon-asterisk smaller-60 smaller-60"></i></label>
        <div class="col-sm-8"><input type="text" name="form_id" class="form-control" value="{$form_id}"></div>
      </div>
      <div class="form-group">
        <label for="" class="col-sm-3 control-label">[[Title]]</label>
        <div class="col-sm-8"><input type="text" name="title" class="form-control" value="{$form_id}"></div>
      </div>
      <div class="form-group">
        <label for="" class="col-sm-3 control-label">[[Category]] <i class="icon-asterisk smaller-60 smaller-60"></i></label>
        <div class="col-sm-8">
          <select class="form-control" name="category_sid">
            {foreach from=$categories item=category}
              <option value="{$category.sid}" {if isset($category_sid) && $category_sid == $category.sid}selected{/if}>{$category.id}</option>
            {/foreach}
          </select>
        </div>
      </div>
      <div class="clearfix form-actions">
        <input type="hidden" name="action" value="add">
        {CSRF_token}
        <input type="hidden" name="application_id" value="{$application_id}">
        <input type="submit" value="[[Add:raw]]" class="btn btn-default">
      </div>
  	</form>
  </div>
</div>
