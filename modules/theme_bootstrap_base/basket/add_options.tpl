<div class="addOptions">
    <h3>[[Add More Options]]</h3>
	<form method="post" action="{page_path module='basket' function='add_options'}" class="respondInMainWindow">
		<ul class="list-unstyled">
		{foreach from=$options item="option"}
			<li>
				<input type="checkbox" name="option_ids[]" id="option_{$option.id}" value="{$option.id}" />
				<label for="option_{$option.id}">
					<span class="name">[[$option.name]]</span>
					<span class="price">{display_price_with_currency amount=$option.price}</span>
				</label>
			</li>
		{/foreach}
		</ul>
		<input type="hidden" name="return_uri" value="{$return_uri}" />
		<input type="hidden" name="listing_sid" value="{$listing_sid}" />
		<input type="hidden" name="action" value="add"/>
        {CSRF_token}
		<input type="submit" value="[[Add To Basket:raw]]" class="btn btn-default" />
	</form>
</div>
