$(document).ready(function()
{
    $('form .has-error').each(function(index)
    {
        var that = $(this);
        that.popover({
            'title' : that.data('error'),
            'placement' : index == 0 ? 'bottom' : 'top',
            'content' : '',
            'trigger' : 'hover focus',
            'animation' : true,
            'html' : true
        });
    })
})

