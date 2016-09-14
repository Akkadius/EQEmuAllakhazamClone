The files into that directory are needed for eqbrowser. You can remove them once everything is running fine and till next update.

REQUIRED : races.sql -> creates the races table.
REQUIRED : spells.sql -> creates the spells table.
OPTIONAL : quests.sql -> creates quests_items table. Only required if you $AllowQuestsNPC is at TRUE in config.php
OPTIONAL : news.sql -> creates the eqbnews table. Only required if you $EnableNews is at TRUE in config.php

OPTIONAL : import_spells.pl -> this generates a brand new spells table, with the lastest spells from live. If you don't understand what I say, forget this.

OPTIONAL : allspells.sql -> This become REQUIRED if you didn't ran import_spells.pl. This is the full spell's list.

