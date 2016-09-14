DROP TABLE IF EXISTS eqbnews;
CREATE TABLE eqbnews (
  id int(11) NOT NULL auto_increment,
  date int(11) NOT NULL default '0',
  title varchar(250) NOT NULL default '',
  content text NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

INSERT INTO eqbnews VALUES (1,1101300277,'This is an example','This news is in the `eqbnews` table, in your Eqemu database. You can add or remove news by editing this table. This is a feature from EqBrowser version 0.3.\r\nIt can contain some HTML code as long as there\'s no BODY, HTML tags and as long as the TABLE are correclty closed.');

