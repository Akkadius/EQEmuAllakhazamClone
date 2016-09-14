package quest;

use strict;

sub say {
  main::WriteIt("<br><b>".$main::mname." says </b>'".$_[0]."'");
}

sub shout {
  main::WriteIt("<br><font color=red><b>".$main::mname." shouts </b>'".$_[0]."'</font>");
}

sub emote {
  main::WriteIt("<br><font color=green><b>".$main::mname." </b>".$_[0]."</font>");
}
sub me { quest::emote(@_); }

sub summonitem {
  main::WriteIt("<br><b>".$main::mname." gives you : </b><a href=$main::root_url"."item.php?id=$_[0]>".main::GetItemName($_[0])."</a>");
  main::add_received($_[0]);
}

sub exp {
  main::WriteIt("<br><font color=yellow>You gain experience!!</font>");
}

sub givecash {
  my ($c,$s,$g,$p)=@_;
  my $str="<br><b>You receive </b>";
  if ($p>0) { $str.=" ".$p."pp"; }
  if ($g>0) { $str.=" ".$g."gp"; }
  if ($s>0) { $str.=" ".$s."sp"; }
  if ($c>0) { $str.=" ".$c."cp"; }
  main::WriteIt($str);
}

sub faction {
  my ($faction,$val)=@_;
  if ($val>0) {
    main::add_raised($faction);
    main::WriteIt("<br><font color=red>Your faction with <a href=$main::root_url"."faction.php?id=$faction>".main::GetFactionName($faction)."</a> has gotten better.</font>");
  }
  if ($val<0) {
    main::add_lowered($faction);
    main::WriteIt("<br><font color=red>Your faction with <a href=$main::root_url"."faction.php?id=$faction>".main::GetFactionName($faction)."</a> has gotten worse.</font>");
  }
}

sub depop {
  main::WriteIt("<br><b>".$main::mname." disappears.</b>");
}

sub castspell {
  main::WriteIt("<br><font color=brown><b>".$main::mname." casts a spell on you : </b><a href=$main::root_url"."spell.php?id=$_[1]>".main::GetSpellName($_[1])."</a>.</font>");  
}

sub selfcast {
  main::WriteIt("<br><font color=brown><b>".$main::mname." casts a spell : </b><a href=$main::root_url"."spell.php?id=$_[0]>".main::GetSpellName($_[0])."</a>.</font>");  
}

sub ChooseRandom {
  my $item=0;
  return $_[int(rand($#_)+1)];
  return $_[0];
}

sub unique_spawn {
  my ($npc,$grid,$guild,$x,$y,$z)=@_;
  my $txt="<br><font color=white><b><a href=$main::root_url"."npc.php?id=$npc>".main::GetNpcName($npc)."</a></b> spawns";
  if ($x ne "") { $txt.=" ($y,$x,$z)"; }
  $txt.=".</font>";  
  main::WriteIt($txt);
}
sub spawn { quest::unique_spawn(@_); }
sub spawn2 { quest::unique_spawn(@_); }

sub setglobal { main::WriteIt("<br><font color=grey><i>That step of the quest sets a global variable.</font></i>"); }
sub delglobal { main::WriteIt("<br><font color=grey><i>A global variable is unset at this point.</font></i>"); }

sub setnexthpevent { main::WriteIt("<br><font color=grey><i>An event will appear when the NPC will be at $_[0]% health.</font></i>"); }

sub pause {
  main::WriteIt("<br><font color=white>The npc waits $_[0] seconds...</font>");
}

sub gmmove {
  main::WriteIt("<br><font color=white>You are teleported to $_[2],$_[1],$_[3] in ".main::GetZoneName($_[0]).".</font>");
}

sub moveto {
  my ($x,$y,$z)=@_;
  my $txt="<br><font color=white><b>The NPC moves to $x,$y,$z.</b></font>";
  main::WriteIt($txt);
}
sub movepc {
  my ($zone,$x,$y,$z)=@_;
  my $txt="<br><font color=white><b>The Player moves to zone $zone at $x,$y,$z</b></font>";
  main::WriteIt($txt);
}

# to be completed
sub signal {}
sub signalwith {}
sub level {}
sub safemove {}
sub addskill {}
sub setguild {}
sub rebind {}
sub follow {}
sub sfollow {}
sub movenpc {}
sub setallskill {}
sub attack {}
sub spawn_condition {}
# have to think about em

# can be ignored
sub start {}
sub set_zone_flag {}
sub flagcheck {}
sub write {}
sub pvp {}
sub doanim {}
sub rain {}
sub traindisc {}
sub ding {}
sub isdisctome {}
sub echo {}
sub cumflag {}
sub flagnpc {}
sub flagclient {}
sub settime {}
sub setsky {}
sub settimer {}
sub stoptimer {}
sub settarget {}
sub targlobal {}
sub set_proximity {}
sub clear_proximity {}
sub getplayerburriedcorpsecount {}
sub scribespells {}
return 1;
