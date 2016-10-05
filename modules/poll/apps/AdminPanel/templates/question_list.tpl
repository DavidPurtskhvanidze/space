<div class="breadcrumbs">
    <ul class="breadcrumb">
        <li>[[Polls]]</li>
    </ul>
</div>
<div class="page-content">
    <div class="page-header">
        <h1 class="lighter">[[Polls]]</h1>
    </div>    
        {display_error_messages}
         <h4 class="headerBlue">[[Add a New Question]]</h4>
        <form method="post" class="form-horizontal" role="form" action="" enctype="multipart/form-data">
            <input type="hidden" name="action" value="createquestion">
            {CSRF_token}

            <div class="form-group">
                <label class="col-sm-2 control-label">
                  [[Question]]
                </label>
                <div class="col-sm-8">
                    <input type="text" name="newquestion" value="" class="form-control">
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
                        {$renderingField='title'}
                        <a href="?sortingField={$renderingField}&amp;sortingOrder={if $REQUEST.sortingField == $renderingField}{$sortedColumnHrefParam}{else}ASC{/if}"{if $REQUEST.sortingField == $renderingField}class="columnSorted {$REQUEST.sortingOrder|strtolower}"{/if}>
                            [[Questions]]
                        </a>
                    </th>
                    <th colspan=2>[[Actions]]</th>                    
                    <tbody>
                    {foreach from=$questions item=question}
                       <tr class="{cycle values="odd,even"}" data-item-sid="{$question.id}">
                           <td>[[{$question.title}]]</td>
                          <td><a class="itemControls edit" href="?question_id={$question.id}" title="[[Edit:raw]]">[[Edit]]</a></td>
                            <td><a class="itemControls delete" href="?action=deletequestion&question_id={$question.id}" onclick="return confirm('[[Are you sure you want to delete this question?:raw]]')" title="[[Delete:raw]]">[[Delete]]</a></td>
                       </tr>
                   {/foreach}
                   </tbody>
                </table>
            </div>
        </div>
    </div>

