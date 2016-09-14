<?php
    require_once('./includes/constants.php');
    require_once('./includes/config.php');
    require_once($includes_dir . 'mysql.php');
    require_once($includes_dir . 'functions.php');
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
                                                    <li><a href="http://10.0.1.12/allaclone/index.php">AllaClone Main
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
                                                <td nowrap="1" class="menu_item">
                                                    <li><a href="http://10.0.1.12/allaclone/news.php">Server News</a>
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
                                                    <li><a href="?a=zonelist">Zones by
                                                            Era</a>
                                                    </li>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td nowrap="1" class="menu_item">
                                                    <li><a href="http://10.0.1.12/allaclone/zones.php">Populated
                                                            Zones</a>
                                                    </li>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td nowrap="1" class="menu_item">
                                                    <li><a href="http://10.0.1.12/allaclone/zoneslevels.php">Zones by
                                                            Level</a>
                                                    </li>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="menuh" nowrap="1">Items...</td>
                                            </tr>
                                            <tr>
                                                <td nowrap="1" class="menu_item">
                                                    <li><a href="http://10.0.1.12/allaclone/items.php">Item Search</a>
                                                    </li>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="menuh" nowrap="1">Spells...</td>
                                            </tr>
                                            <tr>
                                                <td nowrap="1" class="menu_item">
                                                    <li><a href="?a=spells">Spell Search</a>
                                                    </li>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="menuh" nowrap="1">Factions...</td>
                                            </tr>
                                            <tr>
                                                <td class="menu_item" nowrap="1">
                                                    <li><a href="http://10.0.1.12/allaclone/factions.php">Faction
                                                            Search</a>
                                                    </li>
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


    <footer>
        <div class="block-content pad10" style="line-height:24px">
            <ul class="site-footer">
                <li><a href="http://legacy.zam.com/subscribe.html">Subscribe</a>
                </li>
                <li><a href="http://everquest.allakhazam.com/wiki/About_Us">About ZAM</a>
                </li>
                <li><a href="http://legacy.zam.com/wiki/Frequently_Asked_Questions_(Support)">FAQ</a>
                </li>
                <li><a href="http://legacy.zam.com/press.html">Press</a>
                </li>
                <li><a href="http://legacy.zam.com/advertising.html">Advertise</a>
                </li>
                <li><a href="http://legacy.zam.com/privacy.html">Privacy Policy</a>
                </li>
                <li><a href="http://legacy.zam.com/terms.html">Terms of Service</a>
                </li>
                <li><a href="http://everquest.allakhazam.com/wiki/Forum_Rules">Forum Rules</a>
                </li>
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