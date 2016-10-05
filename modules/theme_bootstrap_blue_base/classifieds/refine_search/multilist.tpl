<div class="more-less">
    <div class="items">
        <input type="hidden" name="{$id}[multilist]" value=""/>
        {foreach from=$list_values item=list_value}
            <div class="checkbox option {$list_value.caption}">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="{$id}[multilist][{$list_value.rank}]" value="1"
                               {if isset($value['multilist'][$list_value.rank])}checked{/if} /> {tr domain="Property_$id"}{$list_value.caption}{/tr}
                    </label>
                </div>
            </div>
        {/foreach}
    </div>
    <a href="#" class="show-more">[[Show More Options]]</a>
    <a href="#" class="show-less">[[Hide Options]]</a>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $(".more-less").each(function () {
            var $those = $(this);
            $('.show-more', $(this)).click(function () {
                $those.addClass('open', 300);
                return false;
            });
            $('.show-less', $(this)).click(function () {
                $those.removeClass('open', 300);
                return false;
            });
        });
    })
</script>
