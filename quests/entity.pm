package entity;

sub Constructor {
  my($class)=@_;
  my($this)={};
  bless($this,$class);
  return $this;
}

sub CastToNPC {
  my $npc=npc->Constructor();
  return $npc;
}


return 1;