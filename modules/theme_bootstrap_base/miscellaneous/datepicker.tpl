{require component="jquery" file="jquery.js"}
{require component="twitter-bootstrap" file="css/bootstrap.min.css"}
{require component="twitter-bootstrap" file="js/bootstrap.min.js"}

{require component="bootstrap-datepicker" file="css/bootstrap-datetimepicker.min.css"}
{require component="bootstrap-datepicker" file="js/moment.js"}
{require component="bootstrap-datepicker" file="js/bootstrap-datetimepicker.min.js"}

{capture assign="datePickerDateFormat"}{i18n->getRawDateFormat|replace:"%m":"MM"|replace:"%d":"DD"|replace:"%Y":"YYYY"}{/capture}

<script type="text/javascript">
	$(function () {
		$('input.datepicker')
				.data('dateFormat', '{$datePickerDateFormat}')
				.datetimepicker({
					language: '{i18n->getCurrentLanguage}',
					pickTime: false
				});
	});
</script>
