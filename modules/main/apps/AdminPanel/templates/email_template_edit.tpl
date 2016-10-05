<div class="editListValues">
	<div class="breadcrumbs">
		<ul class="breadcrumb">
			<li><a href="{page_path module='main' function='email_templates'}">[[Email Templates]]</a></li>
			<li>[[Edit]]</li>
		</ul>
	</div>
	<div class="page-content">
		<div class="page-header">
			<h1>{$emailTemplate.id}</h1>
		</div>
		{display_error_messages}
		{display_success_messages}
		<div>
			<form method="post" class="form-horizontal">
                {CSRF_token}
				<input type='hidden' name='id' value="{$emailTemplate.id}">
				<input type='hidden' name='action' value="save">
				<div class="row">
					<div class="col-sm-9">
						<div class="form-group">
							<label for="subject" class="col-sm-3">[[Subject]]</label>
							<div class="col-sm-9">
								<input class="form-control" type="text" name="subject"
								       value="{$emailTemplate.subject}"/>
							</div>
						</div>

						<div class="form-group">
							<label for="subject" class="col-sm-3">[[Body]]</label>

							<div class="col-sm-9">
								{if $GLOBALS.settings.enable_wysiwyg_editor}
									{$height='300px'}
									<div class="form-control-group {if $hasError}has-error tooltip-error{/if}"
									     {if $hasError}data-rel="tooltip" data-placement="top" title="{$error}"{/if}
									     style="display:inline-block">
										{WYSIWYGEditor type="ckeditor" name="body" width="$width" height="$height" ToolbarSet="FullNoForms" entities=false entities_latin=false ForceSimpleAmpersand=true}
										{$emailTemplate.body}
										{/WYSIWYGEditor}
									</div>
								{else}
									<textarea name="body"
									          class="form-control {if $hasError}has-error tooltip-error{/if}"
									          {if $hasError}data-rel="tooltip" data-placement="top"
									          title="{$error}"{/if}>{$emailTemplate.body}</textarea>
								{/if}
							</div>
						</div>
					</div>

					<div class="col-sm-3">
						<label>[[Available Variables]]</label>
						<ul>
							{foreach $availableVariables as $availableVariable}
								<li>{$availableVariable}</li>
							{/foreach}
						</ul>
					</div>
				</div>
				<div class="clearfix form-actions">
					<input name="save" type="submit" value="[[Save:raw]]" class="btn btn-default"/>
				</div>
			</form>
		</div>
	</div>
</div>
