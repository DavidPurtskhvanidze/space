<form class="full-width-inline-form" role="form">
    <input type="hidden" name="action" value="search" />
    <div class="form-group full-width-item">
        <label for="id" class="control-label">[[FormFieldCaptions!Listing ID]]</label>
        {search property="id" placeholder="[[FormFieldCaptions!Listing ID]]"}
    </div>
    <div class="form-group full-width-item">
        <label for="category" class="control-label">[[FormFieldCaptions!Category]]</label>
        {search property=$form_fields.category_sid.id template="category_tree_noredirect_no_digits.tpl" placeholder="[[FormFieldCaptions!Category]]"}
    </div>
    <div class="form-group full-width-item">
        <label for="keywords" class="control-label">[[FormFieldCaptions!Keywords]]</label>
        {search property="keywords" template="string_with_autocomplete.tpl" placeholder="[[FormFieldCaptions!Keywords]]"}
    </div>
    {search property="activation_date" template="date_inline_my_listing.tpl" hideLabels=true placeholderFrom="[[Activation Date]]: [[from]]" placeholderTo="[[to]]"}

    <div class="form-group full-width-item ver-align-bottom">
        <button type="submit" class="default-button wb">[[Search:raw]]</button>
    </div>
</form>
