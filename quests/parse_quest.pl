#!/usr/bin/perl
#
# Muuss 03/29/2006
#
# You should only have to edit config.inc
# 
#
use quest;
use plugin;
use client;
use npc;
use entity;
use entity_list;
use DBI; 
# quest variables
use vars qw($text $item1 $item2 $item3 $item4 %itemcount 
            $class $race $ulevel $name
            $platinum $gold $silver $copper 
            $mobid $mname $faction $faction2 $zonesn $zoneln $zoneid $mynpc
            @handed @received @raised @lowered $return_items
            $dbm, $root_url $client $quests_dir 
            $npc);
require("parse_quest.inc");

##########################################################################################################
# Configure the following variables depending of your system.  (see README.TXT)
##########################################################################################################
#
# the directory where your perl quest files stand. This should be your EQEMU directory followed by /quests/
# must be readable by the webserver
$quests_dir="/usr/local/emu/bin/quests/"; # must ends with a /

# the directory where the parsed datas will be stored, this must be writable by your webserver (ends with /)
# you will have to put that same directory in the includes/config.php file
my $quests_datas="/var/www/html/eq/quests/datas/";

# database configuration, required mainly for item's and faction's names
my $dbname="eq";
my $dbuser="root";
my $dbpassword="al1s0nld";
my $dbhost="localhost";
my $dbport=3306;
my $dbquestitems="quest_items"; # name of the table where quest items are stored

# URL of eqbrowser's root, example http://myserver.com/eqbrowser/, ends with /
$root_url="http://phobos/eq/";

# Name of the character that triggered the quest, this is just for display purposes
$name="[Your Name]";

#######################################################################################################################
#### PAST THAT POINT YOU SHOULDN'T HAVE TO MODIFY THE CODE ############################################################
#######################################################################################################################


if (@ARGV<2) { 
  print "\nSyntax : parse_quest.pl zoneshortname questfile.pl [npcid] [class] [race] [level] [faction]\n\n";
  exit 0;
}
$zonesn=$ARGV[0];
my $questfile=$ARGV[1];
my $npcid=$ARGV[2];
$class=$ARGV[3];
$race=$ARGV[4];
$ulevel=$ARGV[5];

# We save $faction since quests writers like to write $faction=client->GetCharacterFactionLevel
$faction=$ARGV[6]; $faction2=$faction; 

if (!(-d $quests_dir.$zonesn)) {
  print "\nError : zone '$zonesn' doesn't exists.";
  print "\nDirectory for that zone should be $quests_dir$zonesn.";
  exit 1;
}
my $file=$quests_dir.$zonesn."/".$questfile;
if (!(-f $file)) {
  print "\nError : quest file '$questfile' doesn't exists.";
  print "\"File for that npc should be $file.";
  exit 1;
}
# DB conn
my $dsm="DBI:mysql:database=$dbname;host=$dbhost;port=$dbport";
$dbm=DBI->connect($dsm,$dbuser,$dbpassword) or die "Impossible to connect to the database !";

# getting the zoneid
$query="SELECT zoneidnumber FROM zone WHERE short_name like '$zonesn'";
$req=$dbm->prepare($query);
$req->execute();
$item=$req->fetchrow_hashref();
$zoneid=$item->{'zoneidnumber'};


# Client, this is required for client->GetCharacterFactionLevel
$client=client->Constructor();

# Guess npc's name from filename
$mname=$questfile;
$mname=~s/.pl//;
$mname=~s/_/ /g;
$mynpc=$questfile;
$mynpc=~s/.pl//;
$npc=npc->Constructor(); # creates the class npc that may be used for GetX/Y/Z/Heading...

# Global variables used by package quest.pm
$text=""; # contains the text entered by the player
%itemcount=(); # hash table for items handed new form
$item1=""; $item2=""; $item3=""; $item4=""; # items given by the player (old form)
$gold=10000; $platinum=10000; $silver=10000; $copper=10000; # default values, hard to calculate in such formulas : $gold+$platinum*10>=3250

# Includes the quest file so we can use its methods EVENT_SAY, EVENT_ITEM, and so on
eval "require '$quests_dir$zonesn/$questfile'"; # ignores the missing 'return 1;' at the end of each quest file

# Loads the quest file content so we can parse it
open(FQH,$file);
my @content=<FQH>;
close FQH;
#print "Opened quest file:  $file\n";

# Lets create everything we'll need to build the HTML page later
@handed=();
@received=();
@raised=();
@lowered=();
$return_items=1;

my $TheQuest="";

sub WriteIt {
  $TheQuest.=$_[0]."\n";
}

sub GetItemName {
  my $query="SELECT name FROM items WHERE id='$_[0]'";
  my $req=$dbm->prepare($query);
  $req->execute();
  my $item=$req->fetchrow_hashref();
  return $item->{"name"};
}

sub GetFactionName {
  my $query="SELECT name FROM faction_list WHERE id='$_[0]'";
  my $req=$dbm->prepare($query);
  $req->execute();
  my $item=$req->fetchrow_hashref();
  return $item->{"name"};
}

sub GetSpellName {
  my $query="SELECT name FROM spells WHERE spellid='$_[0]'";
  my $req=$dbm->prepare($query);
  $req->execute();
  my $item=$req->fetchrow_hashref();
  return $item->{"name"};
}

sub GetNpcName {
  my $query="SELECT name FROM npc_types WHERE id='$_[0]'";
  my $req=$dbm->prepare($query);
  $req->execute();
  my $item=$req->fetchrow_hashref();
  return $item->{"name"};
}

sub GetZoneName {
  my $query="SELECT long_name FROM zone WHERE zoneidnumber='$_[0]'";
  my $req=$dbm->prepare($query);
  $req->execute();
  my $item=$req->fetchrow_hashref();
  return $item->{"long_name"};
}

sub AddHandedQuestItem { # Add the quest item for that npc into the quests_items table, eqbrowser feature
  my ($item,$npc)=@_;
  my $query="SELECT * FROM $dbquestitems WHERE item_id='$item' AND npc='$npc' AND zone='$zonesn'";
  my $req=$dbm->prepare($query);
  $req->execute();
  if ($req->rows==0) {
    $query="INSERT INTO $dbquestitems SET item_id='$item',npc='$npc',zone='$zonesn',handed=1,rewarded=0";
    my $req=$dbm->prepare($query);
    $req->execute();
  } else {
    $query="UPDATE $dbquestitems SET handed=1 WHERE item_id='$item' AND npc='$npc' AND zone='$zonesn'";
    my $req=$dbm->prepare($query);
    $req->execute();
  }
}

sub AddRewardedQuestItem { # Add the quest item for that npc into the quests_items table, eqbrowser feature
  my ($item,$npc)=@_;
  my $query="SELECT * FROM $dbquestitems WHERE item_id='$item' AND npc='$npc' AND zone='$zonesn'";
  my $req=$dbm->prepare($query);
  $req->execute();
  if ($req->rows==0) {
    $query="INSERT INTO $dbquestitems SET item_id='$item',npc='$npc',zone='$zonesn',handed=0,rewarded=1";
    my $req=$dbm->prepare($query);
    $req->execute();
  } else {
    $query="UPDATE $dbquestitems SET rewarded=1 WHERE item_id='$item' AND npc='$npc' AND zone='$zonesn'";
    my $req=$dbm->prepare($query);
    $req->execute();
  }
}

sub add_handed {
  my $ok=1;
  foreach (@handed) {
    if ($_==$_[0]) { $ok=0; }
  }
  if ($ok==1) { 
    @handed=(@handed,$_[0]); 
    AddHandedQuestItem($_[0],$mynpc);
  }
}

sub add_received{
  my $ok=1;
  foreach (@received) {
    if ($_==$_[0]) { $ok=0; }
  }
  if ($ok==1) { 
    @received=(@received,$_[0]); 
    AddRewardedQuestItem($_[0],$mynpc);
  }
}

sub add_raised {
  my $ok=1;
  foreach (@raised) {
    if ($_==$_[0]) { $ok=0; }
  }
  if ($ok==1) { @raised=(@raised,$_[0]); }
}

sub add_lowered {
  my $ok=1;
  foreach (@lowered) {
    if ($_==$_[0]) { $ok=0; }
  }
  if ($ok==1) { @lowered=(@lowered,$_[0]); }
}
# Launches the parser
ParseFile(@content);

# Produces de output
if (!-d $quests_datas.$zonesn) { mkdir($quests_datas.$zonesn,0777); `echo $quests_datas$zonesn > /tmp/log`; }
if (!-d $quests_datas.$zonesn) {
  print "\nError : impossible to create the zone directory, check the rights and such for $quests_datas$zonesn.";
  exit 1;
}

$file=$quests_datas.$zonesn."/".$questfile;
$file=~s/.pl$/.inc/;
open(FQH,">$file");
print FQH "<table border=0 width=100%><tr valign=top><td nowrap>";
my $hr="";
if ($npcid>0) {
  print FQH "<b>Quest(s) for </b><a href=$root_url"."npc.php?id=$npcid>$mname</a><hr>";
}
# items needed for the quest
if ($#handed>=0) {
  print FQH "$hr<b>Quest items:</b><ul>";
  foreach(@handed) {
    print FQH "<li><a href=$root_url"."item.php?id=$_>".GetItemName($_)."</a>"; 
  }
  print FQH "</ul>";
}
# items received from the quest
if ($#received>=0) {
  print FQH "<b>Rewards :</b><ul>";
  foreach(@received) {
    print FQH "<li><a href=$root_url"."item.php?id=$_>".GetItemName($_)."</a>"; 
  }
  print FQH "</ul>";
}

# factions raised
if ($#raised>=0) {
  print FQH "<b>Factions raised :</b><ul>";
  foreach(@raised) {
    print FQH "<li><a href=$root_url"."faction.php?id=$_>".GetFactionName($_)."</a>"; 
  }
  print FQH "</ul>";
}
# factions lowered
if ($#lowered>=0) {
  print FQH "<b>Factions lowered :</b><ul>";
  foreach(@lowered) {
    print FQH "<li><a href=$root_url"."faction.php?id=$_>".GetFactionName($_)."</a>"; 
  }
  print FQH "</ul>";
}

print FQH "</td><td>$TheQuest</td></tr>";
print FQH "</table>";
close FQH;


# ends with no error
exit 0;
