<form class="form-inline" role="form">
	<input type="hidden" name="action" value="search" />
	<div class="form-group">
		<label for="id" class="sr-only">[[FormFieldCaptions!Listing ID]]</label>
        {search property="id" placeholder="[[FormFieldCaptions!Listing ID]]"}
	</div>
	<div class="form-group">
		<label for="category" class="sr-only">[[FormFieldCaptions!Category]]</label>
        {search property=$form_fields.category_sid.id template="category_tree_noredirect_no_digits.tpl" placeholder="[[FormFieldCaptions!Category]]"}
	</div>
	<div class="form-group">
		<label for="keywords" class="sr-only">[[FormFieldCaptions!Keywords]]</label>
        {search property="keywords" template="string_with_autocomplete.tpl" placeholder="[[FormFieldCaptions!Keywords]]"}
	</div>
	<div class="form-group">
		<label for="activation_date" class="control-label sr-only">[[FormFieldCaptions!Activation Date]]</label>
		{search property="activation_date" template="date_inline.tpl" hideLabels=true placeholderFrom="[[Activation Date]]: [[from]]" placeholderTo="[[to]]"}
	</div>

	<div class="form-group">
        <button type="submit" class="btn btn-primary">[[Search:raw]]</button>
	</div>
</form>
