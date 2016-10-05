<div class="keywordField">
	{if $GLOBALS.settings.autocomplete_enable_in_keyword_search}
		{search property=keywords template="string_with_autocomplete.tpl" parameters=['element_id_prefix'=>'advancedSearch','preselection_fields'=>['category_sid','MakeModel']]}
	{else}
		{search property=keywords}
	{/if}
</div>
<script type="text/javascript">
	$(function () {
		if ($('.refineSearchForm .keywordField input[type="text"]').val() == "") {
			$('.refineSearchForm .keywordField input[type="text"]').val("[[Keywords]]...").addClass("hintText");
		}
		$('.refineSearchForm .keywordField input[type="text"].hintText').on('focusin', function () {
			$(this).val("").removeClass("hintText").off('focusin');
		});

		var onSubmitActor = new Object();
		onSubmitActor.perform = function () {
			if ($('.refineSearchForm .keywordField input[type="text"]').val() == "[[Keywords]]...") {
				$('.refineSearchForm .keywordField input[type="text"]').val("");
			}
		};

		onFormSubmitObserver[onFormSubmitObserver.length] = onSubmitActor;

	})
</script>
