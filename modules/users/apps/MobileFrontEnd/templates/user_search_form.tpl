{if !isset($REQUEST.action)}
	<div class="userSearchPage">
		<h1>[[Search Sellers]]</h1>
		{if $form_fields|@count}
		<form method="get" action="" >
			<fieldset>
				{foreach from=$form_fields item=form_field}
					<div class="formField {$form_field.id}">
						<label for="{$form_field.id}">[[$form_field.caption]]</label>
						{search property=$form_field.id}
					</div>
				{/foreach}
			</fieldset>
			<fieldset class="formControls">
				<input type="hidden" name="action" value="search" />
				<input type="submit" value="[[Search:raw]]" />
			</fieldset>
		</form>
		{/if}
	</div>
{/if}
