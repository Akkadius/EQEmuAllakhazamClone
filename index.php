<?php

    require_once('includes/constants.php');
    require_once('includes/config.php');
    require_once($includes_dir . 'mysql.php');
    require_once($includes_dir . 'functions.php');

    /* Handles PJAX requests */
    if(isset($_GET['v_ajax'])){
        require_once('routes.php');
        echo $print_buffer;
        if($page_title && !isset($_GET['v_tooltip'])){
            $footer_javascript .= '
                <script type="text/javascript">
                    $("#title").html("<h1>' . $page_title . '</h1>");
                    document.title = "' . $page_title . '";
                </script>
            ';
        }
        echo $footer_javascript;
        exit;
    }

    $start = microtime(true);
    $debug_queries = "";
    $print_buffer = "";
?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>EQEmulator Allakhazam</title>
    <link rel="icon" href="http://everquest.allakhazam.com/favicon.ico">
    <link rel="stylesheet" type="text/css" href="includes/alla.css">
    <link rel="stylesheet" type="text/css" href="./zam_files/global.css">
    <link rel="stylesheet" type="text/css" href="./zam_files/site.css" id="css">
    <link rel="stylesheet" type="text/css" href="includes/js/datatables/media/css/jquery.dataTables.min.css" id="css">

    <script src="includes/js/jquery-3.1.0.min.js"></script>
    <script src="./zam_files/jquery-migrate-1.2.1.min.js"></script>

    <link rel="stylesheet" type="text/css" href="./zam_files/zul.css" id="zul-bar-stylesheet">

    <?php
        if($hide_navbar){
            echo '<style>.zul-bar { display:none; } </style>';
        }
    ?>
</head>

<body class="has-zul-bar">
<div id="headjs" style="position: absolute; left: 0px; right: 0px; top: 0px; z-index: 999999999;"></div>
<div class="zul-bar" id="zul-bar" data-mobile="false">
    <div class="zul-bar-inner" id="zul-bar-inner">
    </div>
</div>

<div id="bg-wrapper" style="min-height: auto;">
    <div id="skin-wrap"></div>
    <div id="wrapper">
        <div id="body">
            <div id="cols">
                <div id="col-main">
                    <div id="col-main-inner">
                        <div id="col-main-inner-2">
                            <div id="col-main-inner-3">
                                <div id="buffer-top"></div>
                                <div style="width:100%; overflow: hidden;">
                                    <div class="side_menu" style="width:200px; display: inline-block; float: left;">
                                        <table border="0">

                                            <tbody>
                                            <tr>
                                                <td class="menuh" nowrap="1">Main...</td>
                                            </tr>
                                            <tr>
                                                <td  class="menu_item">
                                                    <li><a href="/">Home</a></li>
													<li><a href="/?p=leaderboard">Leaderboard</a></li>
													<li><a href="/Magelo">Magelo</a></li>
                                                    <li><a href="http://www.eqemulator.org">EQEmulator</a></li>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="menuh" nowrap="1">Items...</td>
                                            </tr>
                                            <tr>
                                                <td  class="menu_item">
                                                    <li><a href="?a=items">Item Search</a> </li>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="menuh" nowrap="1">Spells...</td>
                                            </tr>
                                            <tr>
                                                <td  class="menu_item">
                                                    <li><a href="?a=spells">Spell Search</a></li>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="menuh" nowrap="1">Bestiary...</td>
											</tr>
                                            <tr>
                                                <td  class="menu_item">
                                                    <li><a href="?a=npcs">NPC Search</a></li>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td  class="menu_item">
                                                    <li><a href="?a=pets">Pets</a>
                                                    </li>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="menuh" nowrap="1">Tasks...</td>
                                            </tr>
                                            <tr>
                                                <td  class="menu_item">
                                                    <li><a href="?a=tasks">Task Search</a></li>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="menuh" nowrap="1">Tradeskills...</td>
                                            </tr>
                                            <tr>
                                                <td  class="menu_item">
                                                    <li><a href="?a=recipes">Recipe Search</a></li>
                                                </td>
                                            </tr><tr>
                                                <td class="menuh" nowrap="1">Zones...</td>
                                            </tr>
                                            <tr>
                                                <td  class="menu_item">
                                                    <li><a href="?a=zonelist">Zones by Era</a></li>
                                                    <li><a href="?a=zones">Populated Zones</a> </li>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="page-content" style="margin-left: 220px;">
                                        <div id="title"></div>
                                        <div class="page-content-ajax">
                                            <?php

                                                require_once('routes.php');

                                                if($print_buffer){
                                                    print $print_buffer;
                                                }

                                                if($page_title){
                                                    $footer_javascript .= '
                                                        <script type="text/javascript">
                                                            $("#title").html("<h1>' . $page_title . '</h1>");
                                                            document.title = "' . $page_title . '";
                                                        </script>
                                                    ';

                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="clear"></div>
                                <div id="buffer-bottom"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
        $end = microtime(true);
        $time = number_format(($end - $start), 2);
        $page_load_time = 'This page loaded in ' . $time . ' seconds.';
    ?>

    <footer>
        <div class="block-content pad10" style="line-height:24px">
            <ul class="site-footer">
                <?php
                    echo $page_load_time;
                    if($mysql_debugging)
                        print $debug_queries;
                ?>
            </ul>
            <div class="clear"></div>
        </div>

        <div class="div15"></div>

        <script src="includes/js/footer.js"></script>

        <?php
            if($use_pace_loader){
                echo '
                    <link rel="stylesheet" type="text/css" href="includes/css/pace.css" id="css">
                    <script src="includes/js/pace.min.js"></script>
                ';
            }
        ?>

        <script src="includes/js/pjax.js"></script>
        <script src="includes/js/zam_tooltips.js"></script>
        <script src="includes/js/datatables/media/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript">
            Nav.init()
        </script>
        <?php
            if($footer_javascript){
                echo $footer_javascript;
            }

        ?>
    </footer>

    <div class="copyright">© 2016 EQEmulator :: Akkadius</div>

</div>


</body>

</html>