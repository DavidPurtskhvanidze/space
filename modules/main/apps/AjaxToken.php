<?php
/**
 *
 *    Module: main v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: main-7.5.0-1
 *    Tag: tags/7.5.0-1@19796, 2016-06-17 13:19:59
 *
 *    This file is part of the 'main' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */

namespace modules\main\apps;

use modules\main\apps\FrontEnd\IHtmlHeaderTagContentDisplayer as FrontEndHtmlHeaderTagContentDisplayer;
use modules\main\apps\SubDomain\IHtmlHeaderTagContentDisplayer as SubDomainHtmlHeaderTagContentDisplayer;
use modules\main\apps\MobileFrontEnd\IHtmlHeaderTagContentDisplayer as MobileFrontEndHtmlHeaderTagContentDisplayer;
use modules\main\apps\AdminPanel\IBodyTopTemplateDisplayer as AdminPanelBodyTopTemplateDisplayer;

class AjaxToken implements FrontEndHtmlHeaderTagContentDisplayer, SubDomainHtmlHeaderTagContentDisplayer, MobileFrontEndHtmlHeaderTagContentDisplayer, AdminPanelBodyTopTemplateDisplayer
{

    /**
     * Method for displaying templates
     */
    public function display()
    {
        $tokenValue = \App()->Token->getToken();

        echo "<script type=\"text/javascript\">
                $.ajaxSetup({
                  data: {
                    'secure_token': '{$tokenValue}'
                  }
                });
              </script>";
    }
}
