<?php

// see eq_packet_structs.h -> struct PlayerProfile_Struct

function FuckingDatas($string) {
  $i=0; $saybye=false;
  do {
    $a=hexdec(substr($string,$i,2));
    if ($a>0) { $str=$str.chr($a); } else { $saybye=true; }
    $i+=2;
  } while (($saybye==false) AND ($i<64));
  return $str;
}

function SplitProfile($profile) {
  $profile=bin2hex($profile);
  $char=array();
  $char["firstName"]=FuckingDatas(substr($profile,8,128));
  $char["lastName"]=FuckingDatas(substr($profile,136,64));
  $char["gender"]=(substr($profile,200,2)==1?"Female":"Male");
  $char["race"]=hexdec(substr($profile,208,2));
  $char["class"]=hexdec(substr($profile,216,2));
  $char["level"]=hexdec(substr($profile,232,2));
  $char["zone"]=hexdec(substr($profile,9080,2));
  $char["bindzone"]=hexdec(substr($profile,240,2));
  $char["guildeid"]=hexdec(substr($profile,448,2));
  return $char;
}

?>