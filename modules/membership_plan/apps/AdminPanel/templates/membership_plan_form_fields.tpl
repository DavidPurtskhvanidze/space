{display_error_messages}

<div class="form membershipPlan">
	<div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60 "></i>) are mandatory]]</div>
	<form class="form form-horizontal" method="post" enctype="multipart/form-data">
		<input type="hidden" name="action" value="save_membership_plan"/>
		<input type="hidden" name="sid" value="{$membershipPlanSID}"/>
		{foreach from=$formFields item=formField}
			<div class="form-group" data-field-id="{$formField.id}">
				<label class="col-sm-3 control-label">
					[[$formField.caption]]
					{if $formField.id == 'subscription_period'}
						<small><br/>[[Leave empty for perpetual <br/> subscription plan]]</small>
					{/if}
					{if $formField.id == 'classifieds_listing_amount'}
						<small><br/>[[Leave empty for unlimited <br/> listings amount]]</small>
					{/if}
					{if $formField.is_required}<i class="icon-asterisk smaller-60 ">{/if}</i>
				</label>

				<div class="col-sm-6 input">
					{if $formField.id == 'description'}
						{input property=$formField.id template='textarea.tpl'}
					{else}
						{input property=$formField.id}
					{/if}
				</div>
			</div>
		{/foreach}
		<div class="clearfix form-actions">
			<input type="submit" value="[[Save:raw]]" class="btn btn-default"/>
		</div>
	</form>
</div>
