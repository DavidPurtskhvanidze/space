{foreach from=$display_value item=value name=tree_value}

{tr domain="Property_$id"}{$value}{/tr}{if $smarty.foreach.tree_value.iteration < $smarty.foreach.tree_value.total} / {/if}

{/foreach}
