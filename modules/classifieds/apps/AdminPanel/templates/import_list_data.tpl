<div class="importListValues">
    <div class="breadcrumbs">
        <ul class="breadcrumb">
            <li>{foreach from=$ancestors item=ancestor}
                    <a href="{page_path id='edit_category'}?sid={$ancestor.sid}">[[$ancestor.caption]]</a> &gt;
                {/foreach}
                {if $field.category_sid}
                    <a href="{page_path id='edit_category_field'}?sid={$field_sid}">[[$field.caption]]</a> &gt;
                {else}
                    <a href="{page_path id='edit_listing_field'}?sid={$field_sid}">[[$field.caption]]</a> &gt;
                {/if}
                <a href="{page_path id='edit_listing_field_edit_list'}?field_sid={$field_sid}">[[Edit List]]</a> &gt;
                [[Import List Data]]
            </li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <h1 class="lighter">[[Import List Data]]</h1>
        </div>
        <div class="hint">
            [[Please note that for the Excel format you can import .xls files of the Ms Office versions 95, 97, 2000 and 2003; and you cannot import .xlsx files.]]
        </div>
    </div>
</div>
