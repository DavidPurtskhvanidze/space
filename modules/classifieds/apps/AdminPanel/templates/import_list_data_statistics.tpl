<div class="importListValues">
	<div class="breadcrumbs">
		{foreach from=$ancestors item=ancestor}
			<a href="{page_path id='edit_category'}?sid={$ancestor.sid}">[[$ancestor.caption]]</a> &gt;
		{/foreach}
		{if $field.category_sid}
			<a href="{page_path id='edit_category_field'}?sid={$field_sid}">[[$field.caption]]</a> &gt;
		{else}
			<a href="{page_path id='edit_listing_field'}?sid={$field_sid}">[[$field.caption]]</a> &gt;
		{/if}
		<a href="{page_path id='edit_listing_field_edit_list'}?field_sid={$field_sid}">[[Edit List]]</a> &gt;
		[[Import List Data]]
	</div>

	<h1>[[Import List Data]]</h1>

	[[Number of imported items:]] {$count}
</div>
