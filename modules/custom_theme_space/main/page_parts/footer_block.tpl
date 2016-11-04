<footer id="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="copyright">
                    {IncludeCopyright}
                </div>
            </div>
            <div class="col-md-6">
                {include file="miscellaneous^share_section.tpl"}
            </div>
        </div>
    </div>
</footer>
<script type="text/javascript">
//    $(function() {
//        $('.advanced-search-block input[type="checkbox"]').bootstrapToggle({
//            on: 'On',
//            off: 'Off',
//            size: 'mini'
//        });
//    });
    Waves.attach('.wb');
    Waves.init();
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    $(document).ready(function() {
        $('.navbar-toggle').click(function() {
            $(this).toggleClass('open-menu');
        })
    })
</script>