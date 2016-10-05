<div class="bg-grey">
    <div class="container">
        <div class="searhFormOnMap bg-grey">
            <div class="map">
                <div class="mapHeader"></div>
                <div class="mapWrapper">
                    <div id="Map"></div>
                </div>
                <div class="mapFooter"></div>
                {include file="ads_on_map^google_map_script.tpl"}
            </div>
        </div>
        <div>
            <div class="space-20"></div>
            <div class="space-20"></div>
            {$smarty.capture.map_page_selector}
            <div class="space-20"></div>
            <div class="space-20"></div>
        </div>

    </div>
</div>
