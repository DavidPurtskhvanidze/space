/**
  * jQuery Maxlength plugin 1.0.1
  *
  * http://www.anon-design.se
  *
  * Copyright (c) 2008 Emil Stjerneman <emil@anon-design.se>
  * 
  * Dual licensed under the MIT and GPL licenses:
  * http://www.opensource.org/licenses/mit-license.php
  * http://www.gnu.org/licenses/gpl.html
  */

(function($) 
{

	$.fn.maxlength = function(options)
	{
		var settings = jQuery.extend(
		{
			maxCharacters:		10, // characters limit
			status:				true, // true to show status indicator bewlow the element
			statusClass:		"status", // the class on the status div
			statusText:			"character left", // the status text
			notificationClass:	"notification",	// Will be added to the emement when maxlength is reached
			showAlert: 			false, // true to show a regular alert message
			alertText:			"You have typed to many characters." // Text in the alert message
		}, options );
		
		return this.each(function() 
		{
			
			var item = $(this);
			item.unbind('keyup');

			var charactersLength = $(this).val().length;

			if(!validateElement()) 
			{
				return false;
			}
			
			$(this).keyup( function(e) {
				charactersLength = item.val().length;
				checkChars();
			});	
			
			// Insert the status div
			if(settings.status) 
			{
				item.after($("<div/>").addClass(settings.statusClass).html('-'));
				updateStatus();
			}

			// remove the status div
			if(!settings.status) 
			{
				var removeThisDiv = item.next("div");
				
				if(removeThisDiv) {
					removeThisDiv.remove();
				}

			}

			function checkChars() 
			{
				var valid = true;
				
				// Too many chars?
				if(charactersLength >= settings.maxCharacters) 
				{
					// To may chars, set the valid boolean to false
					valid = false;
					// Add the notifycation class when we have to many chars
					item.addClass(settings.notificationClass);
					// Cut down the string
					item.val(item.val().substr(0,settings.maxCharacters));
					// Show the alert dialog box, if its set to true
					showAlert();
				} 
				else 
				{
					// Remove the notification class
					if(item.hasClass(settings.notificationClass)) 
					{
						item.removeClass(settings.notificationClass);
					}
				}

				if(settings.status)
				{
					updateStatus();
				}
			};

			function updateStatus()
			{
				var charactersLeft = settings.maxCharacters - charactersLength;
				
				if(charactersLeft < 0) 
				{
					charactersLeft = 0;
				}

				item.next("div").html(charactersLeft + " " + settings.statusText);
			};

			function showAlert() 
			{
				if(settings.showAlert)
				{
					alert(settings.alertText);
				}
			};

			function validateElement() {
				
				var ret = false;
				
				if(item.is('textarea')) {
					ret = true;
				} else if(item.filter("input[type=text]")) {
					ret = true;
				} else if(item.filter("input[type=password]")) {
					ret = true;
				}

				return ret;
			};

		});
	};
})(jQuery);
