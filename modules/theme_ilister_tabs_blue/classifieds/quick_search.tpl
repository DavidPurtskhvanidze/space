<div class="quickSearchForm">
	<form action="{page_path id='search_results'}">
		<fieldset class="formFields">
			<ul>
				<li class="fieldControl textBox fieldKeywords inputValuePlaceholder" rel="[[FormFieldCaptions!Keywords]]">
					{search property="keywords" template="string_with_autocomplete.tpl"}
				</li>
				<li class="fieldControl selectBox Category">
					{search property="category_sid" template="category_tree_noredirect.tpl"}
				</li>
				<li class="fieldControl selectBox fieldSearchWithin">
					{search property="ZipCode" template="geo.distance.tpl"}
				</li>
				<li class="fieldControl textBox fieldOfZipCode inputValuePlaceholder" rel="[[FormFieldCaptions!of Zip]]">
					{search property="ZipCode" template="geo.location.tpl"}
				</li>
			</ul>
		</fieldset>
		<fieldset class="formControls">
			<input type="hidden" name="action" value="search" />
			<input type="hidden" name="category_sid[tree][]" value="{$category_sid}" />
			<input type="submit" class="button" value="[[Find:raw]]" /> 
			<a href="{page_path id='search'}">[[Advanced search]]</a>
		</fieldset>
	</form>
</div>
