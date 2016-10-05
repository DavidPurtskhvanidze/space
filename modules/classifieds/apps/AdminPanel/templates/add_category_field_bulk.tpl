<div class="page-content">
	<div id="addBulk">
		<div class="alert alert-warning">
			[[Please keep in mind that there are more field properties available than listed below. You can configure these properties in the settings of each field after its creation, like list values for list-type fields.]]
		</div>
		{display_error_messages}
		<div class="row">
			<div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60"></i>) are mandatory]]</div>

			<form method="post" class="form-horizontal" role="form" action="" id="bulkForm">
                {CSRF_token}
				<input type="hidden" name="action" value="save"/>
				<input type="hidden" name="category_sid" value="{$category_sid}"/>
				<input type="hidden" name="raw_output" value="{$smarty.request.raw_output}"/>

				<div class="row">
					<div class="col-xs-12 usersBlock">
						<table class="items sortable table table-striped table-hover">
							<thead>
							<tr class="head">
								<th>#</th>
								<th>[[ID]] <i class="icon-asterisk smaller-60"></i></th>
								<th>[[Caption]] <i class="icon-asterisk smaller-60"></i></th>
								<th>[[Type]] <i class="icon-asterisk smaller-60"></i></th>
								<th>[[Required]]</th>
								<th></th>
							</tr>
							</thead>
							{foreach $fieldsInfo as $key => $fieldInfo}
								<tr data-item-key="{$key}" data-item-index="{$fieldInfo@iteration}">
									<td class="index">{$fieldInfo@iteration}</td>

									<td>
										{include file="field_types^input/string.tpl" id="fields[{$key}][id]" value=$fieldInfo.id hasError=!empty($validationErrors.$key.id) error=$validationErrors.$key.id}
									</td>
									<td>
										{include file="field_types^input/string.tpl" id="fields[{$key}][caption]" value=$fieldInfo.caption hasError=!empty($validationErrors.$key.caption) error=$validationErrors.$key.caption}
									</td>
									<td>
										{include file="field_types^input/list.tpl" list_values=$listingFieldTypes id="fields[{$key}][type]" value=$fieldInfo.type hasError=!empty($validationErrors.$key.type) error=$validationErrors.$key.type}
									</td>
									<td>
										{include file="field_types^input/boolean.tpl" id="fields[{$key}][is_required]" value=$fieldInfo.is_required hasError=!empty($validationErrors.$key.is_required) error=$validationErrors.$key.is_required}
									</td>
									<td>
										<div class="delete btn btn-default">x</div>
									</td>
								</tr>
							{/foreach}
							<tr>
								<td colspan="6" class="center">
									<div class="addMore btn btn-default">+</div>
									<br/>
								</td>
							</tr>
							<tr>
								<td colspan="6">
									<button type="submit" class="btn btn-default">[[Save:raw]]</button>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {

		$(".items").on("click", ".delete", function () {
			$(this).closest("*[data-item-key]").remove();
		});

		$(".addMore").click(function () {
			var $fieldFormRows = $("*[data-item-key]");

			// defining maximum key and index
			var maxKey = 0;
			var maxIndex = 0;
			$fieldFormRows.each(function () {
				if ($(this).data('itemKey') > maxKey)
				{
					maxKey = $(this).data('itemKey');
				}

				if ($(this).data('itemIndex') > maxIndex)
				{
					maxIndex = $(this).data('itemIndex');
				}
			});

			// key and index for the new form row
			var newKey = maxKey + 1;
			var newIndex = maxIndex + 1;

			var $clone = $fieldFormRows.last().clone();

			// set the correct input names and reset values
			$(":input", $clone).each(function () {
				$(this)
						.val("")
						.attr('name', $(this).attr('name').replace('[' + maxKey + ']', '[' + newKey + ']'))
						.removeClass('has-error tooltip-error')
						.removeAttr('data-rel')
						.removeAttr('data-placement')
						.removeAttr('title');
			});

			// set the correct index
			$(".index", $clone).text(newIndex);

			// set the correct data attributes
			$clone
					.attr('data-item-key', newKey)
					.attr('data-item-index', newIndex);

			$clone.insertAfter($fieldFormRows.last())
		})
	});
</script>

