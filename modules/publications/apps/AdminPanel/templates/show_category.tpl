<div class="breadcrumbs">
	<ul class="breadcrumb">
		<li><a href="{page_path id='publications'}">[[Publications]]</a> &gt; [[$category.title]]</li>
	</ul>
</div>
<div class="page-content">
	<div class="page-header">
		<h1 class="lighter">[[$category.title]]</h1>
	</div>


	{module name="site_pages" function="register_page_link" caption="section" pageInfo=$pageInfo}

	{display_error_messages}

	<h3 class="headerBlue">[[Edit Section]]</h3>

	{include file="publication_category_form.tpl" action="edit"}

	<a class="itemControls addArticle btn btn-link" href="{page_path module='publications' function='add_article'}?category_sid={$category.sid}" title="[[Add Article:raw]]">
		[[Add new article to this section]]
	</a>
	<br/>

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
                        <a href="?category_sid={$category.sid}&amp;sortingField={$renderingField}&amp;sortingOrder={if $REQUEST.sortingField == $renderingField}{$sortedColumnHrefParam}{else}ASC{/if}"
                           {if $REQUEST.sortingField == $renderingField}class="columnSorted {$REQUEST.sortingOrder|strtolower}"{/if}>
                            [[Article]]
                        </a>
                    </th>
                    <th>
                        {$renderingField='date'}
                        <a href="?category_sid={$category.sid}&amp;sortingField={$renderingField}&amp;sortingOrder={if $REQUEST.sortingField == $renderingField}{$sortedColumnHrefParam}{else}ASC{/if}"
                           {if $REQUEST.sortingField == $renderingField}class="columnSorted {$REQUEST.sortingOrder|strtolower}"{/if}>
                            [[Date]]
                        </a>
                    </th>
                    <th colspan=2>[[Actions]]</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$articles item=article name=foreach}
                    <tr class="{cycle values="odd,even"}">
                        <td>{$article.title}</td>
                        <td>[[$article.date]]</td>
                        <td><a class="itemControls edit" href="{page_path module='publications' function='edit_article'}?category_sid={$category.sid}&article_sid={$article.sid}" title="[[Edit:raw]]">[[Edit]]</a></td>
                        <td><a class="itemControls delete" href="?action=delete_article&category_sid={$category.sid}&article_sid={$article.sid}" onclick="return confirm('[[Are you sure you want to delete this article?:raw]]')" title="[[Delete:raw]]">[[Delete]]</a>
                        </td>
                    </tr>
                {/foreach}
            </tbody>

		</table>
	</div>
</div>
