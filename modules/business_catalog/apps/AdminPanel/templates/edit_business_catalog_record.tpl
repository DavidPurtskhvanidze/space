<div class="editBusinessCatalogRecord">
    <div class="breadcrumbs">
        <ul class="breadcrumb">
            <li><a href="?">[[Business Catalog]]</a>
		&gt; <a href="?category_id={$category.id}">{$category.name}</a>
		&gt; {$record.name}</li>
        </ul>
    </div>
<div class="page-content">
    <div class="page-header">
        <h1 class="lighter">[[Edit Company Profile]]</h1>
    </div>
        {display_error_messages}

	<form method="post" class="form-horizontal" role="form" action="" enctype="multipart/form-data">
        {CSRF_token}
		<input type="hidden" name="action" value="edit_record">
		<input type="hidden" name="record_id" value="{$record.id}">
        <div class="form-group">
            <label class="col-sm-2 control-label">
              [[Category]]
            </label>
            <div class="col-sm-8">
                <select name="category_id" class="form-control">
                    {foreach from=$categories item=categoryData}
                        {if $categoryData.id ne $category.id}
                            <option value="{$categoryData.id}">{$categoryData.name}</option>
                        {else}
                            <option value="{$categoryData.id}" selected="selected">{$categoryData.name}</option>
                        {/if}
                    {/foreach}
                </select>
            </div>
        </div> 
        <div class="form-group">
            <label class="col-sm-2 control-label">
              [[Company Name]]
            </label>
            <div class="col-sm-8">
                <input type=text name="name" value="{$record.name}" class="form-control">
            </div>
        </div> 
        <div class="form-group">
            <label class="col-sm-2 control-label">
              [[Brief Description]]
            </label>
            <div class="col-sm-8">
                {WYSIWYGEditor name="description" width="100%" height="300" type="ckeditor"}{$record.description}{/WYSIWYGEditor}
            </div>
        </div> 
        <div class="form-group">
            <label class="col-sm-2 control-label">
              [[Extended Description]]
            </label>
            <div class="col-sm-8">
                {WYSIWYGEditor name="full" width="100%" height="300" type="ckeditor"}{$record.full}{/WYSIWYGEditor}
            </div>
        </div> 
        <div class="form-group">
            <label class="col-sm-2 control-label">
              [[Address]]
            </label>
            <div class="col-sm-8">
                <textarea name="address" class="form-control">{$record.address}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">
              [[Phone]]
            </label>
            <div class="col-sm-8">
                <input type="text" name="phone" value="{$record.phone}" class="form-control">
            </div>
        </div> 
        <div class="form-group">
            <label class="col-sm-2 control-label">
              [[Fax]]
            </label>
            <div class="col-sm-8">
                <input type="text" name="fax" value="{$record.fax}" class="form-control">
            </div>
        </div> 
        <div class="form-group">
            <label class="col-sm-2 control-label">
              [[E-mail]]
            </label>
            <div class="col-sm-8">
                <input type="text" name="email" value="{$record.email}" class="form-control">
            </div>
        </div> 
        <div class="form-group">
            <label class="col-sm-2 control-label">
              [[Website]]
            </label>
            <div class="col-sm-8">
                <input type="text" name="url" value="{$record.url}" class="form-control">
            </div>
        </div> 
        <div class="clearfix form-actions">
            <input type="submit" value="[[Save:raw]]" class="btn btn-default">
        </div>
	</form>
</div>
</div>
