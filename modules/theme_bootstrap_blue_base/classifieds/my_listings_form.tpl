<form role="form">
	<input type="hidden" name="action" value="search" />
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="id">[[FormFieldCaptions!Listing ID]]</label>
                {search property="id"}
            </div>
            <div class="form-group">
                <label for="category">[[FormFieldCaptions!Category]]</label>
                {search property=$form_fields.category_sid.id template="category_tree_noredirect_no_digits.tpl"}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="keywords">[[FormFieldCaptions!Keywords]]</label>
                {search property="keywords" template="string_with_autocomplete.tpl"}
            </div>
            <div class="form-group">
                <label for="activation_date" class="control-label">[[FormFieldCaptions!Activation Date]]</label>
                {search property="activation_date" template="date_inline.tpl" hideLabels=true placeholderFrom="[[Activation Date]]: [[from]]" placeholderTo="[[to]]"}
            </div>
        </div>
    </div>
    <hr class="hidden-sm hidden-xs"/>
    <div class="space-20"></div>
	<div class="form-group text-center">
        <button type="submit" class="btn btn-orange btn1">[[Search:raw]]</button>
	</div>
</form>
