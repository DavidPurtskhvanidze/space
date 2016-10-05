<div class="userSearchTab">
	<form method="get" action="{page_path id='users_search'}" >
		<fieldset class="formFields">
			<ul>
				<li class="fieldControl textBox DealershipName inputValuePlaceholder" rel="[[FormFieldCaptions!Dealership Name]]">
					{search property="DealershipName" template="string_with_autocomplete.tpl"}
				</li>
				<li class="fieldControl textBox City inputValuePlaceholder" rel="[[FormFieldCaptions!City]]">
					{search property="City"}
				</li>
				<li class="fieldControl selectBox State">
					{search property="State"}
				</li>
				<li class="fieldControl selectBox ZipCode">
					{search property="ZipCode" template="geo.distance.tpl"}
				</li>
				<li class="fieldControl textBox ZipCode inputValuePlaceholder" rel="[[FormFieldCaptions!Of Zip]]">
					{search property="ZipCode" template="geo.location.tpl"}
				</li>
			</ul>
		</fieldset>
		<fieldset class="formControls">
			<input type="hidden" name="action" value="search" />
			<input type="submit" class="button" value="[[Search:raw]]" />
		</fieldset>
	</form>
</div>
