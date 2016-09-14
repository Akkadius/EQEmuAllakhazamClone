REQUIREMENTS

- Web server (http://www.apache.org), with PHP support
- PHP with mysql support (http://www.php.net http://www.mysql.com)
- Perl with DBI module (ftp://ftp.demon.co.uk/pub/perl/db) (Optional, Required for quests)
- Mysql Server
- Any EqEmu 0.8.0 database

INSTALLATION:
With the Feb 2012 complete overhaul of this tool, the installation instructions are very simple.

1. Unpack the zip file to a location you can access from a browser ( such as /var/www/html/ ).
2. Edit the ../includes/config.php file and fill in your database, host, username, and password information.
3. Edit the config options to your liking.

Note: When setting a user in the config, it is highly recommended that the user only have SELECT priviledges in MySQL.  No other priviledges are required by this tool.

Note 2: Some of the features such as quest data, maps, csv, and others were not tested/adjusted for this revamp and may not work.

************************************************************************************

OLD INSTALL INFO FOR REFERENCE:

** The easy part :
- Rename includes/config.php.dist to includes/config.php
- Edit and configure includes/config.php

** The hard part :
- Go to the includes/sql directory
- Source races.sql and spells.sql to your eq database
- Source news.sql if you want to enable News support (see includes/config.php)
- Source quests.sql if you want to display quests in the browser (0.5.2+)
if you have Perl::DBI
- Copy spells_us.txt from your Everquest client directory to the includes/sql directory
- Edit and run "perl import_spells.pl", this will extract the spells from spells_us.txt and source 
them to your the table you created 2 steps before
- If you don't have perl::DBI, source allspells.sql

** Icons :
- from this version on the icons are no more bundled with the source code. It is much better that you download
  Georges' IconViewer and copy the contents of its Eqemu_Icons directory in the 'icons' directory of eqbrowser.
  That way you will get the most complete pack of icons (and it significantly decreases the size of this download).

** NPC images : 
- It is still possible to keep the NPC images into the 'npcs' subdirectory (in fact it is the default behaviour).
  The few existing images have been removed from the source code distribution though.
- A new way of handling NPC images is to link EQBrowser to a MediaWiki (that you have to install separately).
  This is done in the 'config.php' file. Then your users can add the NPC images through the link provided on the NPC page.

** Lucy/PEQ Editor interoperability :
- some pages have icons linking to the equivalent page on Lucy/PEQ Editor. The settings for the linked server roots are in config.php

** Quests :
EqBrowser 0.5+ can browse and display quests (not all, depends of the syntax used by the quest writer).
Be aware that having that part of the website working may not be easy for a Linux beginner.
- Go to the quests directory and check that the datas subdirectory (quests/datas) is writeable by your web server.
  This can be changed by the chmod/chown commands under Linux
  (chown quests/datas to the same user as your web server runs, and chmod 755 it).
- Check that the directory where you store your EqEmu quests is accessible by your web server, this is a prerequisite ! 
  If not, do the necessary, eventually, create a new quests directory and fill it with the quests. 
  The web server won't need write access, but only read access to that directory.
- edit 'parse_quest.pl', nothing really hard if you fully made EqBrower to work yet.
- edit 'includes/config.php' and put the right values to $quests_dir and $quests_datas. 
  They have to match exaclty what you wrote in parse_quest.pl (which should match the real pathes too !).
  Put $AllowQuestsNPC to TRUE while you are editing that file.

Each time a visitor will try to browse a quest, it will launch parse_quest.pl. 
The perl script will create a .inc file in quests/datas that will be kept for further use.
The inc file will be loaded by the php script and displayed as html content.
You can run parse_quest.pl manually (it will generate the inc files too, 
so be carefull with user rights, even more if you're connected as root).

You can optimize all that process if you know how to write Linux scripts. 
Create a script that parses all your quests and for each of them, run parse_quest.pl with right parameters. 
Launch that script each time you modify your quests and put $AlwaysBuildQuest to false in includes/config.php.

If you encounter some errors with plugin:: or quest:: methods, edit quests/plugin.pm or quests/quest.pm 
and add the missing methods. Even if they are empty, they will fix the problem.


UPDATE

- Check the HISTORY.TXT file to see if there's some advices on how to update.
- Unpack the package into your eqbrowser directory
- Rename includes/config.php into includes/config.php.old
- Rename includes/config.php.dist into includes/config.php
- Edit and configure includes/config.php (see config.php.old, you saved it earlier :p)
- Go to the includes/sql directory and source all the *.sql files. They will remove the old tables and new ones will be created.
- Copy spells_us.txt from your Everquest client directory to the includes/sql directory
- Edit and run "perl import_spells.pl", this will extract the spells from spells_us.txt and source 
them to your the table.
If you don't have perl::DBI, source allspells.sql


PERSONNALIZATION

In the /design directory, you'll find some html files that are included when the website is displayed.
Modify them to your wishes. Don't include <HTML> or <BODY> tags, and carefully close your <TABLE> if
you add some. Those files are optional, if you don't want any personnalization, delete them.


SNAPSHOTS and MAPS

- The directory named maps can contain maps of the zones. The files MUST be named `short_name`.jpg, where
`short_name` is the file name of the zone, IE, charasis for Howling Stones. When a map file is present in 
the directory, a link to the jpeg file is added in the zone display page. If there's a file named 
`short_name`_tn.jpg, it is used as thumbnail for the map picture.

- The directory named npcs can contain snapshots of the npcs, the files have to be named `npcid`.jpg where
`npcid` is the id of the npc in the `npc_types` table. The pictures of the npcs are shown on the npc display 
page.


FAQ

- When sourcing the spells SQL file, if you get an error message concerning error 1153 and max_allowed_packet,
edit your my.cnf file and put 8M as value for max_allowed_packet option.

- I modified or added something to the package : Please keep us informed, all the community will probably enjoy to 
benefit of your work !

- Muuss, you don't speak english very well : Yes i know, and i beg you to forgive me. I learnt it only a few years ago,
without any help and much too old :)

- Casting Dance of the fireflies is only possible at nighttime and it doesn't appear on the spell's description : Nighttime
is only supported on version 0.3 or higher, please update. If you're yet on version 0.3, run this : 
ALTER TABLE `eq`.`spells` ADD `timeofday` INT NOT NULL;
and run import_spells.pl again.

HELP / CREDITS
Design : http://everquest.allakhazam.com
Website : http://eqemu.vilvert.fr
Developpment : Muuss (muuss@wanadoo.fr)
