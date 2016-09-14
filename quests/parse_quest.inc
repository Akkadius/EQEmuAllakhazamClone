sub say {
  $main::text=$_[0];
  main::WriteIt("<p><b>You say, </b>'".$main::text."'");
  $main::faction=$main::faction2;
  EVENT_SAY(); 
}

sub npc_spawns {
  main::WriteIt("<hr><b>When $main::mname spawns </b>");
  $main::faction=$main::faction2;
  EVENT_SPAWN();
}

sub npc_dies {
  main::WriteIt("<hr><b>When $main::mname is killed </b>");
  $main::faction=$main::faction2;
  EVENT_DEATH();
}

sub give_old {
  my $str="<p><b>You give ".$main::mname." : </b>";
  my $sep="";
  if ($main::item1>0) { $str.="$sep<a href=$main::root_url"."item.php?id=$main::item1>".GetItemName($main::item1)."</a>"; $sep=", "; }
  if ($main::item2>0) { $str.="$sep<a href=$main::root_url"."item.php?id=$main::item2>".GetItemName($main::item2)."</a>"; $sep=", "; }
  if ($main::item3>0) { $str.="$sep<a href=$main::root_url"."item.php?id=$main::item3>".GetItemName($main::item3)."</a>"; $sep=", "; }
  if ($main::item4>0) { $str.="$sep<a href=$main::root_url"."item.php?id=$main::item4>".GetItemName($main::item4)."</a>"; }
  main::WriteIt($str);
  $main::faction=$main::faction2;
  EVENT_ITEM();
  $main::item1="";
  $main::item2="";
  $main::item3="";
  $main::item4="";
  %main::itemcount=();
}

sub give {
  my $str="<p><b>You give ".$main::mname." : </b>";
  my @keys=keys(%main::itemcount);
  my $sep="";
  foreach (@keys) { 
    $str.="$sep<a href=$main::root_url"."item.php?id=$_>".GetItemName($_)."</a>";
    if ($main::itemcount{$_}>1) { $str.=" (x".$main::itemcount{$_}.")"; }
    $sep=", ";
  }
  main::WriteIt($str);
  $main::faction=$main::faction2;
  EVENT_ITEM();
  $main::item1="";
  $main::item2="";
  $main::item3="";
  $main::item4="";
  %main::itemcount=();
}

sub EvalItemOld { # item1 to item4
  my $s=0; my $e=0; my $q=0; my $c="";
  for ($i=0; $i<length($_[0]); $i++) { # from $ to second ", or ) or ; or {
    $c=substr($_[0],$i,1);
    if ($c eq "\$") { if (substr($_[0],$i,5) eq "\$item") { $s=$i; } }
    if ($c eq "\"") { $q++; if ($q==2) { $e=$i+1; } }
    if ($c=~/[;,),{]/) { if ($s>0) { $e=$i; } }
    if (($e>0) && ($s>0)) {
      my $item=substr($_[0],$s,$e-$s);
      $item=~s/==/=/;
      $item=~s/()//;
      eval($item);
      $s=0; $e=0; $q=0;
      $myid=substr($item,5,1);
      if ($myid==1) { main::add_handed(item1); $main::itemcount{$main::item1}++; }
      if ($myid==2) { main::add_handed(item2); $main::itemcount{$main::item2}++; }
      if ($myid==3) { main::add_handed(item3); $main::itemcount{$main::item3}++; }
      if ($myid==4) { main::add_handed(item4); $main::itemcount{$main::item4}++; }
    }
  }
}

sub EvalItemcount { # itemcount(}
  my $s=0; my $e=0; my $c=""; my $sd=0; my $ed=0; my $sn=0; my $ni=0; my $q=0;
  my $item="";
  for ($i=0; $i<length($_[0]); $i++) { # from $ to ) or ; 
    $c=substr($_[0],$i,1);
    if ($c eq "\$") { if (substr($_[0],$i,10) eq "\$itemcount") { $s=$i; $e=0;  } }
    if ($c=~/[;,)]/) { if ($s>0) { $e=$i; } }
    if ($c eq "{") { if (($sd==0) && ($s>0)) { $sd=$i; } } # this is the start of the item's id
    if ($c eq "}") { if (($ed==0) && ($sd>0)) { $ed=$i; } } # this is the end of the item's id
    if ($c eq "{") { if ($ed>0) { $e=$i; } } # this is not the { of itemcount
    if ($c eq "=") { if (substr($_[0],$i,2) eq "==") { if ($e>0) { $sn=$i; } } } # we start the == * if we ended }
    if ($c=~/\d/) { if ($sn>0) { $ni=$i; } } # this is a digit from the number of items we have
    if ($c eq " ") { if ($ni>0) { $e=$i; } } # we have a space after the number of items, itemcount is finished
    if ($c eq "&") { if ($s>0) { $e=$i-1; } } # we found a &&, so our itemcount{} is finished
    if ($c eq "|") { if ($s>0) { $e=$i-1; } } # we found a ||, so our itemcount{} is finished
    if (($e>0) && ($s>0)) {
      $item=substr($_[0],$s,$e-$s);
      $item=~s/==/=/;
      $item=~s/()//;
      if (!($item=~/=/)) { $item=$item."=1"; }
      eval($item);
      $s=0; $e=0; $q=0; $sd=0; $ed=0; $sn=0;
      my @datas=split(/[{}]/,$item);
      main::add_handed($datas[1]);
    }
  }
}

sub ParseFile() {
  my (@content)=@_; 
  #Something happens when the npc spawns ?
  for (my $i=0; $i<$#content; $i++) {
    if ($content[$i]=~/EVENT_SPAWN/) { npc_spawns(); }
  }

  # Let's begin by searching what the player coud say
  foreach (@content) {
    chop;
    if (/\$text/) {
      my @data=split(/\//);
      if ($data[1] ne "") { &say($data[1]); }
    }
  }
  # Now, we give the npc some items
  # Old form, item1 to item4, starting at the if and ending at the {
  for (my $i=0; $i<$#content; $i++) {
    if ($content[$i]=~/item[1234]\s*==/) { 
      EvalItemOld($content[$i]);
      do {
        $i++;
        if ($content[$i]=~/item[1234] ==/) { EvalItemOld($content[$i]); }
      } while (!($content[$i]=~/{/));
      give_old();
    }
  }
  # Now, we give again the npc some items
  # New form itemcount{}, starting at the $itemcount and ending at the first ::
  for (my $i=0; $i<$#content; $i++) {
    if ($content[$i]=~/itemcount{\d+}\s*==/) {
      EvalItemcount($content[$i]);
      do { 
        $i++;
        if ($content[$i]=~/itemcount{\d+}\s*==/) { EvalItemcount($content[$i]); }
      } while (!($content[$i]=~/::/));
      give();
    }
  }  
  
  #Something happens when the npc dies ?
  for (my $i=0; $i<$#content; $i++) {
    if ($content[$i]=~/EVENT_DEATH/) { npc_dies(); }
  }

  # This is for plugin methods, each time you add a plugin into quests/plugins, you'll have to add its counterpart here
  # And into plugin.pm, see examples on how to implement a new plugin. Don't implement any and it ll be much easier :)
  for (my $i=0; $i<$#content; $i++) {
    if ($content[$i]=~/plugin::soulbinder_say/) { plugin::soulbinder_say(); }
    if ($content[$i]=~/plugin::check_handin/) { plugin::check_handin_give_stuff($content[$i]); EVENT_ITEM(); }
  }
  
  # then we do it again to check if return_items is added to the end of the quest, only if there's an EVENT_ITEM() in the quest
  $main::return_items=0;
  for (my $i=0; $i<$#content; $i++) {
    if ($content[$i]=~/EVENT_ITEM/) { EVENT_ITEM(); }
  }
}


# Perl, bad guy, stop to complain !
return 1;
