<div class="breadcrumbs">
    <ul class="breadcrumb">
        <li>[[Static Content]]</li>
    </ul>
</div>
<div class="page-content">
    <div class="page-header">
        <h1 class="lighter">[[Static Content]]</h1>
    </div>   
    
        <h4 class="blue">[[Add New Static Content]]</h4>
        
        {display_error_messages}

        <form method="post" class="form-horizontal" role="form" action="" enctype="multipart/form-data">
            {CSRF_token}
            <input type= "hidden" name= "action" value= "add">
            <div class="form-group">
                <label class="col-sm-1 control-label">
                  [[ID]]
                </label>
                <div class="col-sm-8">
                    <input type="text" name="pageid" value="{$REQUEST.pageid}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label">
                  [[Name]]
                </label>
                <div class="col-sm-8">
                    <input type="text" name="name" value="{$REQUEST.name}" class="form-control" onFocus="JavaScript: if (!this.value) this.value=pageid.value;">
                </div>
            </div>
            <div class="clearfix form-actions">
                <input type="submit" value="[[Add:raw]]" class="btn btn-default">
            </div>
        </form>
        <div class="row">
            <div class="col-xs-8 usersBlock">
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
                                [[Name]]
                            </a>
                        </th>
                        <th>[[Actions]]</th>
                    </tr>
                    <tbody>
                        {foreach from=$pages item=page key=id name=foreach}
                            <tr>
                                <td>{$id}</td>
                                <td>[[{$page.name}]]&nbsp;</td>
                                <td>
																	<a class="itemControls edit btn btn-xs btn-info" href="?action=edit&pageid={$id}" title="[[Edit:raw]]">
																		<i class="icon-edit"></i>
																	</a>
                                	<a class="itemControls delete btn btn-xs btn-danger" href="?action=delete&pageid={$id}" onclick="return confirm('[[Are you sure you want to delete this page?:raw]]')" title="[[Delete:raw]]">
																		<i class="icon-trash"></i>
                                	</a>
																</td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
