<!DOCTYPE html>
<html lang="{i18n->getCurrentLanguage}">
<head>
	<meta name="keywords" content="{$KEYWORDS}" />
	<meta name="description" content="{$DESCRIPTION}" />
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width /">
	<title>{$GLOBALS.settings.product_name} Admin Panel {if $TITLE ne ""} :: {$TITLE}{/if}</title>
	<!-- #EXTERNAL_COMPONENTS_PLACEHOLDER# -->

    {require component="jquery" file="jquery.js"}
    {require component="twitter-bootstrap" file="css/bootstrap.min.css"}
    {require component="twitter-bootstrap" file="js/bootstrap.min.js"}

	{includeDesignFiles}
</head>
<body class="login-layout">
	{module name='main' function='display_body_top_templates'}

    <div class="main-container">
        <div class="main-content">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                    <div class="login-container">
                        <div class="space-24"></div>
                        <div class="center">
                            <h1><span class="white">[[Admin Panel]]</span></h1>
                            <h4 class="blue">[[Version]] {$GLOBALS.settings.product_version}</h4>
                        </div>

                        <div class="space-6"></div>

                        <div class="position-relative">
                            <div id="login-box" class="login-box visible widget-box no-border auth">
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <h4 class="header blue lighter bigger">
                                            <i class="icon-ban-circle red"></i>
	                                        Access Denied
                                        </h4>

                                        <div class="space-6"></div>
                                        <p class="bg-danger">{display_error_messages}</p>
                                    </div><!-- widget-main end-->
                                </div> <!-- widget-body end -->
                                <div class="center"><h6><span class="white">&copy; 2006&ndash;2016 </span><a href="http://www.worksforweb.com/">WorksForWeb</a></h6></div>
                            </div><!-- login-box end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
