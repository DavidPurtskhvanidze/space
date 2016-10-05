

<div class="breadcrumbs">
	<ul class="breadcrumb">
		<a href="{page_path id='edit_themes'}">[[Themes]]</a> &gt; [[Edit colors]]
	</ul>
</div>

<div class="page-content">
	<div class="page-header">
		<h1>[[Edit colors]]</h1>
	</div>

	<div class="row">
		{display_error_messages}
		{display_success_messages}

		<div class="editTemplate">

			<ul class="list-unstyled">
				<li><p>[[Theme]]: <b>{$themeName}</b></p></li>
			</ul>

			<form action="" method="post" class="form">
                {CSRF_token}
				<input type="hidden" name="theme" value="{$themeName}">
				<input type="hidden" name="action" value="save">

				<div id="accordion" class="colorize accordion-style1 panel-group">
					{foreach $themeColors as $colorizeKey => $colorize}

						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#{$colorizeKey}">
										<i class="icon-angle-right bigger-110" data-icon-hide="icon-angle-down" data-icon-show="icon-angle-right"></i>
										[[{$colorize.Caption}]]
									</a>
								</h4>
							</div>
							<div id="{$colorizeKey}" class="panel-collapse collapse">
								<div class="panel-body">
									{foreach $colorize.rules as $ruleKey => $rule}
										<div class="form-group row">
											<label class="col-sm-3">[[{$rule.Caption}]]:</label>
											<div class="col-sm-6">
												<div class="input-group">
													<input type="text" name="{$colorizeKey}[{$ruleKey}]" value="{$rule.value}" class="form-control"/>
													<span class="input-group-addon"><i></i></span>
												</div>
											</div>
											<div class="col-sm-3">
												<a href="#" class="btn reset btn-default">[[Reset]]</a>
											</div>
										</div>
									{/foreach}
								</div>
							</div>
						</div>
					{/foreach}
					<div class="clearfix form-actions ClearBoth">
						<input type="submit" value="[[Save:raw]]" class="btn btn-default">
					</div>
				</div>
			</form>

		</div>
		{require component="jquery" file="jquery.js"}
		{require component="twitter-bootstrap" file="css/bootstrap.min.css"}
		{require component="twitter-bootstrap" file="js/bootstrap.min.js"}

		{require component="jquery-ui" file="css/smoothness/jquery-ui.css"}
		{require component="jquery-cookie" file="jquery.cookie.js"}

		{require component="bootstrap-colorpicker" file="css/bootstrap-colorpicker.min.css" order="9999"}
		{require component="bootstrap-colorpicker" file="js/bootstrap-colorpicker.min.js" order="9999"}

		<script>
			$(function () {
				$('.colorize .input-group').each(function () {
					$(this).colorpicker();
				});

				$('.colorize .reset').click(function(event){
					event.preventDefault();
					$(this).closest('.form-group').find('.input-group').colorpicker('setValue', '');
					$(this).closest('.form-group').find('input').val('').removeAttr('value');
				})

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
	</div>
</div>
