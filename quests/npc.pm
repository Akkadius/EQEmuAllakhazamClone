package npc;

sub Constructor { # creates an empty class
  my ($binz)=@_; 
  my $this={};
  bless ($this,$binz);
  return $this;
}

sub GetX {}
sub GetY {}
sub GetZ {}
sub GetHeading {}

sub AddToHateList {
}
sub SetHP {}
sub SetAppearance {}
sub AddItem {}
return 1;
