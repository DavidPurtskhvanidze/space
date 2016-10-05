<div class="userSearchPage">
	<h1>[[Find A Dealer]]</h1>
	<form method="get" action="{page_path id='users_search'}" >
		<fieldset class="formFields">
			<ul>
				<li class="fieldControl DealershipName">
					<label class="labelDealershipName" for="DealershipName">[[FormFieldCaptions!Dealership Name]]</label>
					<div class="fieldDealershipNameContainer">
						{search property="DealershipName" template="string_with_autocomplete.tpl"}
					</div>
				</li>
				<li class="fieldGroupCaption Location">
					[[Location]]
				</li>
				<li class="fieldControl City">
					<label for="City">[[FormFieldCaptions!City]]</label>
					{search property="City"}
				</li>
				<li class="fieldControl State">
					<label for="State">[[FormFieldCaptions!State]]</label>
					{search property="State"}
				</li>
				<li class="fieldControl ZipCode">
					<label>[[FormFieldCaptions!Search Within]]</label>
					{search property="ZipCode" template="geo.distance.tpl"}
				</li>
				<li class="fieldControl ZipCode">
					<label>[[FormFieldCaptions!Of Zip]]</label>
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
