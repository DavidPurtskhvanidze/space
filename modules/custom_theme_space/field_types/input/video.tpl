<div class="input file video form-control-group {if $hasError}has-error{/if}" {if $hasError}data-error="{$error}"{/if}>
	<ul class="controls text-center">
		{if $value.file_name ne null}
            {include file="miscellaneous^dialog_window.tpl"}
			<li class="removeOnFileDelete">
				<a class="itemControls watch btn btn-success" title="[[Watch a video:raw]]"
                   onclick='return openDialogWindow("[[Watch a video]]", this.href, 650)'
                   href="{page_path id='video_player'}?listing_id={$listing.id}&raw_output=1">
                    <span class="glyphicon glyphicon-play"></span>
					[[Watch a video]]
				</a>
			</li>
			<li>
				<div class="fileInputButton" title="[[Modify:raw]]">
					<input type="file" class="inputVideo" name="{$id}"/>
					<a href="#" class="itemControls modify btn btn-warning">
                        <span class="glyphicon glyphicon-edit"></span>
                        [[Modify]]
                    </a>
					<span class="filenamePlaceholder"></span>
				</div>
			</li>
			<li class="removeOnFileDelete">
				<a class="itemControls delete bin btn btn-danger" title="[[Delete:raw]]" href="{page_path module='classifieds' function='delete_uploaded_file'}?listing_id={$listing.id}&amp;field_id={$id}">
                    <span class="glyphicon glyphicon-trash"></span>
                    [[Delete]]
				</a>
			</li>
		{else}
			<li>
				<div class="fileInputButton text-center" title="[[Upload:raw]]">
					<input type="file" class="inputVideo" name="{$id}"/>
					<a href="#" class="itemControls upload btn btn-primary">
                        <span class="glyphicon glyphicon-upload"></span>
                        [[Upload]]
                    </a>
					<span class="filenamePlaceholder"></span>
				</div>
			</li>
		{/if}
	</ul>
	<div class="errorPlaceholder"></div>
	<div class="hint fieldTypeHint alert alert-warning">
		[[Supported video formats are: $supportedVideoFormats]]
		{if $maxFileSize}
			<br>
			[[Maximum allowed file size: $maxFileSize Mb.]]
		{/if}
	</div>
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
