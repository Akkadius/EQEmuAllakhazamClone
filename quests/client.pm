package client;
use Switch;

sub Constructor {
  my($class)=@_;
  my($this)={};
  bless($this,$class);
  return $this;
}

# Carefull, the following faction returns the char's faction in function of the faction speficied by the website visitor !
# Not the true faction of the char with the npc.
sub GetCharacterFactionLevel {
  switch ($main::faction) {
    case 1 { return 1200; } # ALLY
    case 2 { return 900; } # WARLMY
    case 3 { return 550; } # KINDLY
    case 4 { return 250; } # AMIABLE
    case 5 { return 50; } # INDIFF
    case 9 { return -50; } # APPREHENSIVE
    case 8 { return -400; } # DUBIOUS
    case 7 { return -850; } # THREATNENLY
    case 6 { return -1100; } # SCOWLS
    else { return 99; } # default value if no faction has been set, its a big indifferent
  }
}
sub Message {}
sub CharacterID {}
sub GetGender {}
sub GetSkill {}
sub GetDeity {}
# tsss
return 1;
