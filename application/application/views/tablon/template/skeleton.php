<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $title;?></title>
        <meta name="description" content="">
        
        <link href='http://fonts.googleapis.com/css?family=Dancing+Script' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="<?php echo base_url('resources/css/normalize.css');?>">
        <link rel="stylesheet" href="<?php echo base_url('resources/css/main.css');?>">
        <link rel="stylesheet" href="<?php echo base_url('resources/css/tablon.css');?>">
        <script src="<?php echo base_url('resources/js/modernizr-2.8.2.min.js');?>"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <script type="text/x-handlebars">
            <div class="container-fluid">
                <header class="navbar navbar-static-top" id="top" role="banner">
                    <div class="container">
                        <?php echo $header;?>
                    </div>
                </header>

                <div id="content-center">
                    <div class="container">
                        <div class="col-md-2" role="main">
                            <div id="menu-left">
                                <div class="list-group">
                                    {{#link-to 'fridge' class="list-group-item"}}Mi frigorífico{{/link-to}}
                                    {{#link-to 'statistics' class="list-group-item"}}Estadísticas{{/link-to}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10" role="main">
                            <div id="main" class="clearfix col-md-12">
                                {{outlet}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="push-footer"></div>
            </div>

            <footer class="footer" role="contentinfo">
                <div class="container">
                    <div class="wrapper">
                        <?php echo $footer;?>
                    </div>
                </div>
            </footer>
            {{outlet modal}}

            <div class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">

                        </div>
                        <div class="modal-body">

                        </div>
                        <div class="modal-footer">
                            
                        </div>
                    </div>
                </div>
            </div>
            {{bs-growl-notifications}}
        </script>

        <?php echo $body;?>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="<?php echo base_url('resources/js/jquery-2.1.1.min.js');?>"><\/script>')</script>
        <script src="<?php echo base_url('resources/js/plugins.js');?>"></script>
        <script src="<?php echo base_url('resources/js/tablon.js');?>"></script>

        <script>
           /* (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X');ga('send','pageview');*/
        </script>
    </body>
</html>
