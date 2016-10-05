{if $parameters.id_prefix}
	{$id_attribute = " id=\"{$parameters.id_prefix}_{$id}\""}
{else}
	{$id_attribute = " id=\"input_{$id}\""}
{/if}
<div class="input file">
	<ul class="controls">
		{if $value.file_name ne null}
			<li class="removeOnFileDelete">
				<a class="itemControls download" title="[[Download:raw]]" href="{$value.file_url}">{$value.file_name}</a>
			</li>
			<li>
				<div class="fileInputButton" title="[[Modify:raw]]">
					<input type="file" name="{$id}"{$id_attribute} />
					<a class="itemControls modify" href="#">[[Modify]]</a>
					<span class="filenamePlaceholder"></span>
				</div>
			</li>
			<li class="removeOnFileDelete">
				<a class="itemControls delete bin" title="[[Delete:raw]]" href="{page_path module='classifieds' function='delete_uploaded_file'}?listing_id={$listing['id']}&amp;field_id={$id}">[[Delete]]</a>
			</li>
		{else}
			<li>
				<div class="fileInputButton" title="[[Upload:raw]]">
					<input type="file" name="{$id}"{$id_attribute} />
					<a class="itemControls upload" href="#">[[Upload]]</a>
					<span class="filenamePlaceholder"></span>
				</div>
			</li>
		{/if}
	</ul>
	<div class="errorPlaceholder"></div>
</div>

<script type="text/javascript">
	$(function () {
		$(".input.file .itemControls.delete").click(function () {
			var $context = $(this).parents("div.input.file");

			$.ajax({
				type: 'get',
				url: $(this).prop("href"),
				beforeSend: function () {
					$(".errorPlaceholder", $context).empty();
				},
				success: function () {
					$(".removeOnFileDelete", $context).remove();
				},
				error: function (xhr, textStatus, errorThrown) {
					$(".errorPlaceholder", $context).html(xhr.responseText);
				}
			});
			return false;
		});

		$(".input.file .fileInputButton input").change(function () {
			var $context = $(this).parents("div.input.file");
			var filePath = $(this).val();
			var filename = filePath.substr(Math.max(filePath.lastIndexOf("\\"), filePath.lastIndexOf("\/")) + 1);
			if (filename.length != 0) {
				$(".filenamePlaceholder", $context).text("[" + filename + "]");
			}
			else {
				$(".filenamePlaceholder", $context).text("");
			}
		});
	});
</script>
