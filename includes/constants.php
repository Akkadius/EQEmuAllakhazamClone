<?php

// tables
$accounts_table = "account";
$character_table = "character_data";
$faction_list_table = "faction_list";
$forage_table = "forage";
$ground_spawns_table = "ground_spawns";
$items_table = "items";
$loot_drop_table = "lootdrop";
$loot_drop_entries_table = "lootdrop_entries";
$loot_table = "loottable";
$loot_table_entries = "loottable_entries";
$merchant_list_table = "merchantlist";
$task_table = "tasks";
$task_activities_table = "task_activities";
$goal_lists_table = "goallists";
$npc_faction_table = "npc_faction";
$faction_entries_table = "npc_faction_entries";
$npc_spells_entries_table = "npc_spells_entries";
$npc_spells_table = "npc_spells";
$npc_types_table = "npc_types";
$pets_table = "pets";
$spawn2_table = "spawn2";
$spawn_entry_table = "spawnentry";
$spawn_group_table = "spawngroup";
$trade_skill_recipe_table = "tradeskill_recipe";
$trade_skill_recipe_entries = "tradeskill_recipe_entries";
$zones_table = "zone";
$discovered_items_table = "discovered_items";
$spell_globals_table = "spell_globals";
$spells_table = "spells_new";

// added tables, source the needed file from the includes/sql directory
$tbspawnarea = "spawnarea"; // Tool Specific Table
$tbnews = "eqbnews"; // Tool Specific Table
$tbquestitems = "quest_items"; // Tool Specific Table
$tbraces = "races"; // Tool Specific Table


// merchant classes
$dbmerchants = [40, 41, 59, 61, 67, 68, 70];

// factions (factions.h)
$dbfactions = [
    1 => "Ally",
    2 => "Warmly",
    3 => "Kindly",
    4 => "Amiably",
    5 => "Indifferently",
    9 => "Apprehensive",
    8 => "Dubiously",
    7 => "Threateningly",
    6 => "Ready to Attack",
];

// classes
$dbclasses_names = [
    "Warrior",
    "Cleric",
    "Paladin",
    "Ranger",
    "Shadowknight",
    "Druid",
    "Monk",
    "Bard",
    "Rogue",
    "Shaman",
    "Necromancer",
    "Wizard",
    "Magician",
    "Enchanter",
    "Beastlord",
    "Berserker",
];
$dbclasses = [];
$dbclasses[0] = "Warrior";
$dbclasses[1] = "Warrior";
$dbclasses[2] = "Cleric";
$dbclasses[3] = "Paladin";
$dbclasses[4] = "Ranger";
$dbclasses[5] = "Shadowknight";
$dbclasses[6] = "Druid";
$dbclasses[7] = "Monk";
$dbclasses[8] = "Bard";
$dbclasses[9] = "Rogue";
$dbclasses[10] = "Shaman";
$dbclasses[11] = "Necromancer";
$dbclasses[12] = "Wizard";
$dbclasses[13] = "Magician";
$dbclasses[14] = "Enchanter";
$dbclasses[15] = "Beastlord";
$dbclasses[16] = "Berserker";
$dbclasses[17] = "Banker";
$dbclasses[20] = "GM Warrior";
$dbclasses[21] = "GM Cleric";
$dbclasses[22] = "GM Paladin";
$dbclasses[23] = "GM Ranger";
$dbclasses[24] = "GM Shadowknight";
$dbclasses[25] = "GM Druid";
$dbclasses[26] = "GM Monk";
$dbclasses[27] = "GM Bard";
$dbclasses[28] = "GM Rogue";
$dbclasses[29] = "GM Shaman";
$dbclasses[30] = "GM Necromancer";
$dbclasses[31] = "GM Wizard";
$dbclasses[32] = "GM Magician";
$dbclasses[33] = "GM Enchanter";
$dbclasses[34] = "GM Beastlord";
$dbclasses[35] = "GM Berserker";
$dbclasses[40] = "Banker";
$dbclasses[41] = "Merchant";
$dbclasses[59] = "Discord Merchant";
$dbclasses[60] = "Adventure Recruiter";
$dbclasses[61] = "Adventure Merchant";
$dbclasses[63] = "Tribute Master";
$dbclasses[64] = "Guild Tribute Master";
$dbclasses[66] = "Guild Banker";
$dbclasses[67] = "Radiant Crystal Merchant";
$dbclasses[68] = "Ebon Crystal Merchant";
$dbclasses[69] = "Fellowships";
$dbclasses[70] = "Alternate Currency Merchant";
$dbclasses[71] = "Mercenary Merchant";

// Slots
$dbslots = [];
$dbslotsid = [];
$dbslots[4194304] = "Power Source";
$dbslots[2097152] = "Ammo";
$dbslots[1048576] = "Waist";
$dbslots[524288] = "Feet";
$dbslots[262144] = "Legs";
$dbslots[131072] = "Chest";
$dbslots[98304] = "Fingers";
$dbslots[65536] = "Finger";
$dbslots[32768] = "Finger";
$dbslots[16384] = "Secondary";
$dbslots[8192] = "Primary";
$dbslots[4096] = "Hands";
$dbslots[2048] = "Range";
$dbslots[1536] = "Wrists";
$dbslots[1024] = "Wrist";
$dbslots[512] = "Wrist";
$dbslots[256] = "Back";
$dbslots[128] = "Arms";
$dbslots[64] = "Shoulders";
$dbslots[32] = "Neck";
$dbslots[18] = "Ears";
$dbslots[16] = "Ear";
$dbslots[8] = "Face";
$dbslots[4] = "Head";
$dbslots[2] = "Ear";
$dbslots[1] = "Charm";

// ItemClasses 2^(class-1)
$dbiclasses = [];
$dbiclasses[65535] = "All Classes";
$dbiclasses[32768] = "Berserker";
$dbiclasses[16384] = "Beastlord";
$dbiclasses[8192] = "Enchanter";
$dbiclasses[4096] = "Magician";
$dbiclasses[2048] = "Wizard";
$dbiclasses[1024] = "Necromancer";
$dbiclasses[512] = "Shaman";
$dbiclasses[256] = "Rogue";
$dbiclasses[128] = "Bard";
$dbiclasses[64] = "Monk";
$dbiclasses[32] = "Druid";
$dbiclasses[16] = "Shadowknight";
$dbiclasses[8] = "Ranger";
$dbiclasses[4] = "Paladin";
$dbiclasses[2] = "Cleric";
$dbiclasses[1] = "Warrior";

$db_classes_short = [];
$db_classes_short[65535] = "ALL";
$db_classes_short[32768] = "BER";
$db_classes_short[16384] = "BST";
$db_classes_short[8192] = "ENC";
$db_classes_short[4096] = "MAG";
$db_classes_short[2048] = "WIZ";
$db_classes_short[1024] = "NEC";
$db_classes_short[512] = "SHM";
$db_classes_short[256] = "ROG";
$db_classes_short[128] = "BRD";
$db_classes_short[64] = "MNK";
$db_classes_short[32] = "DRU";
$db_classes_short[16] = "SHD";
$db_classes_short[8] = "RNG";
$db_classes_short[4] = "PAL";
$db_classes_short[2] = "CLR";
$db_classes_short[1] = "WAR";

// races
$dbraces = [];
$dbraces[65535] = "All Races";
$dbraces[32768] = "Drakkin";
$dbraces[16384] = "Froglok";
$dbraces[8192] = "Vah Shir";
$dbraces[4096] = "Iksar";
$dbraces[2048] = "Gnome";
$dbraces[1024] = "Halfling";
$dbraces[512] = "Ogre";
$dbraces[256] = "Troll";
$dbraces[128] = "Dwarf";
$dbraces[64] = "Half Elf";
$dbraces[32] = "Dark Elf";
$dbraces[16] = "High Elf";
$dbraces[8] = "Wood Elf";
$dbraces[4] = "Erudite";
$dbraces[2] = "Barbarian";
$dbraces[1] = "Human";

$db_races_short = [];
$db_races_short[65535] = "ALL";
$db_races_short[32768] = "DRK";
$db_races_short[16384] = "FRG";
$db_races_short[8192] = "VAH";
$db_races_short[4096] = "IKS";
$db_races_short[2048] = "GNM";
$db_races_short[1024] = "HFL";
$db_races_short[512] = "OGR";
$db_races_short[256] = "TRL";
$db_races_short[128] = "DWF";
$db_races_short[64] = "HLF";
$db_races_short[32] = "DKE";
$db_races_short[16] = "HEF";
$db_races_short[8] = "WLF";
$db_races_short[4] = "ERU";
$db_races_short[2] = "BAR";
$db_races_short[1] = "HUM";

// Skills
$dbskills = array();
$dbskills[0] = '1H Blunt';
$dbskills[1] = '1H Slashing';
$dbskills[2] = '2H Blunt';
$dbskills[3] = '2H Slashing';
$dbskills[4] = 'Abjuration';
$dbskills[5] = 'Alteration';
$dbskills[6] = 'Apply Poison';
$dbskills[7] = 'Archery';
$dbskills[8] = 'Backstab';
$dbskills[9] = 'Bind Wound';
$dbskills[10] = 'Bash';
$dbskills[11] = 'Block';
$dbskills[12] = 'Brass Instruments';
$dbskills[13] = 'Channeling';
$dbskills[14] = 'Conjuration';
$dbskills[15] = 'Defense';
$dbskills[16] = 'Disarm';
$dbskills[17] = 'Disarm Traps';
$dbskills[18] = 'Divination';
$dbskills[19] = 'Dodge';
$dbskills[20] = 'Double Attack';
$dbskills[21] = 'Dragon Punch';
$dbskills[22] = 'Dual Wield';
$dbskills[23] = 'Eagle Strike';
$dbskills[24] = 'Evocation';
$dbskills[25] = 'Feign Death';
$dbskills[26] = 'Flying Kick';
$dbskills[27] = 'Forage';
$dbskills[28] = 'Hand to Hand';
$dbskills[29] = 'Hide';
$dbskills[30] = 'Kick';
$dbskills[31] = 'Meditate';
$dbskills[32] = 'Mend';
$dbskills[33] = 'Offense';
$dbskills[34] = 'Parry';
$dbskills[35] = 'Pick Lock';
$dbskills[36] = '1H Piercing';
$dbskills[37] = 'Riposte';
$dbskills[38] = 'Round Kick';
$dbskills[39] = 'Safe Fall';
$dbskills[40] = 'Sense Heading';
$dbskills[41] = 'Singing';
$dbskills[42] = 'Sneak';
$dbskills[43] = 'Specialize Abjuration';
$dbskills[44] = 'Specialize Alteration';
$dbskills[45] = 'Specialize Conjuration';
$dbskills[46] = 'Specialize Divination';
$dbskills[47] = 'Specialize Evocation';
$dbskills[48] = 'Pick Pocket';
$dbskills[49] = 'Stringed Instruments';
$dbskills[50] = 'Swimming';
$dbskills[51] = 'Throwing';
$dbskills[52] = 'Clicky';
$dbskills[53] = 'Tracking';
$dbskills[54] = 'Wind Instruments';
$dbskills[55] = 'Fishing';
$dbskills[56] = 'Poison Making';
$dbskills[57] = 'Tinkering';
$dbskills[58] = 'Research';
$dbskills[59] = 'Alchemy';
$dbskills[60] = 'Baking';
$dbskills[61] = 'Tailoring';
$dbskills[62] = 'Sense Traps';
$dbskills[63] = 'Blacksmithing';
$dbskills[64] = 'Fletching';
$dbskills[65] = 'Brewing';
$dbskills[66] = 'Alcohol Tolerance';
$dbskills[67] = 'Begging';
$dbskills[68] = 'Jewelry Making';
$dbskills[69] = 'Pottery';
$dbskills[70] = 'Percussion Instruments';
$dbskills[71] = 'Intimidation';
$dbskills[72] = 'Berserking';
$dbskills[73] = 'Taunt';
$dbskills[74] = 'Frenzy';
$dbskills[75] = 'Remove Traps';
$dbskills[76] = 'Triple Attack';
$dbskills[77] = '2H Piercing';

// spell effects
$dbspelleffects = [];
$dbspelleffects[0] = 'Increase Hitpoints'; // or decrease
$dbspelleffects[1] = 'Increase AC';
$dbspelleffects[2] = 'Increase ATK';
$dbspelleffects[3] = 'In/Decrease Movement';
$dbspelleffects[4] = 'Increase STR';
$dbspelleffects[5] = 'Increase DEX';
$dbspelleffects[6] = 'Increase AGI';
$dbspelleffects[7] = 'Increase STA';
$dbspelleffects[8] = 'Increase INT';
$dbspelleffects[9] = 'Increase WIS';
$dbspelleffects[10] = 'Increase CHA';
$dbspelleffects[11] = 'In/Decrease Attack Speed';
$dbspelleffects[12] = 'Invisibility';
$dbspelleffects[13] = 'See Invisible';
$dbspelleffects[14] = 'WaterBreathing';
$dbspelleffects[15] = 'Increase Mana';
$dbspelleffects[18] = 'Pacify';
$dbspelleffects[19] = 'Increase Faction';
$dbspelleffects[20] = 'Blindness';
$dbspelleffects[21] = 'Stun';
$dbspelleffects[22] = 'Charm';
$dbspelleffects[23] = 'Fear';
$dbspelleffects[24] = 'Stamina';
$dbspelleffects[25] = 'Bind Affinity';
$dbspelleffects[26] = 'Gate';
$dbspelleffects[27] = 'Cancel Magic';
$dbspelleffects[28] = 'Invisibility versus Undead';
$dbspelleffects[29] = 'Invisibility versus Animals';
$dbspelleffects[30] = 'Frenzy Radius';
$dbspelleffects[31] = 'Mesmerize';
$dbspelleffects[32] = 'Summon Item';
$dbspelleffects[33] = 'Summon Pet';
$dbspelleffects[35] = 'Increase Disease Counter';
$dbspelleffects[36] = 'Increase Poison Counter';
$dbspelleffects[40] = 'Invunerability';
$dbspelleffects[41] = 'Destroy Target';
$dbspelleffects[42] = 'Shadowstep';
$dbspelleffects[44] = 'Lycanthropy';
$dbspelleffects[46] = 'Increase Fire Resist';
$dbspelleffects[47] = 'Increase Cold Resist';
$dbspelleffects[48] = 'Increase Poison Resist';
$dbspelleffects[49] = 'Increase Disease Resist';
$dbspelleffects[50] = 'Increase Magic Resist';
$dbspelleffects[52] = 'Sense Undead';
$dbspelleffects[53] = 'Sense Summoned';
$dbspelleffects[54] = 'Sense Animals';
$dbspelleffects[55] = 'Increase Absorb Damage';
$dbspelleffects[56] = 'True North';
$dbspelleffects[57] = 'Levitate';
$dbspelleffects[58] = 'Illusion:';
$dbspelleffects[59] = 'Increase Damage Shield';
$dbspelleffects[61] = 'Identify';
$dbspelleffects[63] = 'Memblur';
$dbspelleffects[64] = 'SpinStun';
$dbspelleffects[65] = 'Infravision';
$dbspelleffects[66] = 'Ultravision';
$dbspelleffects[67] = 'Eye Of Zomm';
$dbspelleffects[68] = 'Reclaim Energy';
$dbspelleffects[69] = 'Increase Max Hitpoints';
$dbspelleffects[71] = 'Summon Pet';
$dbspelleffects[73] = 'Bind Sight';
$dbspelleffects[74] = 'Feign Death';
$dbspelleffects[75] = 'Voice Graft';
$dbspelleffects[76] = 'Sentinel';
$dbspelleffects[77] = 'Locate Corpse';
$dbspelleffects[78] = 'Increase Absorb Magic Damage';
$dbspelleffects[79] = 'Increase HP when cast';
$dbspelleffects[81] = 'Resurrect';
$dbspelleffects[82] = 'Summon PC';
$dbspelleffects[83] = 'Teleport';
$dbspelleffects[85] = 'Add Proc:';
$dbspelleffects[86] = 'Reaction Radius';
$dbspelleffects[87] = 'Increase Magnification';
$dbspelleffects[88] = 'Evacuate';
$dbspelleffects[89] = 'Increase Player Size';
$dbspelleffects[90] = 'Cloak';
$dbspelleffects[91] = 'Summon Corpse';
$dbspelleffects[92] = 'Increase hate';
$dbspelleffects[93] = 'Stop Rain';
$dbspelleffects[94] = 'Make Fragile (Delete if combat)';
$dbspelleffects[95] = 'Sacrifice';
$dbspelleffects[96] = 'Silence';
$dbspelleffects[97] = 'Increase Mana Pool';
$dbspelleffects[98] = 'Increase Haste v2';
$dbspelleffects[99] = 'Root';
$dbspelleffects[100] = 'Increase Hitpoints v2';
$dbspelleffects[101] = 'Complete Heal (with duration)';
$dbspelleffects[102] = 'Fearless';
$dbspelleffects[103] = 'Call Pet';
$dbspelleffects[104] = 'Translocate target to their bind point';
$dbspelleffects[105] = 'Anti-Gate';
$dbspelleffects[106] = 'Summon Warder:';
$dbspelleffects[108] = 'Summon Familiar:';
$dbspelleffects[109] = 'Summon Item v2';
$dbspelleffects[111] = 'Increase All Resists';
$dbspelleffects[112] = 'Increase Effective Casting Level';
$dbspelleffects[113] = 'Summon Horse:';
$dbspelleffects[114] = 'Increase Agro Multiplier';
$dbspelleffects[115] = 'Food/Water';
$dbspelleffects[116] = 'Decrease Curse Counter';
$dbspelleffects[117] = 'Make Weapons Magical';
$dbspelleffects[118] = 'Increase Singing Skill';
$dbspelleffects[119] = 'Increase Haste v3';
$dbspelleffects[120] = 'Set Healing Effectiveness';
$dbspelleffects[121] = 'Reverse Damage Shield';
$dbspelleffects[123] = 'Screech';
$dbspelleffects[124] = 'Increase Spell Damage';
$dbspelleffects[125] = 'Increase Spell Healing';
$dbspelleffects[127] = 'Increase Spell Haste';
$dbspelleffects[128] = 'Increase Spell Duration';
$dbspelleffects[129] = 'Increase Spell Range';
$dbspelleffects[130] = 'Decrease Spell/Bash Hate';
$dbspelleffects[131] = 'Decrease Chance of Using Reagent';
$dbspelleffects[132] = 'Decrease Spell Mana Cost';
$dbspelleffects[134] = 'Limit Maximum Level';
$dbspelleffects[135] = 'Limit Resist (Magic Allowed)';
$dbspelleffects[136] = 'Limit Target';
$dbspelleffects[137] = 'Limit Effect(Hitpoints Allowed)';
$dbspelleffects[138] = 'Limit Spell Type (Detrimental Only)';
$dbspelleffects[139] = 'Limit Spell';
$dbspelleffects[140] = 'Limit Minimum Duration';
$dbspelleffects[141] = 'Limit Instant spells only';
$dbspelleffects[142] = 'Limit Minimum Level';
$dbspelleffects[143] = 'Limit Minimum Casting Time';
$dbspelleffects[145] = 'Teleport v2';
$dbspelleffects[147] = 'Increase Hitpoints';
$dbspelleffects[148] = 'Block new spell';
$dbspelleffects[149] = 'Stacking: Overwrite existing spell';
$dbspelleffects[150] = 'Death Save - Restore Full Health';
$dbspelleffects[151] = 'Suspend Pet - Lose Buffs and Equipment';
$dbspelleffects[152] = 'Summon Pets:';
$dbspelleffects[153] = 'Balance Party Health';
$dbspelleffects[154] = 'Remove Detrimental';
$dbspelleffects[156] = 'Illusion: Target';
$dbspelleffects[157] = 'Spell-Damage Shield';
$dbspelleffects[158] = 'Increase Chance to Reflect Spell';
$dbspelleffects[159] = 'Decrease Stats';
$dbspelleffects[167] = 'Pet Power Increase';
$dbspelleffects[168] = 'Increase Melee Mitigation';
$dbspelleffects[169] = 'Increase Chance to Critical Hit';
$dbspelleffects[171] = 'CrippBlowChance';
$dbspelleffects[172] = 'Increase Chance to Avoid Melee';
$dbspelleffects[173] = 'Increase Chance to Riposte';
$dbspelleffects[174] = 'Increase Chance to Dodge';
$dbspelleffects[175] = 'Increase Chance to Parry';
$dbspelleffects[176] = 'Increase Chance to Dual Wield';
$dbspelleffects[177] = 'Increase Chance to Double Attack';
$dbspelleffects[178] = 'Lifetap from Weapon Damage';
$dbspelleffects[179] = 'Instrument Modifier';
$dbspelleffects[180] = 'Increase Chance to Resist Spell';
$dbspelleffects[181] = 'Increase Chance to Resist Fear Spell';
$dbspelleffects[182] = 'Hundred Hands Effect';
$dbspelleffects[183] = 'Increase All Skills Skill Check';
$dbspelleffects[184] = 'Increase Chance to Hit With all Skills';
$dbspelleffects[185] = 'Increase All Skills Damage Modifier';
$dbspelleffects[186] = 'Increase All Skills Minimum Damage Modifier';
$dbspelleffects[188] = 'Increase Chance to Block';
$dbspelleffects[192] = 'Increase Hate';
$dbspelleffects[194] = 'Fade';
$dbspelleffects[195] = 'Stun Resist';
$dbspelleffects[200] = 'Increase Proc Modifier';
$dbspelleffects[201] = 'Increase Range Proc Modifier';
$dbspelleffects[205] = 'Rampage';
$dbspelleffects[206] = 'Area of Effect Taunt';
$dbspelleffects[216] = 'Increase Accuracy';
$dbspelleffects[227] = 'Reduce Skill Timer';
$dbspelleffects[254] = 'Blank';
$dbspelleffects[266] = 'Increase Attack Chance';
$dbspelleffects[273] = 'Increase Critical Dot Chance';
$dbspelleffects[289] = 'Improved Spell Effect';
$dbspelleffects[294] = 'Increase Critial Spell Chance';
$dbspelleffects[299] = 'Wake the Dead';
$dbspelleffects[311] = 'Limit: Combat Skills Not Allowed';
$dbspelleffects[314] = 'Fixed Duration Invisbility (not documented on Lucy)';
$dbspelleffects[323] = 'Add Defensive Proc:';
$dbspelleffects[330] = 'Critical Damage Mob';


// spell targets
$dbspelltargets = [];
$dbspelltargets[1] = "";
$dbspelltargets[2] = "Area of Effect: Over the Caster";
$dbspelltargets[3] = "Group Teleport";
$dbspelltargets[4] = "Area of Effect: Around the Caster";
$dbspelltargets[5] = "Single Target";
$dbspelltargets[6] = "Self Only";
$dbspelltargets[8] = "Area of Effect: Around the Target";
$dbspelltargets[9] = "Animal";
$dbspelltargets[10] = "Undead Only";
$dbspelltargets[11] = "Summoned Only";
$dbspelltargets[13] = "Lifetap";
$dbspelltargets[14] = "Caster's Pet";
$dbspelltargets[15] = "Target's Corpse";
$dbspelltargets[16] = "Plant";
$dbspelltargets[17] = "Giant";
$dbspelltargets[18] = "Dragon";
$dbspelltargets[24] = "Area of Effect: On Undeads";
$dbspelltargets[36] = "Area of Effect: Clients Only";
$dbspelltargets[40] = "Area of Effect: Friendly";
$dbspelltargets[41] = "Group";

// Item Types
$dbitypes = array();
$dbitypes[0] = "1H Slashing";
$dbitypes[1] = "2H Slashing";
$dbitypes[2] = "1H Piercing";
$dbitypes[3] = "1H Blunt";
$dbitypes[4] = "2H Blunt";
$dbitypes[5] = "Archery";
$dbitypes[7] = "Throwing";
$dbitypes[8] = "Shield";
$dbitypes[10] = "Armor";
$dbitypes[11] = "Gem";
$dbitypes[12] = "Lockpick";
$dbitypes[14] = "Food";
$dbitypes[15] = "Drink";
$dbitypes[16] = "Light";
$dbitypes[17] = "Combinable";
$dbitypes[18] = "Bandages";
$dbitypes[19] = "Throwing";
$dbitypes[20] = "Scroll";
$dbitypes[21] = "Potion";
$dbitypes[23] = "Wind Instrument";
$dbitypes[24] = "Stringed Instrument";
$dbitypes[25] = "Brass Instrument";
$dbitypes[26] = "Percussion Instrument";
$dbitypes[27] = "Arrow";
$dbitypes[29] = "Jewelry";
$dbitypes[30] = "Skull";
$dbitypes[31] = "Tome";
$dbitypes[32] = "Note";
$dbitypes[33] = "Key";
$dbitypes[34] = "Coin";
$dbitypes[35] = "2H Piercing";
$dbitypes[36] = "Fishing Pole";
$dbitypes[37] = "Fishing Bait";
$dbitypes[38] = "Alcohol";
$dbitypes[39] = "Key (bis)";
$dbitypes[40] = "Compass";
$dbitypes[42] = "Poison";
$dbitypes[45] = "Martial";
$dbitypes[52] = "Charm";
$dbitypes[54] = "Augmentation";

$dbiaugrestrict[1] = "Armor Only";
$dbiaugrestrict[2] = "Weapons Only";
$dbiaugrestrict[3] = "1H Weapons Only";
$dbiaugrestrict[4] = "2H Weapons Only";
$dbiaugrestrict[5] = "1H Slashing Only";
$dbiaugrestrict[6] = "1H Blunt Only";
$dbiaugrestrict[7] = "1H Piercing Only";
$dbiaugrestrict[8] = "Hand-to-Hand Only";
$dbiaugrestrict[9] = "2H Slashing Only";
$dbiaugrestrict[10] = "2H Blunt Only";
$dbiaugrestrict[11] = "2H Piercing Only";
$dbiaugrestrict[12] = "Ranged Weapons Only";
$dbiaugrestrict[13] = "Shields Only";
$dbiaugrestrict[14] = "1H Slashing Weapons, 1H Blunt Weapons, or Hand-to-Hand Weapons Only";
$dbiaugrestrict[15] = "1H Blunt Weapons, or Hand-to-Hand Weapons Only";

$dbbardskills[23] = "Wind Instruments";
$dbbardskills[24] = "Strings Instruments";
$dbbardskills[25] = "Brass Instruments";
$dbbardskills[26] = "Percussion Instruments";
$dbbardskills[51] = "All Instruments";

$NPCTypeArray = [
    '###' => 'Boss',
    '##' => 'Mini-Boss',
    '#' => 'Named',
    '~' => 'Quest NPC',
    '!' => 'Hidden',
    '_' => 'Event Spawned',
];

// deities
$dbdeities = [];
$dbdeities[0] = "Unknown";
$dbdeities[201] = "Bertoxxulous";
$dbdeities[202] = "Brell Serilis";
$dbdeities[203] = "Cazic Thule";
$dbdeities[204] = "Erollisi Marr";
$dbdeities[205] = "Bristlebane";
$dbdeities[206] = "Innoruuk";
$dbdeities[207] = "Karana";
$dbdeities[208] = "Mithaniel Marr";
$dbdeities[209] = "Prexus";
$dbdeities[210] = "Quellious";
$dbdeities[211] = "Rallos Zek";
$dbdeities[213] = "Solusek Ro";
$dbdeities[212] = "Rodcet Nife";
$dbdeities[215] = "Tunare";
$dbdeities[214] = "The Tribunal";
$dbdeities[216] = "Veeshan";
$dbdeities[396] = "Agnostic";

// deities (items)
$dbideities = [];
$dbideities[65536] = "Veeshan";
$dbideities[32768] = "Tunare";
$dbideities[16384] = "The Tribunal";
$dbideities[8192] = "Solusek Ro";
$dbideities[4096] = "Rodcet Nife";
$dbideities[2048] = "Rallos Zek";
$dbideities[1024] = "Quellious";
$dbideities[512] = "Prexus";
$dbideities[256] = "Mithaniel Marr";
$dbideities[128] = "Karana";
$dbideities[64] = "Innoruuk";
$dbideities[32] = "Bristlebane";
$dbideities[16] = "Erollisi Marr";
$dbideities[8] = "Cazic Thule";
$dbideities[4] = "Brell Serilis";
$dbideities[2] = "Bertoxxulous";

// elements
$dbelements = ["Unknown", "Magic", "Fire", "Cold", "Poison", "Disease", "Corruption"];

// damage bonuses 2Hands at 65
//http://lucy.allakhazam.com/dmgbonus.html
$dam2h = [
    0,
    14,
    14,
    14,
    14,
    14,
    14,
    14,
    14,
    14, // 0->9
    14,
    14,
    14,
    14,
    14,
    14,
    14,
    14,
    14,
    14, // 10->19
    14,
    14,
    14,
    14,
    14,
    14,
    14,
    14,
    35,
    35, // 20->29
    36,
    36,
    37,
    37,
    38,
    38,
    39,
    39,
    40,
    40, // 30->39
    42,
    42,
    42,
    45,
    45,
    47,
    48,
    49,
    49,
    51, // 40->49
    51,
    52,
    53,
    54,
    54,
    56,
    56,
    57,
    58,
    59, // 50->59
    59,
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    0, // 60->69
    68,
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    0, // 70->79
    0,
    0,
    0,
    0,
    0,
    80,
    0,
    0,
    0,
    0, // 80->89
    0,
    0,
    0,
    0,
    0,
    88,
    0,
    0,
    0,
    0, // 90->99
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    0, // 100->109
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    0, // 110->119
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    0, // 120->129
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    0, // 130->139
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    0, // 140->149
    132,
]; // 150

// Body types (bodytypes.h)
$dbbodytypes = [
    "Unknown", // 0
    "Humanoid", // 1
    "Lycanthrope", // 2
    "Undead",  // 3
    "Giant", // 4
    "Construct", // 5
    "Extra planar", //6
    "Magical", // 7
    "Summoned undead", // 8
    "Unknown", //9
    "Unknown", //10
    "No target", //11
    "Vampire", //12
    "Atenha Ra", // 13
    "Greater Akheva", // 14
    "Khati Sha", // 15
    "Unknown", //16
    "Unknown", //17
    "Unknown", //18
    "Zek", // 19
    "Unkownn", // 20
    "Animal", // 21
    "Insect", // 22
    "Monster", // 23
    "Summoned", // 24
    "Plant", // 25
    "Dragon", // 26
    "Summoned 2", // 27
    "Summoned 3", // 28
    "Unknown", //29
    "Velious Dragon", //30
    "Unknown", //31
    "Dragon 3", //32
    "Boxes", //33
    "Discord Mob",
]; //34
$dbbodytypes[60] = "No Target 2";
$dbbodytypes[63] = "Swarm pet";
$dbbodytypes[67] = "Special";

$dbbagtypes = [
    9 => "Alchemy",
    10 => "Tinkering",
    12 => "Poison Making",
    13 => "Special Quests",
    14 => "Baking",
    15 => "Baking",
    16 => "Tailoring",
    18 => "Fletching",
    20 => "Jewelry",
    30 => "Pottery",
    24 => "Research",
    25 => "Research",
    26 => "Research",
    27 => "Research",
    46 => "Fishing",
];

$dbspelltypes = [
    1 => "Nuke",
    2 => "Heal",
    4 => "Root",
    8 => "Buff",
    16 => "Escape",
    32 => "Pet",
    64 => "Lifetap",
    128 => "Snare",
    256 => "Dot",
];

$dbspellresists = [
    0 => "None",
    1 => "Magic",
    2 => "Fire",
    3 => "Cold",
    4 => "Poison",
    5 => "Disease",
    6 => "Chromatic",
    7 => "Prismatic",
    8 => "Physical",
];

// Array of ALL races through VoA Expansion

$dbiracenames = array(
	1 => "Human",
    2 => "Barbarian",
    3 => "Erudite",
    4 => "Wood Elf",
    5 => "High Elf",
    6 => "Dark Elf",
    7 => "Half Elf",
    8 => "Dwarf",
    9 => "Troll",
    10 => "Ogre",
    11 => "Halfling",
    12 => "Gnome",
    13 => "Aviak",
    14 => "Werewolf",
    15 => "Brownie",
    16 => "Centaur",
    17 => "Golem",
    18 => "Giant",
    19 => "Trakanon",
    20 => "Venril Sathir",
    21 => "Evil Eye",
    22 => "Beetle",
    23 => "Kerran",
    24 => "Fish",
    25 => "Fairy",
    26 => "Froglok",
    27 => "Froglok",
    28 => "Fungusman",
    29 => "Gargoyle",
    30 => "Gasbag",
    31 => "Gelatinous Cube",
    32 => "Ghost",
    33 => "Ghoul",
    34 => "Bat",
    35 => "Eel",
    36 => "Rat",
    37 => "Snake",
    38 => "Spider",
    39 => "Gnoll",
    40 => "Goblin",
    41 => "Gorilla",
    42 => "Wolf",
    43 => "Bear",
    44 => "Guard",
    45 => "Demi Lich",
    46 => "Imp",
    47 => "Griffin",
    48 => "Kobold",
    49 => "Dragon",
    50 => "Lion",
    51 => "Lizard Man",
    52 => "Mimic",
    53 => "Minotaur",
    54 => "Orc",
    55 => "Beggar",
    56 => "Pixie",
    57 => "Drachnid",
    58 => "Solusek Ro",
    59 => "Goblin",
    60 => "Skeleton",
    61 => "Shark",
    62 => "Tunare",
    63 => "Tiger",
    64 => "Treant",
    65 => "Vampire",
    66 => "Rallos Zek",
    67 => "Human",
    68 => "Tentacle Terror",
    69 => "Will-O-Wisp",
    70 => "Zombie",
    71 => "Human",
    72 => "Ship",
    73 => "Launch",
    74 => "Piranha",
    75 => "Elemental",
    76 => "Puma",
    77 => "Dark Elf",
    78 => "Erudite",
    79 => "Bixie",
    80 => "Reanimated Hand",
    81 => "Halfling",
    82 => "Scarecrow",
    83 => "Skunk",
    84 => "Snake Elemental",
    85 => "Spectre",
    86 => "Sphinx",
    87 => "Armadillo",
    88 => "Clockwork Gnome",
    89 => "Drake",
    90 => "Barbarian",
    91 => "Alligator",
    92 => "Troll",
    93 => "Ogre",
    94 => "Dwarf",
    95 => "Cazic Thule",
    96 => "Cockatrice",
    97 => "Daisy Man",
    98 => "Vampire",
    99 => "Amygdalan",
    100 => "Dervish",
    101 => "Efreeti",
    102 => "Tadpole",
    103 => "Kedge",
    104 => "Leech",
    105 => "Swordfish",
    106 => "Guard",
    107 => "Mammoth",
    108 => "Eye",
    109 => "Wasp",
    110 => "Mermaid",
    111 => "Harpy",
    112 => "Guard",
    113 => "Drixie",
    114 => "Ghost Ship",
    115 => "Clam",
    116 => "Seahorse",
    117 => "Ghost",
    118 => "Ghost",
    119 => "Sabertooth",
    120 => "Wolf",
    121 => "Gorgon",
    122 => "Dragon",
    123 => "Innoruuk",
    124 => "Unicorn",
    125 => "Pegasus",
    126 => "Djinn",
    127 => "Invisible Man",
    128 => "Iksar",
    129 => "Scorpion",
    130 => "Vah Shir",
    131 => "Sarnak",
    132 => "Draglock",
    133 => "Drolvarg",
    134 => "Mosquito",
    135 => "Rhinoceros",
    136 => "Xalgoz",
    137 => "Goblin",
    138 => "Yeti",
    139 => "Iksar",
    140 => "Giant",
    141 => "Boat",
    144 => "Burynai",
    145 => "Goo",
    146 => "Sarnak Spirit",
    147 => "Iksar Spirit",
    148 => "Fish",
    149 => "Scorpion",
    150 => "Erollisi",
    151 => "Tribunal",
    152 => "Bertoxxulous",
    153 => "Bristlebane",
    154 => "Fay Drake",
    155 => "Undead Sarnak",
    156 => "Ratman",
    157 => "Wyvern",
    158 => "Wurm",
    159 => "Devourer",
    160 => "Iksar Golem",
    161 => "Undead Iksar",
    162 => "Man-Eating Plant",
    163 => "Raptor",
    164 => "Sarnak Golem",
    165 => "Dragon",
    166 => "Animated Hand",
    167 => "Succulent",
    168 => "Holgresh",
    169 => "Brontotherium",
    170 => "Snow Dervish",
    171 => "Dire Wolf",
    172 => "Manticore",
    173 => "Totem",
    174 => "Ice Spectre",
    175 => "Enchanted Armor",
    176 => "Snow Rabbit",
    177 => "Walrus",
    178 => "Geonid",
    181 => "Yakkar",
    182 => "Faun",
    183 => "Coldain",
    184 => "Dragon",
    185 => "Hag",
    186 => "Hippogriff",
    187 => "Siren",
    188 => "Giant",
    189 => "Giant",
    190 => "Othmir",
    191 => "Ulthork",
    192 => "Dragon",
    193 => "Abhorrent",
    194 => "Sea Turtle",
    195 => "Dragon",
    196 => "Dragon",
    197 => "Ronnie Test",
    198 => "Dragon",
    199 => "Shik'Nar",
    200 => "Rockhopper",
    201 => "Underbulk",
    202 => "Grimling",
    203 => "Worm",
    204 => "Evan Test",
    205 => "Shadel",
    206 => "Owlbear",
    207 => "Rhino Beetle",
    208 => "Vampire",
    209 => "Earth Elemental",
    210 => "Air Elemental",
    211 => "Water Elemental",
    212 => "Fire Elemental",
    213 => "Wetfang Minnow",
    214 => "Thought Horror",
    215 => "Tegi",
    216 => "Horse",
    217 => "Shissar",
    218 => "Fungal Fiend",
    219 => "Vampire",
    220 => "Stonegrabber",
    221 => "Scarlet Cheetah",
    222 => "Zelniak",
    223 => "Lightcrawler",
    224 => "Shade",
    225 => "Sunfbelow",
    226 => "Sun Revenant",
    227 => "Shrieker",
    228 => "Galorian",
    229 => "Netherbian",
    230 => "Akheva",
    231 => "Grieg Veneficus",
    232 => "Sonic Wolf",
    233 => "Ground Shaker",
    234 => "Vah Shir Skeleton",
    235 => "Wretch",
    236 => "Seru",
    237 => "Recuso",
    238 => "Vah Shir",
    239 => "Guard",
    240 => "Teleport Man",
    241 => "Werewolf",
    242 => "Nymph",
    243 => "Dryad",
    244 => "Treant",
    245 => "Fly",
    246 => "Tarew Marr",
    247 => "Solusek Ro",
    248 => "Clockwork Golem",
    249 => "Clockwork Brain",
    250 => "Banshee",
    251 => "Guard of Justice",
    252 => "Mini POM",
    253 => "Diseased Fiend",
    254 => "Solusek Ro Guard",
    255 => "Bertoxxulous",
    256 => "The Tribunal",
    257 => "Terris Thule",
    258 => "Vegerog",
    259 => "Crocodile",
    260 => "Bat",
    261 => "Hraquis",
    262 => "Tranquilion",
    263 => "Tin Soldier",
    264 => "Nightmare Wraith",
    265 => "Malarian",
    266 => "Knight of Pestilence",
    267 => "Lepertoloth",
    268 => "Bubonian",
    269 => "Bubonian Underling",
    270 => "Pusling",
    271 => "Water Mephit",
    272 => "Stormrider",
    273 => "Junk Beast",
    274 => "Broken Clockwork",
    275 => "Giant Clockwork",
    276 => "Clockwork Beetle",
    277 => "Nightmare Goblin",
    278 => "Karana",
    279 => "Blood Raven",
    280 => "Nightmare Gargoyle",
    281 => "Mouth of Insanity",
    282 => "Skeletal Horse",
    283 => "Saryrn",
    284 => "Fennin Ro",
    285 => "Tormentor",
    286 => "Soul Devourer",
    287 => "Nightmare",
    288 => "Rallos Zek",
    289 => "Vallon Zek",
    290 => "Tallon Zek",
    291 => "Air Mephit",
    292 => "Earth Mephit",
    293 => "Fire Mephit",
    294 => "Nightmare Mephit",
    295 => "Zebuxoruk",
    296 => "Mithaniel Marr",
    297 => "Undead Knight",
    298 => "The Rathe",
    299 => "Xegony",
    300 => "Fiend",
    301 => "Test Object",
    302 => "Crab",
    303 => "Phoenix",
    304 => "Dragon",
    305 => "Bear",
    306 => "Giant",
    307 => "Giant",
    308 => "Giant",
    309 => "Giant",
    310 => "Giant",
    311 => "Giant",
    312 => "Giant",
    313 => "War Wraith",
    314 => "Wrulon",
    315 => "Kraken",
    316 => "Poison Frog",
    317 => "Nilborien",
    318 => "Valorian",
    319 => "War Boar",
    320 => "Efreeti",
    321 => "War Boar",
    322 => "Valorian",
    323 => "Animated Armor",
    324 => "Undead Footman",
    325 => "Rallos Zek Minion",
    326 => "Arachnid",
    327 => "Crystal Spider",
    328 => "Zebuxoruk's Cage",
    329 => "BoT Portal",
    330 => "Froglok",
    331 => "Troll",
    332 => "Troll",
    333 => "Troll",
    334 => "Ghost",
    335 => "Pirate",
    336 => "Pirate",
    337 => "Pirate",
    338 => "Pirate",
    339 => "Pirate",
    340 => "Pirate",
    341 => "Pirate",
    342 => "Pirate",
    343 => "Frog",
    344 => "Troll Zombie",
    345 => "Luggald",
    346 => "Luggald",
    347 => "Luggalds",
    348 => "Drogmore",
    349 => "Froglok Skeleton",
    350 => "Undead Froglok",
    351 => "Knight of Hate",
    352 => "Arcanist of Hate",
    353 => "Veksar",
    354 => "Veksar",
    355 => "Veksar",
    356 => "Chokidai",
    357 => "Undead Chokidai",
    358 => "Undead Veksar",
    359 => "Vampire",
    360 => "Vampire",
    361 => "Rujarkian Orc",
    362 => "Bone Golem",
    363 => "Synarcana",
    364 => "Sand Elf",
    365 => "Vampire",
    366 => "Rujarkian Orc",
    367 => "Skeleton",
    368 => "Mummy",
    369 => "Goblin",
    370 => "Insect",
    371 => "Froglok Ghost",
    372 => "Dervish",
    373 => "Shade",
    374 => "Golem",
    375 => "Evil Eye",
    376 => "Box",
    377 => "Barrel",
    378 => "Chest",
    379 => "Vase",
    380 => "Table",
    381 => "Weapon Rack",
    382 => "Coffin",
    383 => "Bones",
    384 => "Jokester",
    385 => "Nihil",
    386 => "Trusik",
    387 => "Stone Worker",
    388 => "Hynid",
    389 => "Turepta",
    390 => "Cragbeast",
    391 => "Stonemite",
    392 => "Ukun",
    393 => "Ixt",
    394 => "Ikaav",
    395 => "Aneuk",
    396 => "Kyv",
    397 => "Noc",
    398 => "Ra`tuk",
    399 => "Taneth",
    400 => "Huvul",
    401 => "Mutna",
    402 => "Mastruq",
    403 => "Taelosian",
    404 => "Discord Ship",
    405 => "Stone Worker",
    406 => "Mata Muram",
    407 => "Lightning Warrior",
    408 => "Succubus",
    409 => "Bazu",
    410 => "Feran",
    411 => "Pyrilen",
    412 => "Chimera",
    413 => "Dragorn",
    414 => "Murkglider",
    415 => "Rat",
    416 => "Bat",
    417 => "Gelidran",
    418 => "Discordling",
    419 => "Girplan",
    420 => "Minotaur",
    421 => "Dragorn Box",
    422 => "Runed Orb",
    423 => "Dragon Bones",
    424 => "Muramite Armor Pile",
    425 => "Crystal Shard",
    426 => "Portal",
    427 => "Coin Purse",
    428 => "Rock Pile",
    429 => "Murkglider Egg Sack",
    430 => "Drake",
    431 => "Dervish",
    432 => "Drake",
    433 => "Goblin",
    434 => "Kirin",
    435 => "Dragon",
    436 => "Basilisk",
    437 => "Dragon",
    438 => "Dragon",
    439 => "Puma",
    440 => "Spider",
    441 => "Spider Queen",
    442 => "Animated Statue",
    445 => "Dragon Egg",
    446 => "Dragon Statue",
    447 => "Lava Rock",
    448 => "Animated Statue",
    449 => "Spider Egg Sack",
    450 => "Lava Spider",
    451 => "Lava Spider Queen",
    452 => "Dragon",
    453 => "Giant",
    454 => "Werewolf",
    455 => "Kobold",
    456 => "Sporali",
    457 => "Gnomework",
    458 => "Orc",
    459 => "Corathus",
    460 => "Coral",
    461 => "Drachnid",
    462 => "Drachnid Cocoon",
    463 => "Fungus Patch",
    464 => "Gargoyle",
    465 => "Witheran",
    466 => "Dark Lord",
    467 => "Shiliskin",
    468 => "Snake",
    469 => "Evil Eye",
    470 => "Minotaur",
    471 => "Zombie",
    472 => "Clockwork Boar",
    473 => "Fairy",
    474 => "Witheran",
    475 => "Air Elemental",
    476 => "Earth Elemental",
    477 => "Fire Elemental",
    478 => "Water Elemental",
    479 => "Alligator",
    480 => "Bear",
    481 => "Scaled Wolf",
    482 => "Wolf",
    483 => "Spirit Wolf",
    484 => "Skeleton",
    485 => "Spectre",
    486 => "Bolvirk",
    487 => "Banshee",
    488 => "Banshee",
    489 => "Elddar",
    490 => "Forest Giant",
    491 => "Bone Golem",
    492 => "Horse",
    493 => "Pegasus",
    494 => "Shambling Mound",
    495 => "Scrykin",
    496 => "Treant",
    497 => "Vampire",
    498 => "Ayonae Ro",
    499 => "Sullon Zek",
    500 => "Banner",
    501 => "Flag",
    502 => "Rowboat",
    503 => "Bear Trap",
    504 => "Clockwork Bomb",
    505 => "Dynamite Keg",
    506 => "Pressure Plate",
    507 => "Puffer Spore",
    508 => "Stone Ring",
    509 => "Root Tentacle",
    510 => "Runic Symbol",
    511 => "Saltpetter Bomb",
    512 => "Floating Skull",
    513 => "Spike Trap",
    514 => "Totem",
    515 => "Web",
    516 => "Wicker Basket",
    517 => "Nightmare/Unicorn",
    518 => "Horse",
    519 => "Nightmare/Unicorn",
    520 => "Bixie",
    521 => "Centaur",
    522 => "Drakkin",
    523 => "Giant",
    524 => "Gnoll",
    525 => "Griffin",
    526 => "Giant Shade",
    527 => "Harpy",
    528 => "Mammoth",
    529 => "Satyr",
    530 => "Dragon",
    531 => "Dragon",
    532 => "Dyn'Leth",
    533 => "Boat",
    534 => "Weapon Rack",
    535 => "Armor Rack",
    536 => "Honey Pot",
    537 => "Jum Jum Bucket",
    538 => "Toolbox",
    539 => "Stone Jug",
    540 => "Small Plant",
    541 => "Medium Plant",
    542 => "Tall Plant",
    543 => "Wine Cask",
    544 => "Elven Boat",
    545 => "Gnomish Boat",
    546 => "Barrel Barge Ship",
    547 => "Goo",
    548 => "Goo",
    549 => "Goo",
    550 => "Merchant Ship",
    551 => "Pirate Ship",
    552 => "Ghost Ship",
    553 => "Banner",
    554 => "Banner",
    555 => "Banner",
    556 => "Banner",
    557 => "Banner",
    558 => "Aviak",
    559 => "Beetle",
    560 => "Gorilla",
    561 => "Kedge",
    562 => "Kerran",
    563 => "Shissar",
    564 => "Siren",
    565 => "Sphinx",
    566 => "Human",
    567 => "Campfire",
    568 => "Brownie",
    569 => "Dragon",
    570 => "Exoskeleton",
    571 => "Ghoul",
    572 => "Clockwork Guardian",
    573 => "Mantrap",
    574 => "Minotaur",
    575 => "Scarecrow",
    576 => "Shade",
    577 => "Rotocopter",
    578 => "Tentacle Terror",
    579 => "Wereorc",
    580 => "Worg",
    581 => "Wyvern",
    582 => "Chimera",
    583 => "Kirin",
    584 => "Puma",
    585 => "Boulder",
    586 => "Banner",
    587 => "Elven Ghost",
    588 => "Human Ghost",
    589 => "Chest",
    590 => "Chest",
    591 => "Crystal",
    592 => "Coffin",
    593 => "Guardian CPU",
    594 => "Worg",
    595 => "Mansion",
    596 => "Floating Island",
    597 => "Cragslither",
    598 => "Wrulon",
    600 => "Invisible Man of Zomm",
    601 => "Robocopter of Zomm",
    602 => "Burynai",
    603 => "Frog",
    604 => "Dracolich",
    605 => "Iksar Ghost",
    606 => "Iksar Skeleton",
    607 => "Mephit",
    608 => "Muddite",
    609 => "Raptor",
    610 => "Sarnak",
    611 => "Scorpion",
    612 => "Tsetsian",
    613 => "Wurm",
    614 => "Nekhon",
    615 => "Hydra Crystal",
    616 => "Crystal Sphere",
    617 => "Gnoll",
    618 => "Sokokar",
    619 => "Stone Pylon",
    620 => "Demon Vulture",
    621 => "Wagon",
    622 => "God of Discord",
    623 => "Feran Mount",
    624 => "Ogre NPC",
    625 => "Sokokar Mount",
    626 => "Giant",
    627 => "Sokokar",
    628 => "10th Anniversary Banner",
    629 => "10th Anniversary Cake",
    630 => "Wine Cask",
    631 => "Hydra Mount",
    632 => "Hydra NPC",
    633 => "Wedding Fbelows",
    634 => "Wedding Arbor",
    635 => "Wedding Altar",
    636 => "Powder Keg",
    637 => "Apexus",
    638 => "Bellikos",
    639 => "Brell's First Creation",
    640 => "Brell",
    641 => "Crystalskin Ambuloid",
    642 => "Cliknar Queen",
    643 => "Cliknar Soldier",
    644 => "Cliknar Worker",
    645 => "Coldain",
    646 => "Coldain",
    647 => "Crystalskin Sessiloid",
    648 => "Genari",
    649 => "Gigyn",
    650 => "Greken",
    651 => "Greken",
    652 => "Cliknar Mount",
    653 => "Telmira",
    654 => "Spider Mount",
    655 => "Bear Mount",
    656 => "Rat Mount",
    657 => "Sessiloid Mount",
    658 => "Morell Thule",
    659 => "Marionette",
    660 => "Book Dervish",
    661 => "Topiary Lion",
    662 => "Rotdog",
    663 => "Amygdalan",
    664 => "Sandman",
    665 => "Grandfather Clock",
    666 => "Gingerbread Man",
    667 => "Royal Guard",
    668 => "Rabbit",
    669 => "Blind Dreamer",
    670 => "Cazic Thule",
    671 => "Topiary Lion Mount",
    672 => "Rot Dog Mount",
    673 => "Goral Mount",
    674 => "Selyrah Mount",
    675 => "Sclera Mount",
    676 => "Braxi Mount",
    677 => "Kangon Mount",
    678 => "Erudite",
    679 => "Wurm Mount",
    680 => "Raptor Mount",
    681 => "Invisible Man",
    682 => "Whirligig",
    683 => "Gnomish Balloon",
    684 => "Gnomish Rocket Pack",
    685 => "Gnomish Hovering Transport",
    686 => "Selyrah",
    687 => "Goral",
    688 => "Braxi",
    689 => "Kangon",
    690 => "Invisible Man",
    691 => "Floating Tower",
    692 => "Explosive Cart",
    693 => "Blimp Ship",
    694 => "Tumbleweed",
    695 => "Alaran",
    696 => "Swinetor",
    697 => "Triumvirate",
    698 => "Hadal",
    699 => "Hovering Platform",
    700 => "Parasitic Scavenger",
    701 => "Grendlaen",
    702 => "Ship in a Bottle",
    703 => "Alaran Sentry Stone",
    704 => "Dervish",
    705 => "Regeneration Pool",
    706 => "Teleportation Stand",
    707 => "Relic Case",
    708 => "Alaran Ghost",
    709 => "Skystrider",
    710 => "Water Spout",
    711 => "Aviak Pull Along",
    712 => "Gelatinous Cube",
    713 => "Cat",
    714 => "Elk Head",
    715 => "Holgresh",
    716 => "Beetle",
    717 => "Vine Maw",
    718 => "Ratman",
    719 => "Fallen Knight",
    720 => "Flying Carpet",
    721 => "Carrier Hand",
    722 => "Akheva",
    723 => "Servant of Shadow",
    724 => "Luclin"
);

$dbizonenames = array(
	1 => array("qeynos", "South Qeynos"),
	2 => array("qeynos2", "North Qeynos"),
	3 => array("qrg", "The Surefall Glade"),
	4 => array("qeytoqrg", "The Qeynos Hills"),
	5 => array("highpass", "Highpass Hold"),
	6 => array("highkeep", "High Keep"),
	8 => array("freportn", "North Freeport"),
	9 => array("freportw", "West Freeport"),
	10 => array("freporte", "East Freeport"),
	11 => array("runnyeye", "The Liberated Citadel of Runnyeye"),
	12 => array("qey2hh1", "The Western Plains of Karana"),
	13 => array("northkarana", "The Northern Plains of Karana"),
	14 => array("southkarana", "The Southern Plains of Karana"),
	15 => array("eastkarana", "Eastern Plains of Karana"),
	16 => array("beholder", "Gorge of King Xorbb"),
	17 => array("blackburrow", "Blackburrow"),
	18 => array("paw", "The Lair of the Splitpaw"),
	19 => array("rivervale", "Rivervale"),
	20 => array("kithicor", "Kithicor Forest"),
	21 => array("commons", "West Commonlands"),
	22 => array("ecommons", "East Commonlands"),
	23 => array("erudnint", "The Erudin Palace"),
	24 => array("erudnext", "Erudin"),
	25 => array("nektulos", "The Nektulos Forest"),
	25 => array("nektulos", "The Nektulos Forest"),
	26 => array("cshome", "Sunset Home"),
	27 => array("lavastorm", "The Lavastorm Mountains"),
	28 => array("nektropos", "Nektropos"),
	29 => array("halas", "Halas"),
	30 => array("everfrost", "Everfrost Peaks"),
	31 => array("soldunga", "Solusek's Eye"),
	32 => array("soldungb", "Nagafen's Lair"),
	33 => array("misty", "Misty Thicket"),
	34 => array("nro", "Northern Desert of Ro"),
	35 => array("sro", "Southern Desert of Ro"),
	36 => array("befallen", "Befallen"),
	37 => array("oasis", "Oasis of Marr"),
	38 => array("tox", "Toxxulia Forest"),
	39 => array("hole", "The Hole"),
	40 => array("neriaka", "Neriak - Foreign Quarter"),
	41 => array("neriakb", "Neriak - Commons"),
	42 => array("neriakc", "Neriak - 3rd Gate"),
	43 => array("neriakd", "Neriak Palace"),
	44 => array("najena", "Najena"),
	45 => array("qcat", "The Qeynos Aqueduct System"),
	46 => array("innothule", "Innothule Swamp"),
	47 => array("feerrott", "The Feerrott"),
	48 => array("cazicthule", "Accursed Temple of Cazic Thule"),
	49 => array("oggok", "Oggok"),
	50 => array("rathemtn", "The Rathe Mountains"),
	51 => array("lakerathe", "Lake Rathetear"),
	52 => array("grobb", "Grobb"),
	53 => array("aviak", "Aviak Village"),
	54 => array("gfaydark", "The Greater Faydark"),
	55 => array("akanon", "Ak'Anon"),
	56 => array("steamfont", "Steamfont Mountains"),
	57 => array("lfaydark", "The Lesser Faydark"),
	58 => array("crushbone", "Crushbone"),
	59 => array("mistmoore", "The Castle of Mistmoore"),
	60 => array("kaladima", "South Kaladim"),
	61 => array("felwithea", "Northern Felwithe"),
	62 => array("felwitheb", "Southern Felwithe"),
	63 => array("unrest", "The Estate of Unrest"),
	64 => array("kedge", "Kedge Keep"),
	65 => array("guktop", "The City of Guk"),
	66 => array("gukbottom", "The Ruins of Old Guk"),
	67 => array("kaladimb", "North Kaladim"),
	68 => array("butcher", "Butcherblock Mountains"),
	69 => array("oot", "Ocean of Tears"),
	70 => array("cauldron", "Dagnor's Cauldron"),
	71 => array("airplane", "The Plane of Sky"),
	72 => array("fearplane", "The Plane of Fear"),
	73 => array("permafrost", "The Permafrost Caverns"),
	74 => array("kerraridge", "Kerra Isle"),
	75 => array("paineel", "Paineel"),
	76 => array("hateplane", "Plane of Hate"),
	77 => array("arena", "The Arena"),
	78 => array("fieldofbone", "The Field of Bone"),
	79 => array("warslikswood", "The Warsliks Woods"),
	80 => array("soltemple", "The Temple of Solusek Ro"),
	81 => array("droga", "The Temple of Droga"),
	82 => array("cabwest", "Cabilis West"),
	83 => array("swampofnohope", "The Swamp of No Hope"),
	84 => array("firiona", "Firiona Vie"),
	85 => array("lakeofillomen", "Lake of Ill Omen"),
	86 => array("dreadlands", "The Dreadlands"),
	87 => array("burningwood", "The Burning Wood"),
	88 => array("kaesora", "Kaesora"),
	89 => array("sebilis", "The Ruins of Sebilis"),
	90 => array("citymist", "The City of Mist"),
	91 => array("skyfire", "The Skyfire Mountains"),
	92 => array("frontiermtns", "Frontier Mountains"),
	93 => array("overthere", "The Overthere"),
	94 => array("emeraldjungle", "The Emerald Jungle"),
	95 => array("trakanon", "Trakanon's Teeth"),
	96 => array("timorous", "Timorous Deep"),
	97 => array("kurn", "Kurn's Tower"),
	98 => array("erudsxing", "Erud's Crossing"),
	100 => array("stonebrunt", "The Stonebrunt Mountains"),
	101 => array("warrens", "The Warrens"),
	102 => array("karnor", "Karnor's Castle"),
	103 => array("chardok", "Chardok"),
	104 => array("dalnir", "The Crypt of Dalnir"),
	105 => array("charasis", "The Howling Stones"),
	106 => array("cabeast", "Cabilis East"),
	107 => array("nurga", "The Mines of Nurga"),
	108 => array("veeshan", "Veeshan's Peak"),
	109 => array("veksar", "Veksar"),
	110 => array("iceclad", "The Iceclad Ocean"),
	111 => array("frozenshadow", "The Tower of Frozen Shadow"),
	112 => array("velketor", "Velketor's Labyrinth"),
	113 => array("kael", "Kael Drakkel"),
	114 => array("skyshrine", "Skyshrine"),
	115 => array("thurgadina", "The City of Thurgadin"),
	116 => array("eastwastes", "Eastern Wastes"),
	117 => array("cobaltscar", "Cobaltscar"),
	118 => array("greatdivide", "The Great Divide"),
	119 => array("wakening", "The Wakening Land"),
	120 => array("westwastes", "The Western Wastes"),
	121 => array("crystal", "The Crystal Caverns"),
	123 => array("necropolis", "Dragon Necropolis"),
	124 => array("templeveeshan", "The Temple of Veeshan"),
	125 => array("sirens", "Siren's Grotto"),
	126 => array("mischiefplane", "The Plane of Mischief"),
	127 => array("growthplane", "The Plane of Growth"),
	128 => array("sleeper", "The Sleeper's Tomb"),
	129 => array("thurgadinb", "Icewell Keep"),
	130 => array("erudsxing2", "Marauders Mire"),
	150 => array("shadowhaven", "Shadow Haven"),
	151 => array("bazaar", "The Bazaar"),
	151 => array("bazaar", "The Bazaar"),
	152 => array("nexus", "Nexus"),
	153 => array("echo", "The Echo Caverns"),
	154 => array("acrylia", "The Acrylia Caverns"),
	155 => array("sharvahl", "The City of Shar Vahl"),
	156 => array("paludal", "The Paludal Caverns"),
	157 => array("fungusgrove", "The Fungus Grove"),
	158 => array("vexthal", "Vex Thal"),
	159 => array("sseru", "Sanctus Seru"),
	160 => array("katta", "Katta Castellum"),
	161 => array("netherbian", "Netherbian Lair"),
	162 => array("ssratemple", "Ssraeshza Temple"),
	163 => array("griegsend", "Grieg's End"),
	164 => array("thedeep", "The Deep"),
	165 => array("shadeweaver", "Shadeweaver's Thicket"),
	166 => array("hollowshade", "Hollowshade Moor"),
	167 => array("grimling", "Grimling Forest"),
	168 => array("mseru", "Marus Seru"),
	169 => array("letalis", "Mons Letalis"),
	170 => array("twilight", "The Twilight Sea"),
	171 => array("thegrey", "The Grey"),
	172 => array("tenebrous", "The Tenebrous Mountains"),
	173 => array("maiden", "The Maiden's Eye"),
	174 => array("dawnshroud", "The Dawnshroud Peaks"),
	175 => array("scarlet", "The Scarlet Desert"),
	176 => array("umbral", "The Umbral Plains"),
	179 => array("akheva", "The Akheva Ruins"),
	180 => array("arena2", "The Arena Two"),
	181 => array("jaggedpine", "The Jaggedpine Forest"),
	182 => array("nedaria", "Nedaria's Landing"),
	183 => array("tutorial", "EverQuest Tutorial"),
	184 => array("load", "Loading Zone"),
	185 => array("load2", "New Loading Zone"),
	186 => array("hateplaneb", "The Plane of Hate"),
	187 => array("shadowrest", "Shadowrest"),
	188 => array("tutoriala", "The Mines of Gloomingdeep"),
	189 => array("tutorialb", "The Mines of Gloomingdeep"),
	190 => array("clz", "Loading"),
	200 => array("codecay", "The Crypt of Decay"),
	201 => array("pojustice", "The Plane of Justice"),
	202 => array("poknowledge", "The Plane of Knowledge"),
	203 => array("potranquility", "The Plane of Tranquility"),
	204 => array("ponightmare", "The Plane of Nightmares"),
	205 => array("podisease", "The Plane of Disease"),
	206 => array("poinnovation", "The Plane of Innovation"),
	207 => array("potorment", "Torment, the Plane of Pain"),
	208 => array("povalor", "The Plane of Valor"),
	209 => array("bothunder", "Bastion of Thunder"),
	210 => array("postorms", "The Plane of Storms"),
	211 => array("hohonora", "The Halls of Honor"),
	212 => array("solrotower", "The Tower of Solusek Ro"),
	213 => array("powar", "Plane of War"),
	214 => array("potactics", "Drunder, the Fortress of Zek"),
	215 => array("poair", "The Plane of Air"),
	216 => array("powater", "The Plane of Water"),
	217 => array("pofire", "The Plane of Fire"),
	218 => array("poeartha", "The Plane of Earth"),
	219 => array("potimea", "The Plane of Time"),
	220 => array("hohonorb", "The Temple of Marr"),
	221 => array("nightmareb", "The Lair of Terris Thule"),
	222 => array("poearthb", "The Plane of Earth"),
	223 => array("potimeb", "The Plane of Time"),
	224 => array("gunthak", "The Gulf of Gunthak"),
	225 => array("dulak", "Dulak's Harbor"),
	226 => array("torgiran", "The Torgiran Mines"),
	227 => array("nadox", "The Crypt of Nadox"),
	228 => array("hatesfury", "Hate's Fury"),
	229 => array("guka", "Deepest Guk: Cauldron of Lost Souls"),
	230 => array("ruja", "The Rujarkian Hills: Bloodied Quarries"),
	231 => array("taka", "Takish-Hiz: Sunken Library"),
	232 => array("mira", "Miragul's Menagerie: Silent Gallery"),
	233 => array("mmca", "Mistmoore's Catacombs: Forlorn Caverns"),
	234 => array("gukb", "The Drowning Crypt"),
	235 => array("rujb", "The Rujarkian Hills: Halls of War"),
	236 => array("takb", "Takish-Hiz: Shifting Tower"),
	237 => array("mirb", "Miragul's Menagerie: Frozen Nightmare"),
	238 => array("mmcb", "Mistmoore's Catacombs: Dreary Grotto"),
	239 => array("gukc", "Deepest Guk: Ancient Aqueducts"),
	240 => array("rujc", "The Rujarkian Hills: Wind Bridges"),
	241 => array("takc", "Takish-Hiz: Fading Temple"),
	242 => array("mirc", "The Spider Den"),
	243 => array("mmcc", "Mistmoore's Catacombs: Struggles within the Progeny"),
	244 => array("gukd", "The Mushroom Grove"),
	245 => array("rujd", "The Rujarkian Hills: Prison Break"),
	246 => array("takd", "Takish-Hiz: Royal Observatory"),
	247 => array("mird", "Miragul's Menagerie: Hushed Banquet"),
	248 => array("mmcd", "Mistmoore's Catacombs: Chambers of Eternal Affliction"),
	249 => array("guke", "Deepest Guk: The Curse Reborn"),
	250 => array("ruje", "The Rujarkian Hills: Drudge Hollows"),
	251 => array("take", "Takish-Hiz: River of Recollection"),
	252 => array("mire", "The Frosted Halls"),
	253 => array("mmce", "Mistmoore's Catacombs: Sepulcher of the Damned"),
	254 => array("gukf", "Deepest Guk: Chapel of the Witnesses"),
	255 => array("rujf", "The Rujarkian Hills: Fortified Lair of the Taskmasters"),
	256 => array("takf", "Takish-Hiz: Sandfall Corridors"),
	257 => array("mirf", "The Forgotten Wastes"),
	258 => array("mmcf", "Mistmoore's Catacombs: Scion Lair of Fury"),
	259 => array("gukg", "The Root Garden"),
	260 => array("rujg", "The Rujarkian Hills: Hidden Vale of Deceit"),
	261 => array("takg", "Takish-Hiz: Balancing Chamber"),
	262 => array("mirg", "Miragul's Menagerie: Heart of the Menagerie"),
	263 => array("mmcg", "Mistmoore's Catacombs: Cesspits of Putrescence"),
	264 => array("gukh", "Deepest Guk: Accursed Sanctuary"),
	265 => array("rujh", "The Rujarkian Hills: Blazing Forge "),
	266 => array("takh", "Takish-Hiz: Sweeping Tides"),
	267 => array("mirh", "The Morbid Laboratory"),
	268 => array("mmch", "Mistmoore's Catacombs: Aisles of Blood"),
	269 => array("ruji", "The Rujarkian Hills: Arena of Chance"),
	270 => array("taki", "Takish-Hiz: Antiquated Palace"),
	271 => array("miri", "The Theater of Imprisoned Horror"),
	272 => array("mmci", "Mistmoore's Catacombs: Halls of Sanguinary Rites"),
	273 => array("rujj", "The Rujarkian Hills: Barracks of War"),
	274 => array("takj", "Takish-Hiz: Prismatic Corridors"),
	275 => array("mirj", "Miragul's Menagerie: Grand Library"),
	276 => array("mmcj", "Mistmoore's Catacombs: Infernal Sanctuary"),
	277 => array("chardokb", "Chardok: The Halls of Betrayal"),
	278 => array("soldungc", "The Caverns of Exile"),
	279 => array("abysmal", "The Abysmal Sea"),
	280 => array("natimbi", "Natimbi, the Broken Shores"),
	281 => array("qinimi", "Qinimi, Court of Nihilia"),
	282 => array("riwwi", "Riwwi, Coliseum of Games"),
	283 => array("barindu", "Barindu, Hanging Gardens"),
	284 => array("ferubi", "Ferubi, Forgotten Temple of Taelosia"),
	285 => array("snpool", "Sewers of Nihilia, Pool of Sludg"),
	286 => array("snlair", "Sewers of Nihilia, Lair of Trapp"),
	287 => array("snplant", "Sewers of Nihilia, Purifying Pla"),
	288 => array("sncrematory", "Sewers of Nihilia, Emanating Cre"),
	289 => array("tipt", "Tipt, Treacherous Crags"),
	290 => array("vxed", "Vxed, the Crumbling Caverns"),
	291 => array("yxtta", "Yxtta, Pulpit of Exiles "),
	292 => array("uqua", "Uqua, the Ocean God Chantry"),
	293 => array("kodtaz", "Kod'Taz, Broken Trial Grounds"),
	294 => array("ikkinz", "Ikkinz, Chambers of Transcendence"),
	295 => array("qvic", "Qvic, Prayer Grounds of Calling"),
	296 => array("inktuta", "Inktu'Ta, the Unmasked Chapel"),
	297 => array("txevu", "Txevu, Lair of the Elite"),
	298 => array("tacvi", "Tacvi, The Broken Temple"),
	299 => array("qvicb", "Qvic, the Hidden Vault"),
	300 => array("wallofslaughter", "Wall of Slaughter"),
	301 => array("bloodfields", "The Bloodfields"),
	302 => array("draniksscar", "Dranik's Scar"),
	303 => array("causeway", "Nobles' Causeway"),
	304 => array("chambersa", "Muramite Proving Grounds"),
	305 => array("chambersb", "Muramite Proving Grounds"),
	306 => array("chambersc", "Muramite Proving Grounds"),
	307 => array("chambersd", "Muramite Proving Grounds"),
	308 => array("chamberse", "Muramite Proving Grounds"),
	309 => array("chambersf", "Muramite Proving Grounds"),
	316 => array("provinggrounds", "Muramite Provinggrounds"),
	317 => array("anguish", "Anguish, the Fallen Palace"),
	318 => array("dranikhollowsa", "Dranik's Hollows"),
	319 => array("dranikhollowsb", "Dranik's Hollows"),
	320 => array("dranikhollowsc", "Dranik's Hollows"),
	328 => array("dranikcatacombsa", "Catacombs of Dranik"),
	329 => array("dranikcatacombsb", "Catacombs of Dranik"),
	330 => array("dranikcatacombsc", "Catacombs of Dranik"),
	331 => array("draniksewersa", "Sewers of Dranik"),
	332 => array("draniksewersb", "Sewers of Dranik"),
	333 => array("draniksewersc", "Sewers of Dranik"),
	334 => array("riftseekers", "Riftseekers' Sanctum"),
	335 => array("harbingers", "Harbinger's Spire"),
	336 => array("dranik", "The Ruined City of Dranik"),
	337 => array("broodlands", "The Broodlands"),
	338 => array("stillmoona", "Stillmoon Temple"),
	339 => array("stillmoonb", "The Ascent"),
	340 => array("thundercrest", "Thundercrest Isles"),
	341 => array("delvea", "Lavaspinner's Lair"),
	342 => array("delveb", "Tirranun's Delve"),
	343 => array("thenest", "The Nest"),
	344 => array("guildlobby", "Guild Lobby"),
	345 => array("guildhall", "Guild Hall"),
	346 => array("barter", "The Barter Hall"),
	347 => array("illsalin", "Ruins of Illsalin"),
	348 => array("illsalina", "Illsalin Marketplace"),
	349 => array("illsalinb", "Temple of Korlach"),
	350 => array("illsalinc", "The Nargil Pits"),
	351 => array("dreadspire", "Dreadspire Keep"),
	354 => array("drachnidhive", "The Hive"),
	355 => array("drachnidhivea", "The Hatchery"),
	356 => array("drachnidhiveb", "The Cocoons"),
	357 => array("drachnidhivec", "Queen Sendaii`s Lair"),
	358 => array("westkorlach", "Stoneroot Falls"),
	359 => array("westkorlacha", "Prince's Manor"),
	360 => array("westkorlachb", "Caverns of the Lost"),
	361 => array("westkorlachc", "Lair of the Korlach"),
	362 => array("eastkorlach", "The Undershore"),
	363 => array("eastkorlacha", "Snarlstone Dens"),
	364 => array("shadowspine", "Shadow Spine"),
	365 => array("corathus", "Corathus Creep"),
	366 => array("corathusa", "Sporali Caverns"),
	367 => array("corathusb", "The Corathus Mines"),
	368 => array("nektulosa", "Shadowed Grove"),
	369 => array("arcstone", "Arcstone, Isle of Spirits"),
	370 => array("relic", "Relic, the Artifact City"),
	371 => array("skylance", "Skylance"),
	372 => array("devastation", "The Devastation"),
	373 => array("devastationa", "The Seething Wall"),
	374 => array("rage", "Sverag, Stronghold of Rage"),
	375 => array("ragea", "Razorthorn, Tower of Sullon Zek"),
	376 => array("takishruins", "Ruins of Takish-Hiz"),
	377 => array("takishruinsa", "The Root of Ro"),
	378 => array("elddar", "The Elddar Forest"),
	379 => array("elddara", "Tunare's Shrine"),
	380 => array("theater", "Theater of Blood"),
	381 => array("theatera", "Deathknell, Tower of Dissonance"),
	382 => array("freeporteast", "East Freeport"),
	383 => array("freeportwest", "West Freeport"),
	384 => array("freeportsewers", "Freeport Sewers"),
	385 => array("freeportacademy", "Acadeof Arcane Sciences"),
	386 => array("freeporttemple", "Temple of Marr"),
	387 => array("freeportmilitia", "Freeport Militia House: Precious"),
	388 => array("freeportarena", "Arena"),
	389 => array("freeportcityhall", "City Hall"),
	390 => array("freeporttheater", "Theater of the Tranquil"),
	391 => array("freeporthall", "Hall of Truth: Bounty"),
	392 => array("northro", "North Desert of Ro"),
	393 => array("southro", "South Desert of Ro"),
	394 => array("crescent", "Crescent Reach"),
	395 => array("moors", "Blightfire Moors"),
	396 => array("stonehive", "Stone Hive"),
	397 => array("mesa", "Goru`kar Mesa"),
	398 => array("roost", "Blackfeather Roost"),
	399 => array("steppes", "The Steppes"),
	400 => array("icefall", "Icefall Glacier"),
	401 => array("valdeholm", "Valdeholm"),
	402 => array("frostcrypt", "Frostcrypt, Throne of the Shade King"),
	403 => array("sunderock", "Sunderock Springs"),
	404 => array("vergalid", "Vergalid Mines"),
	405 => array("direwind", "Direwind Cliffs"),
	406 => array("ashengate", "Ashengate, Reliquary of the Scale"),
	407 => array("highpasshold", "Highpass Hold"),
	408 => array("commonlands", "The Commonlands"),
	409 => array("oceanoftears", "The Ocean of Tears"),
	410 => array("kithforest", "Kithicor Forest"),
	411 => array("befallenb", "Befallen"),
	412 => array("highpasskeep", "HighKeep"),
	413 => array("innothuleb", "The Innothule Swamp"),
	414 => array("toxxulia", "Toxxulia Forest"),
	415 => array("mistythicket", "The Misty Thicket"),
	416 => array("kattacastrum", "Katta Castrum"),
	417 => array("thalassius", "Thalassius, the Coral Keep"),
	418 => array("atiiki", "Jewel of Atiiki"),
	419 => array("zhisza", "Zhisza, the Shissar Sanctuary"),
	420 => array("silyssar", "Silyssar, New Chelsith"),
	421 => array("solteris", "Solteris, the Throne of Ro"),
	422 => array("barren", "Barren Coast"),
	423 => array("buriedsea", "The Buried Sea"),
	424 => array("jardelshook", "Jardel's Hook"),
	425 => array("monkeyrock", "Monkey Rock"),
	426 => array("suncrest", "Suncrest Isle"),
	427 => array("deadbone", "Deadbone Reef"),
	428 => array("blacksail", "Blacksail Folly"),
	429 => array("maidensgrave", "Maiden's Grave"),
	430 => array("redfeather", "Redfeather Isle"),
	431 => array("shipmvp", "The Open Sea"),
	432 => array("shipmvu", "The Open Sea"),
	433 => array("shippvu", "The Open Sea"),
	434 => array("shipuvu", "The Open Sea"),
	435 => array("shipmvm", "The Open Sea"),
	436 => array("mechanotus", "Fortress Mechanotus"),
	437 => array("mansion", "Meldrath's Majestic Mansion"),
	438 => array("steamfactory", "The Steam Factory"),
	439 => array("shipworkshop", "S.H.I.P. Workshop"),
	440 => array("gyrospireb", "Gyrospire Beza"),
	441 => array("gyrospirez", "Gyrospire Zeka"),
	442 => array("dragonscale", "Dragonscale Hills"),
	443 => array("lopingplains", "Loping Plains"),
	444 => array("hillsofshade", "Hills of Shade"),
	445 => array("bloodmoon", "Bloodmoon Keep"),
	446 => array("crystallos", "Crystallos, Lair of the Awakened"),
	447 => array("guardian", "The Mechamatic Guardian"),
	448 => array("steamfontmts", "The Steamfont Mountains"),
	449 => array("cryptofshade", "Crypt of Shade"),
	451 => array("dragonscaleb", "Deepscar's Den"),
	452 => array("oldfieldofbone", "Field of Scale"),
	453 => array("oldkaesoraa", "Kaesora Library"),
	454 => array("oldkaesorab", "Kaesora Hatchery"),
	455 => array("oldkurn", "Kurn's Tower"),
	456 => array("oldkithicor", "Bloody Kithicor"),
	457 => array("oldcommons", "Old Commonlands"),
	458 => array("oldhighpass", "Highpass Hold"),
	459 => array("thevoida", "The Void"),
	460 => array("thevoidb", "The Void"),
	461 => array("thevoidc", "The Void"),
	462 => array("thevoidd", "The Void"),
	463 => array("thevoide", "The Void"),
	464 => array("thevoidf", "The Void"),
	465 => array("thevoidg", "The Void"),
	466 => array("oceangreenhills", "Oceangreen Hills"),
	467 => array("oceangreenvillage", "Oceangreen Village"),
	468 => array("oldblackburrow", "BlackBurrow"),
	469 => array("bertoxtemple", "Temple of Bertoxxulous"),
	470 => array("discord", "Korafax, Home of the Riders"),
	471 => array("discordtower", "Citadel of the Worldslayer"),
	472 => array("oldbloodfield", "Old Bloodfields"),
	473 => array("precipiceofwar", "The Precipice of War"),
	474 => array("olddranik", "City of Dranik"),
	475 => array("toskirakk", "Toskirakk"),
	476 => array("korascian", "Korascian Warrens"),
	477 => array("rathechamber", "Rathe Council Chamber"),
	480 => array("brellsrest", "Brell's Rest"),
	481 => array("fungalforest", "Fungal Forest"),
	482 => array("underquarry", "The Underquarry"),
	483 => array("coolingchamber", "The Cooling Chamber"),
	484 => array("shiningcity", "Kernagir, the Shining City"),
	485 => array("arthicrex", "Arthicrex"),
	486 => array("foundation", "The Foundation"),
	487 => array("lichencreep", "Lichen Creep"),
	488 => array("pellucid", "Pellucid Grotto"),
	489 => array("stonesnake", "Volska's Husk"),
	490 => array("brellstemple", "Brell's Temple"),
	491 => array("convorteum", "The Convorteum"),
	492 => array("brellsarena", "Brell's Arena"),
	493 => array("weddingchapel", "Wedding Chapel"),
	494 => array("weddingchapeldark", "Wedding Chapel"),
	495 => array("dragoncrypt", "Lair of the Risen"),
	700 => array("feerrott2", "The Feerrott"),
	701 => array("thulehouse1", "House of Thule"),
	702 => array("thulehouse2", "House of Thule, Upper Floors"),
	703 => array("housegarden", "The Grounds"),
	704 => array("thulelibrary", "The Library"),
	705 => array("well", "The Well"),
	706 => array("fallen", "Erudin Burning"),
	707 => array("morellcastle", "Morell's Castle"),
	708 => array("somnium", "Sanctum Somnium"),
	709 => array("alkabormare", "Al'Kabor's Nightmare"),
	710 => array("miragulmare", "Miragul's Nightmare"),
	711 => array("thuledream", "Fear Itself"),
	712 => array("neighborhood", "Sunrise Hills"),
	724 => array("argath", "Argath, Bastion of Illdaera"),
	725 => array("arelis", "Valley of Lunanyn"),
	726 => array("sarithcity", "Sarith, City of Tides"),
	727 => array("rubak", "Rubak Oseka, Temple of the Sea"),
	728 => array("beastdomain", "Beasts' Domain"),
	729 => array("resplendent", "The Resplendent Temple"),
	730 => array("pillarsalra", "Pillars of Alra"),
	731 => array("windsong", "Windsong Sanctuary"),
	732 => array("cityofbronze", "Erillion, City of Bronze"),
	733 => array("sepulcher", "Sepulcher of Order"),
	734 => array("eastsepulcher", "Sepulcher East"),
	735 => array("westsepulcher", "Sepulcher West"),
	752 => array("shardslanding", "Shard's Landing"),
	753 => array("xorbb", "Valley of King Xorbb"),
	754 => array("kaelshard", "Kael Drakkel: The King's Madness"),
	755 => array("eastwastesshard", "East Wastes: Zeixshi-Kar's Awakening"),
	756 => array("crystalshard", "The Crystal Caverns: Fragment of Fear"),
	757 => array("breedinggrounds", "The Breeding Grounds"),
	758 => array("eviltree", "Evantil, the Vile Oak"),
	759 => array("grelleth", "Grelleth's Palace, the Chateau of Filth"),
	760 => array("chapterhouse", "Chapterhouse of the Fallen"),
	996 => array("arttest", "Art Testing Domain"),
	998 => array("fhalls", "The Forgotten Halls"),
	999 => array("apprentice", "Designer Apprentice")
);

$era_zones = array(
	1  => "Antonica",
	2  => "Odus",
	3  => "Faydwer",
	4  => "Old World Planes",
	5  => "Ruins of Kunark",
	6  => "Scars of Velious",
	7  => "Shadows of Luclin",
	8  => "The Planes of Power",
	9  => "The Legacy of Ykesha",
	10 => "The Lost Dungeons of Norrath",
	11 => "The Gates of Discord",
	12 => "The Omens of War",
	13 => "Dragons of Norrath",
	14 => "Depths of Darkhollow",
	15 => "Prophecy of Ro",
	16 => "The Serpent's Spine",
	17 => "The Buried Sea",
	18 => "Secrets of Faydwer",
	19 => "Seeds of Destruction",
	20 => "Underfoot",
	21 => "House of Thule",
	22 => "Veil of Alaris"
);

$task_types = array(
	0 => "Task",
	1 => "Shared Task",
	2 => "Quest",
	3 => "Expedition"
);

$duration_codes = array(
	1 => "Short",
	2 => "Medium",
	3 => "Long"
);