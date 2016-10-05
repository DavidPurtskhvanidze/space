{require component="jquery" file="jquery.js"}
{require component="jquery-ui" file="css/smoothness/jquery-ui.css"}

<script>
	$(document).ready(function(){
		var evenColor = $('.items.sortable').find('tr.even').css('background-color');
		var oddColor = $('.items.sortable').find('tr.odd').css('background-color');

		$('.items.sortable').sortable({
			axis: 'y',
			handle: 'td.sort',
			items: 'tr:not(.head):not([data-sorting-exclude=true])',
			cursor: 'move',
			create: function(){
				var itemsSelector = $(this).sortable('option','items');
				var handle = $(this).sortable('option','handle');
				if ($(itemsSelector).length <= 1)
				{
					$(this).sortable({ disabled: true });
					$(itemsSelector).each(function(){
						$(this).find(handle).remove();
					});
				}
			},
            helper: function(e, tr)
            {
                var $originals = tr.children();
                var $helper = tr.clone();
                $helper.children().each(function(index)
                {
                    $(this).width($originals.eq(index).width()).css('background', '#f5f5f5');
                });
                return $helper;
            },
			update: function (event, ui) {
                var sid = ui.item.data('itemSid').toString();
                var nextItemSid = ui.item.next().data('itemSid');
                var prevItemSid = ui.item.prev().data('itemSid');

				var url = $(this).data('sortingUrl');
				var parentValue = $(this).data('parentValue');
				var parentNodeValue = $(this).data('parentNodeValue');
				var $this = $(this);

                var itemData = { sid: sid, nextItemSid: nextItemSid, prevItemSid: prevItemSid };

				$.ajax({
					url: url,
					data: { action: 'sort', item: itemData, parentValue: parentValue, parentNodeValue: parentNodeValue },
					success: function ()
					{
						$('tr:not(.head):nth-child(odd)', $this).animate({ 'background-color': evenColor },500);
						$('tr:nth-child(even)', $this).animate({ 'background-color': oddColor },500);
					},
					error: function () {
						$this.sortable('cancel');
						alert('[[Something has gone wrong. Please refresh the page.:raw]]');
					}
				});
			}
		});
		$('.items.sortable td.sort').disableSelection();
	});
</script>
