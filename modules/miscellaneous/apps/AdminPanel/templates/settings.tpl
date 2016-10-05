<div class="breadcrumbs">
	<ul class="breadcrumb">
		<li>[[System Settings]]</li>
	</ul>
</div>

<div class="page-content">
	<div class="page-header">
		<h1>[[System Settings]]</h1>
	</div>

	<div class="row">
		{display_success_messages}
		{display_error_messages}

		{if $watermarkUploadStatus == 'CANT_MOVE_UPLOADED_FILE'}
			<p class="text-error">[[Cannot move uploded file. Please set file permissions to 755.]]</p>
		{elseif $watermarkUploadStatus == 'UNSUPPORTED_FILE_TYPE' || $watermarkUploadStatus == 'RESTRICTED_EXTENSION'}
			<p class="text-error">[[Supported file formats : JPEG, GIF, PNG8<br/>Please refer to the article of the User Manual at User Manual -> Additional Features -> Watermark to learn more about watermark settings.]]</p>
		{elseif $watermarkUploadStatus == 'LARGER_THAN_INI_SIZE' || $watermarkUploadStatus == 'LARGER_THAN_FORM_SIZE'}
			<p class="text-error">[[The uploaded file is too large. Please either make the file smaller, or increase the size limit for uploads.]]</p>
		{elseif $watermarkUploadStatus == 'NO_TMP_DIR' || $watermarkUploadStatus == 'CANT_WRITE_TO_TMP_DIR'}
			<p class="text-error">[[Temporary directory cannot be found or is not writable. Please report this error to the server administrator.]]</p>
		{/if}

		<form enctype="multipart/form-data" method="post" class="form form-horizontal form-horizontal-settings">
			<input type="hidden" name="action" value="save">
            {CSRF_token}

			<div id="accordion" class="accordion-style1 panel-group">
				{foreach from=$pages item='page'}
					{capture assign='content'}{$page->getContent()}{/capture}

					{if !empty($content)}
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#{$page->getId()}">
										<i class="icon-angle-right bigger-110" data-icon-hide="icon-angle-down" data-icon-show="icon-angle-right"></i>
										[[{$page->getCaption()}]]
									</a>
								</h4>
							</div>
							<div id="{$page->getId()}" class="panel-collapse collapse">
								<div class="panel-body">
									{$content}
								</div>
							</div>
						</div>
					{/if}
				{/foreach}
			</div>
		</form>

		{require component="jquery" file="jquery.js"}
		{require component="jquery-ui" file="css/smoothness/jquery-ui.css"}
		{require component="jquery-cookie" file="jquery.cookie.js"}
		<script type="text/javascript">
			$(function () {

				$('#accordion')
						.find('.panel-collapse')
						.on('show.bs.collapse', function () {
							$.cookie('activeSettingAccordionPageHeaderId', $(this).prop('id'))
						})
						.on('hide.bs.collapse', function () {
							if ($.cookie('activeSettingAccordionPageHeaderId') == $(this).prop('id')) {
								$.cookie('activeSettingAccordionPageHeaderId', null);
							}
						});

				$('a.tabControl')
						.click(function () {
							$($(this).attr('href'))
									.parent('.panel')
									.find('.accordion-toggle.collapsed')
									.trigger('click');
							return false;
						});

				var hash = window.location.hash;

				if (hash) {
					$(hash).collapse('show');
				} else if ($.cookie('activeSettingAccordionPageHeaderId')) {
					$('#' + $.cookie('activeSettingAccordionPageHeaderId')).collapse('show');
				}

			});
		</script>
	</div
</div>
