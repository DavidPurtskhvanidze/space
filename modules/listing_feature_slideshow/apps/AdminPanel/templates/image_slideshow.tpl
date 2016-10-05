<button class="btn btn-primary" id="image-gallery-button"><i class="fa fa-play"></i>[[View Slide Show:raw]]</button>
{require component="Gallery" file="css/blueimp-gallery.min.css"}
{require component="Bootstrap-Image-Gallery" file="css/bootstrap-image-gallery.min.css"}

<div id="links">
	{foreach from=$listing.pictures.collection key=key item=picture name=thumbnails}
		<a href="{$picture.file.large.url}" title="{$picture.caption}" data-description="{$picture.caption}" data-gallery></a>
	{/foreach}
</div>

<!-- The Bootstrap Image Gallery lightbox, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-start-slideshow="true" data-use-bootstrap-modal="false">
    <!-- The container for the modal slides -->
    <div class="slides"></div>
    <!-- Controls for the borderless lightbox -->
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
    <!-- The modal dialog, which will be used to wrap the lightbox content -->
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body next"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left prev">
                        <i class="glyphicon glyphicon-chevron-left"></i>
                        Previous
                    </button>
                    <button type="button" class="btn btn-primary next">
                        Next
                        <i class="glyphicon glyphicon-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{* This scripts should be loaded in the end. That is why they are not loaded via {require} *}
<script type="text/javascript" src="{$GLOBALS.front_end_url}/vendor/Gallery/js/jquery.blueimp-gallery.min.js"></script>
<script type="text/javascript" src="{$GLOBALS.front_end_url}/vendor/Bootstrap-Image-Gallery/js/bootstrap-image-gallery.min.js"></script>

<script>
	$(function () {
		$('#image-gallery-button').on('click', function (event) {
			event.preventDefault();
			blueimp.Gallery($('#links a'), $('#blueimp-gallery').data());
		});
	})
</script>
