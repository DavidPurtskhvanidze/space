{if $pages_number > 1}
	<div class="page-selector">
		<ul class="pagination">
			{if $current_page-1 > 0}
				<li>
					<a href="{$url}&amp;page={$current_page-1}">
						&laquo;
					</a>
				</li>
			{/if}
			{if $current_page-3 > 0}<li><a href="{$url}&amp;page=1">1</a></li>{/if}
			{if $current_page-3 > 1}<li class="disabled"><a>...</a></li>{/if}
			{if $current_page-2 > 0}<li><a href="{$url}&amp;page={$current_page-2}">{$current_page-2}</a></li>{/if}
			{if $current_page-1 > 0}<li><a href="{$url}&amp;page={$current_page-1}">{$current_page-1}</a></li>{/if}
			<li class="active"><a href="#">{$current_page}</a></li>
			{if $current_page+1 <= $pages_number}<li><a href="{$url}&amp;page={$current_page+1}">{$current_page+1}</a></li>{/if}
			{if $current_page+2 <= $pages_number}<li><a href="{$url}&amp;page={$current_page+2}">{$current_page+2}</a></li>{/if}
			{if $current_page+3 < $pages_number}<li class="disabled"><a>...</a></li>{/if}
			{if $current_page+3 < $pages_number + 1}<li><a href="{$url}&amp;page={$pages_number}">{$pages_number}</a></li>{/if}
			{if $current_page+1 <= $pages_number}
				<li>
					<a class="nextPageSelector" href="{$url}&amp;page={$current_page+1}">
						&raquo;
					</a>
				</li>
			{/if}
		</ul>
	</div>
{/if}
