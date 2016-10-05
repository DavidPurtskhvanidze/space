$(document).ready(function()
{

    var menuCotainer = $('#sidebar');
    var collapsButton = menuCotainer.find('#sidebar-collapse');

    if ($.cookie('collapsedMenu') != undefined && $.cookie('collapsedMenu') == 1)
    {
        menuCotainer.addClass('menu-min');
    }

    collapsButton.bind("click",function(event)
    {
       if (menuCotainer.hasClass('menu-min'))
       {
           $.cookie('collapsedMenu', '1', { expires: 7, path: '/' });
       }
        else
       {
           $.cookie('collapsedMenu', '0', { expires: 7, path: '/' });
       }
    });

})
