<div class="page-content">
	<div id="addBulk">
		<div class="alert alert-warning">
			[[Please keep in mind that there are more category properties available than listed below. You can configure these properties in the settings of each category after its creation, like Listing Caption or category-specific templates.]]
		</div>
		{display_error_messages}
		<div class="row">
			<div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60"></i>) are mandatory]]</div>

			<form method="post" class="form-horizontal" role="form" action="" id="bulkForm">
                {CSRF_token}
				<input type="hidden" name="action" value="save"/>
				<input type="hidden" name="parent" value="{$parentCategorySid}"/>
				<input type="hidden" name="raw_output" value="{$smarty.request.raw_output}"/>

				<div class="row">
					<div class="col-xs-12 usersBlock">
						<table class="items sortable table table-striped table-hover">
							<thead>
							<tr class="head">
								<th>#</th>
								<th>[[ID]] <i class="icon-asterisk smaller-60"></i></th>
								<th>[[Name]] <i class="icon-asterisk smaller-60"></i></th>
								<th>[[Meta Keywords]]</th>
								<th>[[Meta Description]]</th>
								<th>[[FormFieldCaptions!Page Title]]</th>
								<th></th>
							</tr>
							</thead>
							<tbody>
							{foreach $categoriesInfo as $key => $categoryInfo}
								<tr data-item-key="{$key}" data-item-index="{$categoryInfo@iteration}">
									<td class="index">{$categoryInfo@iteration}</td>
									<td>
										{include file="field_types^input/string.tpl" id="categories[{$key}][id]" value=$categoryInfo.id hasError=!empty($validationErrors.$key.id) error=$validationErrors.$key.id}
									</td>
									<td>
										{include file="field_types^input/string.tpl" id="categories[{$key}][name]" value=$categoryInfo.name hasError=!empty($validationErrors.$key.name) error=$validationErrors.$key.name}
									</td>
									<td>
										{include file="field_types^input/textarea.tpl" id="categories[{$key}][meta_keywords]" value=$categoryInfo.meta_keywords hasError=!empty($validationErrors.$key.meta_keywords) error=$validationErrors.$key.meta_keywords}
									</td>
									<td>
										{include file="field_types^input/textarea.tpl" id="categories[{$key}][meta_description]" value=$categoryInfo.meta_description hasError=!empty($validationErrors.$key.meta_description) error=$validationErrors.$key.meta_description}
									</td>
									<td>
										{include file="field_types^input/textarea.tpl" id="categories[{$key}][page_title]" value=$categoryInfo.page_title hasError=!empty($validationErrors.$key.page_title) error=$validationErrors.$key.page_title}
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
								<td colspan="6"><button type="submit" class="btn btn-default">[[Save:raw]]</button></td>
							</tr>
							</tbody>
						</table>
					</div>
				</div><!-- end row  -->

			</form>
		</div>

	</div>
</div>
<script type="text/javascript">
	$(document).ready(function () {

		$(".items").on("click", ".delete", function () {
            /*Если строк только 1, то не удаляем*/
            if ($("*[data-item-key]").length < 2)
                return false;

			$(this).closest("*[data-item-key]").remove();
		});

		$(".addMore").click(function () {
			var $categoryFormRows = $("*[data-item-key]");

			// defining maximum key and index
			var maxKey = 0;
			var maxIndex = 0;
			$categoryFormRows.each(function () {
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

			var $clone = $categoryFormRows.last().clone();

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

			$clone.insertAfter($categoryFormRows.last())
		})
	});
</script>

