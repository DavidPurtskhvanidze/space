<div class="dataTables_paginate">
  <ul class="pagination">
    {if $search.current_page-1 > 0}
      <li class="prev">
        <a href="?action=restore&amp;searchId={$search.id}&amp;page={$search.current_page-1}">
          <i class="icon-double-angle-left"></i>
        </a>
      </li>
    {/if}
    {if $search.current_page-3 > 0}<li><a href="?action=restore&amp;searchId={$search.id}&amp;page=1">1</a>{/if}</li>
    {if $search.current_page-3 > 1}<li><a href="#">...</a><li>{/if}</li>
    {if $search.current_page-2 > 0}<li><a href="?action=restore&amp;searchId={$search.id}&amp;page={$search.current_page-2}">{$search.current_page-2}</a>{/if}</li>
    {if $search.current_page-1 > 0}<li><a href="?action=restore&amp;searchId={$search.id}&amp;page={$search.current_page-1}">{$search.current_page-1}</a>{/if}</li>
    <li class="active"><a href="#">{$search.current_page}</a></li>
    {if $search.current_page+1 <= $search.pages_number}<li><a href="?action=restore&amp;searchId={$search.id}&amp;page={$search.current_page+1}">{$search.current_page+1}</a></li>{/if}
    {if $search.current_page+2 <= $search.pages_number}<li><a href="?action=restore&amp;searchId={$search.id}&amp;page={$search.current_page+2}">{$search.current_page+2}</a></li>{/if}
    {if $search.current_page+3 < $search.pages_number}<li><a href="#">...</a></li>{/if}
    {if $search.current_page+3 < $search.pages_number + 1}<li><a href="?action=restore&amp;searchId={$search.id}&amp;page={$search.pages_number}">{$search.pages_number}</a></li>{/if}
    {if $search.current_page+1 <= $search.pages_number}
      <li class="next">
        <a href="?action=restore&amp;searchId={$search.id}&amp;page={$search.current_page+1}">
          <i class="icon-double-angle-right"></i>
        </a>
      </li>
    {/if}
  </ul>
</div>
