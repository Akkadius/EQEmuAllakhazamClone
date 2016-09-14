package entity_list;

sub Constructor {
  my($class)=@_;
  my($this)={};
  bless($this,$class);
  return $this;
}

sub GetMobID {
  my $ent=entity->Constructor();
  return $ent;
}


return 1;
