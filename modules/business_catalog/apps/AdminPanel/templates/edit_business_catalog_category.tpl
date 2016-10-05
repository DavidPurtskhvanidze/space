<div class="breadcrumbs">
    <ul class="breadcrumb">
        <li><a href="?">[[Business Catalog]]</a> &gt; {$category.name}</li>
    </ul>
</div>

<div class="page-content">
    <div class="page-header">
        <h1 class="lighter">[[Edit Category]]</h1>
    </div>

<div class="row">
        {$createPageForThisCategoryLink}
        {display_error_messages}
        <form method="post" class="form-horizontal" role="form" action="" enctype="multipart/form-data">
            {CSRF_token}
            <input type="hidden" name="action" value="edit_category" />
            <input type="hidden" name="category_id" value="{$category.id}" />
            <div class="form-group">
                <label class="col-sm-2 control-label">
                  [[Category ID]]
                </label>
                <div class="col-sm-8">
                    <input type=text name="new_category_id" value="{$category.id}" class="form-control">
                </div>
            </div> 
            <div class="form-group">
                <label class="col-sm-2 control-label">
                  [[Category Name]]
                </label>
                <div class="col-sm-8">
                    <input type=text name="name" value="{$category.name}" class="form-control">
                </div>
            </div> 
            <div class="clearfix form-actions">
                <input type="submit" value="[[Save:raw]]" class="btn btn-default">
            </div>
        </form>

        <h3 class="headerBlue">[[Add Company]]</h1>
        <br />
        <form method="post" class="form-horizontal" role="form" action="" enctype="multipart/form-data">
            {CSRF_token}
            <input type="hidden" name="action" value="create_record" />
            <input type="hidden" name="category_id" value="{$category.id}" />
            <div class="form-group">
                <label class="col-sm-2 control-label">
                  [[New Company]]
                </label>
                <div class="col-sm-8">
                    <input type="text" name="record_name" class="form-control">
                </div>
            </div> 
            <div class="clearfix form-actions">
                <input type="submit" value="[[Add:raw]]" class="btn btn-default">
            </div>
        </form>
        <div class="row">
            <div class="col-xs-12">
                <table class="items sortable table table-striped table-hover">
                 <thead>
                  <tr class="head">
                    {if $REQUEST.sortingOrder=='ASC'}
                        {assign var="sortedColumnHrefParam" value="DESC"}
                    {elseif $REQUEST.sortingOrder=='DESC'}
                        {assign var="sortedColumnHrefParam" value="ASC"}
                    {/if}
                    <th>
                        {$renderingField='name'}
                        <a href="?category_id={$category_id}&amp;sortingField={$renderingField}&amp;sortingOrder={if $REQUEST.sortingField == $renderingField}{$sortedColumnHrefParam}{else}ASC{/if}"{if $REQUEST.sortingField == $renderingField}class="columnSorted {$REQUEST.sortingOrder|strtolower}"{/if}>
                            [[Companies]]
                        </a>
                    </th>                    
                    <th colspan=2>[[Actions]]</th>
                  </tr>
                 </thead>
                 <tbody>
                     {foreach from=$records item=record}
                        <tr class="{cycle values="odd,even"}">
                            <td>{$record.name}</td>
                            <td><a class="itemControls edit" href="?category_id={$category_id}&record_id={$record.id}" title="[[Edit:raw]]">[[Edit]]</a></td>
                            <td><a class="itemControls delete" href="?category_id={$category_id}&action=deleterecord&del_record_id={$record.id}" onclick="return confirm('[[Are you sure you want to delete these data?:raw]]')" title="[[Delete:raw]]">[[Delete]]</a></td>
                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
                    
   
