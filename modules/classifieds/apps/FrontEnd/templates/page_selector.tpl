{if $pages_number > 1}
	<div class="pageSelector">
		{if $current_page-1 > 0}
			<a class="prevPageSelector" href="{$url}&amp;page={$current_page-1}">
				<img src="{url file='main^prev_page_white.png'}" alt="[[Previous Page:raw]]" />
			</a>
		{/if}
		{if $current_page-3 > 0}<a href="{$url}&amp;page=1">1</a>{/if}
		{if $current_page-3 > 1}<span class="dots">...</span>{/if}
		{if $current_page-2 > 0}<a href="{$url}&amp;page={$current_page-2}">{$current_page-2}</a>{/if}
		{if $current_page-1 > 0}<a href="{$url}&amp;page={$current_page-1}">{$current_page-1}</a>{/if}
		<span class="selected">{$current_page}</span>
		{if $current_page+1 <= $pages_number}<a href="{$url}&amp;page={$current_page+1}">{$current_page+1}</a>{/if}
		{if $current_page+2 <= $pages_number}<a href="{$url}&amp;page={$current_page+2}">{$current_page+2}</a>{/if}
		{if $current_page+3 < $pages_number}<span class="dots">...</span>{/if}
		{if $current_page+3 < $pages_number + 1}<a href="{$url}&amp;page={$pages_number}">{$pages_number}</a>{/if}
		{if $current_page+1 <= $pages_number}
			<a class="nextPageSelector" href="{$url}&amp;page={$current_page+1}">
				<img src="{url file='main^next_page_white.png'}" alt="[[Next Page:raw]]" />
			</a>
		{/if}
	</div>
{/if}
