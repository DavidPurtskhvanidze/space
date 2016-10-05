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
    Waves.init();
    Waves.attach('.wb');
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>