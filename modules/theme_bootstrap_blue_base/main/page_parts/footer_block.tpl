<footer id="footer" class="colorize-footer blue-bg">
	<div class="container text-center">
        <div class="share-block">
            {include file="miscellaneous^share_section.tpl"}
        </div>
        <div class="copyright">
            {IncludeCopyright}
        </div>
	</div>
</footer>
<script type="text/javascript">
    $(document).ready(function(){

        function footerToBottom() {
            var browserHeight = $(window).height();
            var footerOuterHeight = $('#footer').outerHeight(true);
            var mainHeightMarginPaddingBorder = $('#page-content').outerHeight() - $('#page-content').height();
            $('#page-content').css({
                'min-height': browserHeight - footerOuterHeight - mainHeightMarginPaddingBorder
            });
        }

        footerToBottom();
        $(window).resize(function () {
            footerToBottom();
        });
    })
</script>
