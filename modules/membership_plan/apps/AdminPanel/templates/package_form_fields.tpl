<div class="page-content">
    <div class="row">
    {display_error_messages}

    <div class="alert alert-info">[[To enable any of the options free of charge under this package, enter 0 (zero) in the relevant price input field.]]</div>

    <div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60  smaller-60 "></i>) are mandatory]]</div>
    <form method="post" class="form-horizontal" role="form">
        {CSRF_token}
        <input type="hidden" name="action" value="save_package">
        <input type="hidden" name="sid" value="{$packageSID}" />
        <input type="hidden" name="class_name" value="{$className}">
        <input type="hidden" name="membership_plan_sid" value="{$membershipPlan.sid}">
        {foreach from=$formFields item=formField}
            <div class="form-group {$formField.id}">
                <label class="col-sm-3 control-label">
                  [[$formField.caption]]
                  {if $formField.is_required}<i class="icon-asterisk smaller-60   smaller-60  "></i>{/if}
                </label>
                <div class="col-sm-8">
                    {if $formField.id == 'description'}
                        {input property=$formField.id template='textarea.tpl'}
                    {else}
                        {input property=$formField.id}
                        {if $formField.id == 'video_allowed'}
                            <div class="help-block">[[The feature works only if you've set up OAuth & OpenID Configurations for Google Account and  Social Networks -> Upload Video to YouTube]]</div>
                        {/if}    
                    {/if}
                    
                </div>
            </div>
        {/foreach}
        <div class="clearfix form-actions">
           <input type="submit" value="[[Save:raw]]" class="btn btn-default" />
        </div>
    </form>
</div>
</div>
{require component="jquery" file="jquery.js"}
<script>
	function toggleRows($element) {
		var featureAllowedRadioButtonName = new String($element.prop("name"));
		var featureId = featureAllowedRadioButtonName.replace("_allowed", "");
		var $correspondingRows = $("tr[class^=" + featureId + "]:not([class$=_allowed])");
		if ($element.prop("checked")) {
			$("input", $correspondingRows).prop("disabled", false);
		}
		else {
			$("input", $correspondingRows).prop("disabled", true);
		}
	}
	$(function () {
		$("div.make-switch")
				.on('switch-change', function (e, data) {
					var $element = $(data.el), value = data.value
					toggleRows($element)
				})
				.each(function () {
					toggleRows($("input:checkbox", $(this)))
				})
	});
</script>
