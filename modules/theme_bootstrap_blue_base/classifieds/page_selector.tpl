{if $pages_number > 1}
	<div class="page-selector">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4 text-center">
                <ul class="pagination">
                    {if $current_page-1 > 0}
                        <li>
                            <a href="{$url}&amp;page=1">
                                <i class="fa fa-angle-double-left fa-2x"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{$url}&amp;page={$current_page-1}">
                                <i class="fa fa-angle-left fa-2x"></i>
                            </a>
                        </li>
                    {/if}
                    <li class="active"><span>{$current_page} [[of]] {$pages_number}</span></li>
                    {if $current_page+1 <= $pages_number}
                        <li>
                            <a class="nextPageSelector" href="{$url}&amp;page={$current_page+1}">
                                <i class="fa fa-angle-right fa-2x"></i>
                            </a>
                        </li>

                        <li>
                            <a class="nextPageSelector" href="{$url}&amp;page={$pages_number}">
                                <i class="fa fa-angle-double-right fa-2x"></i>
                            </a>
                        </li>
                    {/if}
                </ul>
            </div>
            <div class="col-sm-4 text-right to-top hidden-xs">
                <a href="#top">[[to top]] <i class="fa fa-chevron-up"></i></a>
            </div>
        </div>

	</div>
{/if}
