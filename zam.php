<?php
    require_once('./includes/constants.php');
    require_once('./includes/config.php');
    require_once($includes_dir . 'mysql.php');
    require_once($includes_dir . 'functions.php');

    $start = microtime(true);
?>

<!DOCTYPE html>
<!-- saved from url=(0055)http://everquest.allakhazam.com/db/zone.html?mode=bymap -->
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Zones by Expansion :: EverQuest :: ZAM</title>
    <link rel="icon" href="http://everquest.allakhazam.com/favicon.ico">
    <link rel="stylesheet" type="text/css" href="includes/alla.css">
    <link rel="stylesheet" type="text/css" href="./zam_files/global.css">
    <link rel="stylesheet" type="text/css" href="./zam_files/site.css" id="css">
    <script src="./zam_files/jquery-1.10.2.min.js"></script>
    <script src="./zam_files/jquery-migrate-1.2.1.min.js"></script>
    <script src="includes/js/footer.js"></script>
    <link rel="stylesheet" type="text/css" href="./zam_files/zul.css" id="zul-bar-stylesheet">
    <link rel="stylesheet" type="text/css" href="./zam_files/tooltips.css">
</head>

<body class="has-zul-bar">
<div id="headjs" style="position: absolute; left: 0px; right: 0px; top: 0px; z-index: 999999999;"></div>
<div class="zul-bar" id="zul-bar" data-mobile="false">
    <div class="zul-bar-inner" id="zul-bar-inner">
    </div>
</div>

<div id="bg-wrapper" style="min-height: auto;">
    <div id="skin-wrap"></div>
    <div id="header">
        <div id="logo" style="background: url(images/logos/eqemu.png) 0 10px no-repeat;">
            <a href="#" style="background: url(images/logos/logo.png) right no-repeat;top:10px"></a>
        </div>
        <!--
        <form action="" name="search">
            <input name="q" type="text" onfocus="this.select()" value="" autocomplete="off">
            <a href="javascript:document.search.submit();"></a>
        </form>
        -->

        <div id="nav">
            <ul id="menu_horiz">
                <li class="has-sub first-child  nc-home"><a href="?">Home</a>
                    <div><em></em><var></var><strong></strong>
                        <ul>
                            <li class="first-child  nc-news-archives"><a href="http://eqemulator.org">EQEmulator</a> </li>
                            <li class="last-child  nc-zam-tools"><a href="http://everquest.allakhazam.com/"><span class="icon-tools">Official Allakhazam</span></a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        <script>
            Nav.init()
        </script>
    </div>
    <div id="wrapper">
        <div id="shadows">
            <div id="s-top"></div>
            <div id="s-bot"></div>
            <div id="s-left"></div>
            <div id="s-right"></div>
        </div>
        <div id="body">
            <div id="cols">

                <div id="col-main">
                    <div id="col-main-inner">
                        <div id="col-main-inner-2">
                            <div id="col-main-inner-3">
                                <div id="buffer-top"></div>


                                <div style="width:100%">
                                    <div style="width:200px; display: inline-block; float: left;">
                                        <table border="0">
                                            <form name="fullsearch" method="GET" action="fullsearch.php"></form>
                                            <tbody>
                                            <tr>
                                                <td class="menuh" nowrap="1">Main...</td>
                                            </tr>
                                            <tr>
                                                <td nowrap="1" class="menu_item">
                                                    <li><a href="?">AllaClone Main
                                                            Page</a>
                                                    </li>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td nowrap="1" class="menu_item">
                                                    <li><a href="http://www.eqemulator.org">EQEmulator</a>
                                                    </li>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="menuh" nowrap="1">Search...
                                                    <input type="hidden" name="isearchtype" value="">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input onfocus="if(this.value == 'Name...') { this.value = ''; }" onkeypress="var key=event.keyCode || event.which; if(key==13){ this.form.isearchtype.value = 'name'; this.form.submit(); } else {return true;}" type="text" name="iname" placeholder="Name..." size="20">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input onfocus="if(this.value == 'ID...') { this.value = ''; }" onkeypress="var key=event.keyCode || event.which; if(key==13){ this.form.isearchtype.value = 'id'; this.form.submit(); } else {return true;}" type="text" name="iid" placeholder="ID..." size="10">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="menuh" nowrap="1">Zones...</td>
                                            </tr>
                                            <tr>
                                                <td nowrap="1" class="menu_item">
                                                    <li><a href="?a=zonelist">Zones by Era</a></li>
                                                    <li><a href="http://10.0.1.12/allaclone/zones.php">Populated Zones</a> </li>
                                                    <li><a href="?a=zones_by_level">Zones by Level</a> </li>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="menuh" nowrap="1">Items...</td>
                                            </tr>
                                            <tr>
                                                <td nowrap="1" class="menu_item">
                                                    <li><a href="?a=items">Item Search</a> </li>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="menuh" nowrap="1">Spells...</td>
                                            </tr>
                                            <tr>
                                                <td nowrap="1" class="menu_item">
                                                    <li><a href="?a=spells">Spell Search</a></li>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="menuh" nowrap="1">Factions...</td>
                                            </tr>
                                            <tr>
                                                <td class="menu_item" nowrap="1">
                                                    <li><a href="?a=factions">Faction Search</a> </li>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td nowrap="1" class="menu_item">
                                                    <li><a href="http://10.0.1.12/allaclone/npcfactions.php">NPCs By
                                                            Faction</a>
                                                    </li>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="menuh" nowrap="1">Bestiary...</td>
                                            </tr>
                                            <tr>
                                                <td nowrap="1" class="menu_item">
                                                    <li><a href="http://10.0.1.12/allaclone/npcs.php">NPC Search</a>
                                                    </li>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td nowrap="1" class="menu_item">
                                                    <li><a href="http://10.0.1.12/allaclone/advnpcs.php">Advanced NPC
                                                            Search</a>
                                                    </li>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td nowrap="1" class="menu_item">
                                                    <li><a href="?a=pets">Pets</a>
                                                    </li>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="menuh" nowrap="1">Trade skills...</td>
                                            </tr>
                                            <tr>
                                                <td nowrap="1" class="menu_item">
                                                    <li><a href="http://10.0.1.12/allaclone/recipes.php">Recipe
                                                            Search</a>
                                                    </li>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="menuh" nowrap="1">Strategy...</td>
                                            </tr>
                                            <tr>
                                                <td nowrap="1" class="menu_item">
                                                    <li><a href="http://10.0.1.12/allaclone/strategy/melee.php">Melee
                                                            Damage Calculator</a>
                                                    </li>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="center page-content">
                                        <div id="title"></div>
                                        <?php

                                            $route = $_GET['a'];
                                            if($route == "spells"){ require_once('pages/spells.php'); }
                                            if($route == "spell"){ require_once('pages/spell.php'); }
                                            if($route == "item"){ require_once('pages/item.php'); }
                                            if($route == "pets"){ require_once('pages/pets.php'); }
                                            if($route == "zonelist"){ require_once('pages/zonelist.php'); }
                                            if($route == "items"){ require_once('pages/items.php'); }
                                            if($route == "factions"){ require_once('pages/factions.php'); }
                                            if($route == "faction"){ require_once('pages/faction.php'); }
                                            if($route == "pet"){ require_once('pages/pet.php'); }
                                            if($route == "zones_by_level"){ require_once('pages/zones_by_level.php'); }
                                            if($route == "zone"){ require_once('pages/zone.php'); }
                                            if($route == "npc"){ require_once('pages/npc.php'); }
                                            if($route == "recipe"){ require_once('pages/recipe.php'); }

                                            if($Title){
                                                $footer_javascript .= '
                                                    <script type="text/javascript">
                                                        $("#title").html("<h1>' . $Title . '</h1>");
                                                    </script>
                                                ';
                                            }
                                        ?>
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

        $page_load_time = 'This page loaded in ' . $time . ' seconds';

        if($slow_page_logging){
            $myfile = fopen("slow_page_logging.txt", "w") or die("Unable to open file!");
            fwrite($myfile, $_SERVER['REQUEST_URI'] . ' :: Took ' . $time . ' to load');
            fclose($myfile);
        }

    ?>

    <footer>
        <div class="block-content pad10" style="line-height:24px">
            <ul class="site-footer">
                <?php echo $page_load_time; ?>
            </ul>
            <div class="clear"></div>
        </div>

        <div class="div15"></div>

        <script type="text/javascript" src="jquery/easytooltip/js/easyTooltip.js"></script>
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