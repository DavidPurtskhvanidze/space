<div class="editImageCarousel">
    <div class="breadcrumbs">
        <ul class="breadcrumb">
            <li><a href="{page_path module='image_carousel' function='manage_image_carousel'}">[[Image Carousel]]</a> &gt; [[Edit Carousel Image]]</li>
        </ul>
    </div>
	 <div class="page-content">
        <div class="page-header">
            <h1 class="lighter">[[Edit Carousel Image]]</h1>
        </div>

	{display_error_messages}
    <div class="row">
        <div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60"></i>) are mandatory]]</div>


	<form method="post" class="form-horizontal" role="form" action="" enctype="multipart/form-data">
		<input type="hidden" name="action" value="save">
		<input type="hidden" name="image_sid" value="{$imageSid}">
		<table class="properties">
			{foreach from=$form_fields item=form_field}
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                      [[$form_field.caption]]
                      {if $form_field.is_required}<i class="icon-asterisk smaller-60"></i>{/if}
                    </label>
                    <div class="col-sm-8">
                        {input property=$form_field.id}
                    </div>
                </div>
				
			{/foreach}
            <div class="clearfix form-actions">
                <input type="submit" value="[[Save:raw]]" class="btn btn-default">
            </div>
		</table>
	</form>
</div>
</div>
</div>
