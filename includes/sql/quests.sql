DROP TABLE IF EXISTS quest_items;
CREATE TABLE quest_items (
  item_id int(11) NOT NULL default '0',
  npc varchar(64) NOT NULL default '',
  zone varchar(64) NOT NULL default '',
  rewarded tinyint(4) NOT NULL default '0',
  handed tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (item_id,npc,zone),
  KEY item_id (item_id)
) TYPE=MyISAM;
