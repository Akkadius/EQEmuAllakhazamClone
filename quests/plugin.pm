package plugin;


#######################
### complete that file with all plugin:: entries you use in your quests, even if you create empty methods.
### if a method is missing when a quest is parsed, it will break the parser and nothing or not all will be displayed
#######################

sub soulbinder_say { 
  my $plugin=$main::quests_dir."plugins/soulbinders.pl";
  eval "require '$plugin'"; 
  main::WriteIt("<p><b>You say, </b>'Hail'");
  &soulbinder_say("Hail");
  main::WriteIt("<p><b>You say, </b>'Bind my soul'");
  &soulbinder_say("Bind my soul");
}

sub check_handin {
  my $hashref = shift;
  my %required = @_;
  foreach my $req (keys %required) {
	  if ((!defined $hashref->{$req}) || ($hashref->{$req} != $required{$req})) {
      return(0);
  	}
  }
  foreach my $req (keys %required) {
    delete $hashref->{$req};
  }
  return 1;
}

sub check_handin_give_stuff { 
  my @str=split(/[()]/,$_[0]);
  my $stuff="<p><b>You give ".$main::mname." : </b>";
  my $sep="";
  %main::itemcount=();
  foreach (@str) {
    if ($_=~/=\>/) { # we found the part with the data we need
      my @items=split(/,/,$_);
      foreach (@items) {
        if ($_=~/=\>/) { # this is a 12345=>1, ie and item and the number we need
          my @myitem=split(/=\>/,$_); # item=myitem[0] number=myitem[1]
          if (eval($myitem[1])>0) {
            main::add_handed(eval($myitem[0]));
            $stuff.=$sep."<a href=$main::root_url"."item.php?id=".eval($myitem[0]).">";
            $stuff.=main::GetItemName($myitem[0])."</a>"; 
            if (eval($myitem[1])>1) { $stuff.=" x".eval($myitem[1]); }
            $main::itemcount{eval($myitem[0])}=eval($myitem[1]);
            $sep=', ';
          }
        }
      }
    }
  }
  $main::faction=$main::faction2;
  main::WriteIt($stuff);
  return 1; 
}

sub return_items {
  if ($main::return_items==0) {
    main::WriteIt("<p><i>This npc will return you any extra items you gave him.</i>"); 
    $main::return_items=1; 
  }
}

sub check_hasitem {
   my ($client,$item) = @_;
   my $stuff="<p><b>".$main::mname." checks to see if you have " ;
      $stuff.="<a href=$main::root_url"."item.php?id=".eval($item).">";
      $stuff.=main::GetItemName($item)."</a></b><br>"; 
   main::WriteIt($stuff);
}
sub try_tome_handins { }


# perl is not friendly
return 1;
