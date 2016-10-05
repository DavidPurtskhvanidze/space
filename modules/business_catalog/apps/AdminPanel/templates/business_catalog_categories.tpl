<div class="breadcrumbs">
    <ul class="breadcrumb">
        <li>[[Business Catalog]]</li>
    </ul>
</div>

<div class="page-content">
    <div class="page-header">
        <h1 class="lighter">[[Business Catalog]]</h1>
    </div>
        {display_error_messages}
        <h4 class="headerBlue">[[Add Category]]</h4>
        <form method="post" class="form-horizontal" role="form" action="" enctype="multipart/form-data">
        {CSRF_token}
		<input type="hidden" name="action" value="createcategory">
            <div class="form-group">
                <label class="col-sm-3 control-label">
                  [[Category ID]]
                </label>
                <div class="col-sm-8">
                    <input type="text" name="category_id" class="form-control">
                </div>
            </div>           
            <div class="form-group">
                <label class="col-sm-3 control-label">
                  [[New Category]]
                </label>
                <div class="col-sm-8">
                    <input type="text" name="category_name" onfocus="javascript: if (!this.value) this.value=category_id.value;" class="form-control">
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
                            {$renderingField='id'}
                            <a href="?sortingField={$renderingField}&amp;sortingOrder={if $REQUEST.sortingField == $renderingField}{$sortedColumnHrefParam}{else}ASC{/if}"{if $REQUEST.sortingField == $renderingField}class="columnSorted {$REQUEST.sortingOrder|strtolower}"{/if}>
                                [[ID]]
                            </a>
                        </th>
                        <th>
                            {$renderingField='name'}
                            <a href="?sortingField={$renderingField}&amp;sortingOrder={if $REQUEST.sortingField == $renderingField}{$sortedColumnHrefParam}{else}ASC{/if}"{if $REQUEST.sortingField == $renderingField}class="columnSorted {$REQUEST.sortingOrder|strtolower}"{/if}>
                                [[Categories]]
                            </a>
                        </th>
                        <th>[[Actions]]</th>
                  </tr>
                 </thead>
                 <tbody>
                     {foreach from=$categories item="category"}
                        <tr class="{cycle values="odd,even"}">
                            <td>{$category.id}</td>
                            <td>[[{$category.name}]]</td>
                            <td>
                                <div class="btn-group actionList">
                                    <a class="itemControls edit btn btn-xs btn-info" href="?category_id={$category.id}" title="[[Edit:raw]]">
                                        <i class="icon-edit bigger-120"></i>
                                    </a>
                                    <a class="itemControls delete btn btn-xs btn-danger" href="?action=deletecategory&del_category_id={$category.id}" onclick="return confirm('[[Are you sure you want to delete this category?:raw]]')" title="[[Delete:raw]]">
                                        <i class="icon-trash bigger-120"></i>
                                    </a>
                                </div>

                            </td>
                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

            
            
            
            

