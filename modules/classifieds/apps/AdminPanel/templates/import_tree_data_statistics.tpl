<div class="ipBlockList">
	<div class="breadcrumbs">
         <ul class="breadcrumb">
            <li>
                {foreach from=$ancestors item=ancestor}
                    <a href="{page_path id='edit_category'}?sid={$ancestor.sid}">[[$ancestor.caption]]</a> &gt;
                {/foreach}
                {if $field.category_sid}
                    <a href="{page_path id='edit_category_field'}?sid={$field_sid}">[[$field.caption]]</a> &gt;
                {else}
                    <a href="{page_path id='edit_listing_field'}?sid={$field_sid}">[[$field.caption]]</a> &gt;
                {/if}
                <a href="{page_path id='edit_listing_field_edit_tree'}?field_sid={$field_sid}">[[Edit Tree]]</a> &gt;
                [[Import Tree Data]]
            </li>
         </ul>
	</div>
    <div class="page-content">
        <div class="page-header">
            <h1 class="lighter">[[Import Tree Data]]</h1>
        </div>            
        <div class="row">
            [[Number of imported items:]] {$count}
        </div>
    </div>
</div>
