{require component="js" file="Event.js"}
<script type="text/javascript">
	var fieldsToLoad = 0;
	var fieldsToRemove = 0;
	var refineSearchFieldsLoader = '{page_path module='classifieds' function='refine_search_fields'}';
	jQuery(document).ready(function () {
		
		{*initHiddenOptions*}
		$('.menu.accordion .content.Options').each(function(){
			$(this).find('.optionDisplayModeSwitch.expand').hide();
			$(this).find('.optionDisplayModeSwitch.collapse').hide();

			if ($(this).find('li').length >=3) {
				$(this).find('li:nth-child(1n+4)').hide();
				$(this).find('.optionDisplayModeSwitch.expand').show();
			}
			var parent = $(this);
			parent.find('.optionDisplayModeSwitch.expand a').click(function () {
				parent.find('.optionDisplayModeSwitch.expand').hide();
				parent.find('li:nth-child(1n+4)').slideDown('fast');
				parent.find('.optionDisplayModeSwitch.collapse').show();
				return false;
			});
			parent.find('.optionDisplayModeSwitch.collapse a').click(function () {
				parent.find('.optionDisplayModeSwitch.collapse').hide();
				parent.find('li:nth-child(1n+4)').slideUp('fast');
				parent.find('.optionDisplayModeSwitch.expand').show();
				return false;
			});
		});

		{*Resetting Form*}
		$(".ResetSearchCriteria").click(function(e){
			e.preventDefault();
			$(".refineSearchForm form")[0].reset();
            $(".refineSearchForm form input[type='text']").each(function(){
                $(this).val(null);
            });
            $(".refineSearchForm form textarea").each(function(){
                $(this).val(null);
            });
            $(".refineSearchForm form input[type='checkbox']").each(function(){
                $(this).prop('checked', false);
            });
            $(".refineSearchForm form input[type='radio']").each(function(){
                $(this).prop('checked', false);
            });
			$(".refineSearchForm form select").each(function(){
				$(this).prop('selectedIndex',0);
			});
			for (index = 0; index < resetObserver.length; ++index) {
				resetObserver[index].clearAllSelection();//The resetObserver array is filled with objects in other templates (F.E.: refine_search\integer.tpl)
			}
			$(".refineSearchForm input").first().change();
		});

		var numberOfAjaxConnection = 0;
		{*When some of the form elements is changed a request to server is sent and the "search results" page part is reloaded via AJAX*}
		$(".refineSearchForm form").off("change").on("change", ":input", function(){
			for (var i = 0; i < onFormSubmitObserver.length; i++) {
				onFormSubmitObserver[i].perform();//Performing actions before form submitting(F.E.:refine_search\keywords.tpl)
			}

			if ($(this).data("ajaxReload") == false)
			{
				location.href = '{$GLOBALS.site_url}{$GLOBALS.current_page_uri}?' + $(".refineSearchForm form *").filter(":input[name!=raw_output]").serialize() + '&page=1';
				return;
			}
			else
			{
				displayLoadingAnimation();
				++numberOfAjaxConnection;
				var url = '{$GLOBALS.site_url}{$GLOBALS.current_page_uri}?' + $(".refineSearchForm form").serialize() + '&page=1';
				$.get(url, onSearchResultsLoad);

				//Hash for currency converter
				location.hash = '?action=restore&searchId={$listing_search.id}';
			}

			$(this).trigger('blur');

			function onSearchResultsLoad(data) {
				$(".listingSearchResultsPage").replaceWith($("<div>").html(data).find( ".listingSearchResultsPage" ));
				if (--numberOfAjaxConnection == 0)//If there is no connections any more
					hideLoadingAnimation();
				{extension_point name='modules\classifieds\apps\FrontEnd\IJavaScriptCodeOnSearchResultsRefined'}
			}
		});
		$(".refineSearchForm form").on("submit", function(e){
			e.preventDefault();
		});

		$(".refineSearchForm").on('click', '.header.AddMoreCriteria a', function(e){
			e.preventDefault();
			$(".addMoreCriteriaPopUp").dialog("open");
		});
		$(".addMoreCriteriaPopUp").dialog(
					{
						autoOpen: false,
						modal: true,
						width: '500px',
						position: ['center', 20],
						open: function(event, ui){
							$('.addMoreCriteriaPopUp').html('');
							$('.addMoreCriteriaPopUp').load(
									refineSearchFieldsLoader,
									{
										action: 'show_available_fields',
										form_fields: formFields,
										search_id: '{$listing_search.id}',
										category_sid: '{$category_sid}'
									}
							);
						},
						buttons: {
							"[[Apply:raw]]":
								{
									'text': '[[Apply:raw]]',
									'class': 'centerButton',
									click: function () { //Pop up has one button "Add"
										fieldsToLoad = 0;
										fieldsToRemove = 0;
										displayLoadingAnimation();
										$('.addCriteriaFields input[type^="checkbox"]:not(:checked)').each(function(){
											fieldsToRemove++;
											removeFieldFromForm($(this).attr('name'))
										});
										$('.addCriteriaFields input[type^="checkbox"]:checked').each(function(){
											fieldsToLoad++;
											addFieldToForm($(this).attr('name'))
										});
										$(this).dialog("close");
										if (fieldsToLoad <= 0 && fieldsToRemove <= 0)
											hideLoadingAnimation();
                                        WFWEvents.publish('on_form_update');
									}
								}
						}
					}
		);
	});

	function removeFieldFromForm(fieldId)
	{
		if ($('.tab.'+fieldId).length == 0 && $('.option.'+fieldId).length == 0)
		{
			fieldsToRemove--;
			return;
		}
		if($('.tab.'+fieldId).length != 0)
		{
			$('.tab.'+fieldId).remove();
			$('.content.'+fieldId).remove();
			$('.ui-dialog select[name^='+fieldId+']').parents('.ui-dialog').remove();
		}
		else if($('.option.'+fieldId).length != 0)
		{
			$('.option.'+fieldId).remove();
		}
		fieldsToRemove--;
		formFields.splice(formFields.indexOf(fieldId),1);
	}

	function addFieldToForm(fieldId)
	{
		if ($('.tab.'+fieldId).length != 0 || $('.option.'+fieldId).length != 0)
		{
			fieldsToLoad--;
			return;
		}
		$.ajax({
				url: refineSearchFieldsLoader,
				data: {
						'action': 'get_field_by_id',
						'field_id': fieldId,
						'search_id': '{$listing_search.id}',
						'category_sid': '{$category_sid}'
				},
				dataType: 'json',
				success: function(data){
					if (data.type == 'boolean')
					{
						$('.refineSearchForm .menu.accordion .tab.Options').addClass('opened');
						$('.refineSearchForm .menu.accordion .tab.Options').show();
						$('.refineSearchForm .menu.accordion .content.Options').show();
						$('.refineSearchForm .menu.accordion .content.Options ul').append(data.field);

						// for theme irealty bootstrap based
						$('.refineSearchForm #Options .items').append(data.field);
					}
					else
					{
						$('.refineSearchForm .header.AddMoreCriteria').before(data.field);
						$('.refineSearchForm .menu.accordion .content.' + data.fieldId).hide();
					}
					fieldsToLoad--;
					formFields.push(data.fieldId);
					if (fieldsToLoad <= 0 && fieldsToRemove <= 0)
						hideLoadingAnimation();
				}
			});
	}

	function displayLoadingAnimation()
	{
		$('body').append('<div class="loadingAnimationModalLayer"></div>');
		$('.loadingAnimation').fadeIn('fast');
	}
	function hideLoadingAnimation()
	{
		$('.loadingAnimationModalLayer').remove();
		$('.loadingAnimation').fadeOut('slow');
	}
</script>
