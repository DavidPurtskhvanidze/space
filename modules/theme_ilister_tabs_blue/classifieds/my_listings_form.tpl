<form method="get" action="">
	<fieldset class="formFields">
		<ul>
			<li class="fieldControl ID">
				<label for="id">[[FormFieldCaptions!Listing ID]]</label>
				{search property="id"}
			</li>
			<li class="fieldControl Category">
				<label for="category">[[FormFieldCaptions!Category]]</label>
				{search property=$form_fields.category_sid.id template="category_tree_noredirect_no_digits.tpl"}
			</li>
			<li class="fieldControl Keywords">
				<label for="keywords">[[FormFieldCaptions!Keywords]]</label>
				{search property="keywords" template="string_with_autocomplete.tpl"}
			</li>
			<li class="fieldControl ActivationDate">
				<label for="activation_date">[[FormFieldCaptions!Activation Date]]</label>
				{search property="activation_date"}
			</li>
		</ul>
	</fieldset>
	<fieldset class="formControls">
		<input type="hidden" name="action" value="search" />
		<input type="submit" class="button" value="[[Search:raw]]" />
	</fieldset>
</form>
