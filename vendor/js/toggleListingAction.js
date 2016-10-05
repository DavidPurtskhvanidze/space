function toggleListingAction(checkbox, addListingPageUrl, removeListingPageUrl)
{
	var $checkbox = $(checkbox);
	$checkbox.prop('disabled', true);
	if ($checkbox.prop('checked'))
	{
		$.ajax({
			url: addListingPageUrl,
			data: {listing_id: $($checkbox).attr('value'), getCount: 1},
			success: function(message){
				$checkbox.prop('checked', true);
                $('.manageSearchHeader:not(.opened)').trigger('click');
				if ($checkbox.attr('name') == "compareAddSwitch")
				{
					listingsInComparisonCounter = $(message).text();
                    $(".listingsCountInComparison").text(listingsInComparisonCounter);
                    $(".listingsInComparisonLink").fadeIn('fast');
				}
                else
                if ($checkbox.attr('name') == "saveAddSwitch")
                {
                    savedListingsCounter = $(message).text();
                    $(".savedListingsCount").text(savedListingsCounter);
                    $(".savedListingsLink").fadeIn('fast');
                }
            },
			error: function(XMLHttpRequest, textStatus, errorThrown){
				$checkbox.prop('checked', false);
				var $errorBox = $('<div class="errorNotification"></div>');
				$(document).click(function(){
					$errorBox.remove();
				});
				$(document).keyup(function(e){
					if (e.which == 27)
						$errorBox.remove();
				});
				$errorBox.html(XMLHttpRequest.responseText);
				$errorBox.appendTo($checkbox.parent().parent());
			},
			complete: function(){
				$checkbox.prop('disabled', false);
				if (typeof onCompleteActionComparison == 'function')
					onCompleteActionComparison($checkbox);
			}
		});
	}
	else
	{
		$.ajax({
			url: removeListingPageUrl,
			data: {listing_id: $($checkbox).attr('value'), getCount: 1},
			success: function(message){
				$checkbox.prop('checked', false);
                $('.manageSearchHeader:not(.opened)').trigger('click');
				if ($checkbox.attr('name') == "compareAddSwitch")
				{
					listingsInComparisonCounter = $(message).text();
                    $(".listingsCountInComparison").text(listingsInComparisonCounter);
                    if (listingsInComparisonCounter == 0) {
                        $(".listingsInComparisonLink").fadeOut('fast');
                    }
				}
                else
                if ($checkbox.attr('name') == "saveAddSwitch")
                {
                    savedListingsCounter = $(message).text();
                    $(".savedListingsCount").text(savedListingsCounter);
                    if (savedListingsCounter == 0) {
                        $(".savedListingsLink").fadeOut('fast');
                    }
                }
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
				$checkbox.prop('checked', true);
			},
			complete: function(){
				$checkbox.prop('disabled', false);
				if (typeof onCompleteActionComparison == 'function')
					onCompleteActionComparison($checkbox);
			}
		});
	}
}
