<?php

// tables
$accounts_table             = "account";
$character_table            = "character_data";
$faction_list_table         = "faction_list";
$forage_table               = "forage";
$ground_spawns_table        = "ground_spawns";
$items_table                = "items";
$loot_drop_table            = "lootdrop";
$loot_drop_entries_table    = "lootdrop_entries";
$loot_table                 = "loottable";
$loot_table_entries         = "loottable_entries";
$merchant_list_table        = "merchantlist";
$task_table                 = "tasks";
$task_activities_table      = "activities";
$npc_faction_table          = "npc_faction";
$faction_entries_table      = "npc_faction_entries";
$npc_spells_entries_table   = "npc_spells_entries";
$npc_spells_table           = "npc_spells";
$npc_types_table            = "npc_types";
$pets_table                 = "pets";
$spawn2_table               = "spawn2";
$spawn_entry_table          = "spawnentry";
$spawn_group_table          = "spawngroup";
$trade_skill_recipe_table   = "tradeskill_recipe";
$trade_skill_recipe_entries = "tradeskill_recipe_entries";
$zones_table                = "zone";
$discovered_items_table     = "discovered_items";
$spell_globals_table        = "spell_globals";
$spells_table               = "spells_new";
$tasks_table                = "tasks";
$activities_table           = "activities";

// added tables, source the needed file from the includes/sql directory
$tbspawnarea  = "spawnarea"; // Tool Specific Table
$tbnews       = "eqbnews"; // Tool Specific Table
$tbquestitems = "quest_items"; // Tool Specific Table
$tbraces      = "races"; // Tool Specific Table


// merchant classes
$dbmerchants = [40, 41, 59, 61, 67, 68, 70];

// factions (factions.h)
$dbfactions = [
    1 => "Ally",
    2 => "Warmly",
    3 => "Kindly",
    4 => "Amiably",
    5 => "Indifferent",
    9 => "Apprehensive",
    8 => "Dubious",
    7 => "Threatenly",
    6 => "Ready to attack",
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
$dbclasses       = [];
$dbclasses[0]    = "Warrior";
$dbclasses[1]    = "Warrior";
$dbclasses[2]    = "Cleric";
$dbclasses[3]    = "Paladin";
$dbclasses[4]    = "Ranger";
$dbclasses[5]    = "Shadown Knight";
$dbclasses[6]    = "Druid";
$dbclasses[7]    = "Monk";
$dbclasses[8]    = "Bard";
$dbclasses[9]    = "Rogue";
$dbclasses[10]   = "Shaman";
$dbclasses[11]   = "Necromancer";
$dbclasses[12]   = "Wizard";
$dbclasses[13]   = "Magician";
$dbclasses[14]   = "Enchanter";
$dbclasses[15]   = "Beastlord";
$dbclasses[16]   = "Berserker";
$dbclasses[17]   = "Banker";
$dbclasses[20]   = "GM Warrior";
$dbclasses[21]   = "GM Cleric";
$dbclasses[22]   = "GM Paladin";
$dbclasses[23]   = "GM Ranger";
$dbclasses[24]   = "GM Shadown Knight";
$dbclasses[25]   = "GM Druid";
$dbclasses[26]   = "GM Monk";
$dbclasses[27]   = "GM Bard";
$dbclasses[28]   = "GM Rogue";
$dbclasses[29]   = "GM Shaman";
$dbclasses[30]   = "GM Necromancer";
$dbclasses[31]   = "GM Wizard";
$dbclasses[32]   = "GM Magician";
$dbclasses[33]   = "GM Enchanter";
$dbclasses[34]   = "GM Beastlord";
$dbclasses[35]   = "GM Berserker";
$dbclasses[40]   = "Banker";
$dbclasses[41]   = "Shopkeeper";
$dbclasses[59]   = "Discord Merchant";
$dbclasses[60]   = "Adventure Recruiter";
$dbclasses[61]   = "Adventure Merchant";
$dbclasses[63]   = "Tribute Master";
$dbclasses[64]   = "Guild Tribute Master";
$dbclasses[66]   = "Guild Bank";
$dbclasses[67]   = "Radiant Crystal Merchant";
$dbclasses[68]   = "Ebon Crystal Merchant";
$dbclasses[69]   = "Fellowships";
$dbclasses[70]   = "Alternate Currency Merchant";
$dbclasses[71]   = "Mercenary Merchant ";

// Slots
$dbslots          = [];
$dbslotsid        = [];
$dbslots[4194304] = "Power Source";
$dbslots[2097152] = "Ammo";
$dbslots[1048576] = "Waist";
$dbslots[524288]  = "Feet";
$dbslots[262144]  = "Legs";
$dbslots[131072]  = "Chest";
$dbslots[98304]   = "Fingers";
$dbslots[65536]   = "Finger";
$dbslots[32768]   = "Finger";
$dbslots[16384]   = "Secondary";
$dbslots[8192]    = "Primary";
$dbslots[4096]    = "Hands";
$dbslots[2048]    = "Range";
$dbslots[1536]    = "Wrists";
$dbslots[1024]    = "Wrist";
$dbslots[512]     = "Wrist";
$dbslots[256]     = "Back";
$dbslots[128]     = "Arms";
$dbslots[64]      = "Shoulders";
$dbslots[32]      = "Neck";
$dbslots[18]      = "Ears";
$dbslots[16]      = "Ear";
$dbslots[8]       = "Face";
$dbslots[4]       = "Head";
$dbslots[2]       = "Ear";
$dbslots[1]       = "Charm";

// ItemClasses 2^(class-1)
$dbiclasses        = [];
$dbiclasses[65535] = "All classes";
$dbiclasses[32768] = "Berserker";
$dbiclasses[16384] = "Beastlord";
$dbiclasses[8192]  = "Enchanter";
$dbiclasses[4096]  = "Magician";
$dbiclasses[2048]  = "Wizard";
$dbiclasses[1024]  = "Necromancer";
$dbiclasses[512]   = "Shaman";
$dbiclasses[256]   = "Rogue";
$dbiclasses[128]   = "Bard";
$dbiclasses[64]    = "Monk";
$dbiclasses[32]    = "Druid";
$dbiclasses[16]    = "Shadow knight";
$dbiclasses[8]     = "Ranger";
$dbiclasses[4]     = "Paladin";
$dbiclasses[2]     = "Cleric";
$dbiclasses[1]     = "Warrior";

$db_classes_short        = [];
$db_classes_short[65535] = "ALL";
$db_classes_short[32768] = "BER";
$db_classes_short[16384] = "BST";
$db_classes_short[8192]  = "ENC";
$db_classes_short[4096]  = "MAG";
$db_classes_short[2048]  = "WIZ";
$db_classes_short[1024]  = "NEC";
$db_classes_short[512]   = "SHM";
$db_classes_short[256]   = "ROG";
$db_classes_short[128]   = "BRD";
$db_classes_short[64]    = "MNK";
$db_classes_short[32]    = "DRU";
$db_classes_short[16]    = "SHD";
$db_classes_short[8]     = "RNG";
$db_classes_short[4]     = "PAL";
$db_classes_short[2]     = "CLR";
$db_classes_short[1]     = "WAR";

// races
$dbraces        = [];
$dbraces[65535] = "All races";
$dbraces[32768] = "Drakkin";
$dbraces[16384] = "Froglok";
$dbraces[8192]  = "Vah Shir";
$dbraces[4096]  = "Iksar";
$dbraces[2048]  = "Gnome";
$dbraces[1024]  = "Halfling";
$dbraces[512]   = "Ogre";
$dbraces[256]   = "Troll";
$dbraces[128]   = "Dwarf";
$dbraces[64]    = "Half Elf";
$dbraces[32]    = "Dark Elf";
$dbraces[16]    = "High Elf";
$dbraces[8]     = "Wood Elf";
$dbraces[4]     = "Erudite";
$dbraces[2]     = "Barbarian";
$dbraces[1]     = "Human";

$db_races_short        = [];
$db_races_short[65535] = "ALL";
$db_races_short[32768] = "DRK";
$db_races_short[16384] = "FRG";
$db_races_short[8192]  = "VAH";
$db_races_short[4096]  = "IKS";
$db_races_short[2048]  = "GNM";
$db_races_short[1024]  = "HFL";
$db_races_short[512]   = "OGR";
$db_races_short[256]   = "TRL";
$db_races_short[128]   = "DWF";
$db_races_short[64]    = "HLF";
$db_races_short[32]    = "DKE";
$db_races_short[16]    = "HEF";
$db_races_short[8]     = "WLF";
$db_races_short[4]     = "ERU";
$db_races_short[2]     = "BAR";
$db_races_short[1]     = "HUM";

// skills
$dbskills     = [];
$dbskills[0]  = '1H_BLUNT';
$dbskills[1]  = '1H_SLASHING';
$dbskills[2]  = '2H_BLUNT';
$dbskills[3]  = '2H_SLASHING';
$dbskills[4]  = 'ABJURATION';
$dbskills[5]  = 'ALTERATION';
$dbskills[6]  = 'APPLY_POISON';
$dbskills[7]  = 'ARCHERY';
$dbskills[8]  = 'BACKSTAB';
$dbskills[9]  = 'BIND_WOUND';
$dbskills[10] = 'BASH';
$dbskills[11] = 'BLOCKSKILL';
$dbskills[12] = 'BRASS_INSTRUMENTS';
$dbskills[13] = 'CHANNELING';
$dbskills[14] = 'CONJURATION';
$dbskills[15] = 'DEFENSE';
$dbskills[16] = 'DISARM';
$dbskills[17] = 'DISARM_TRAPS';
$dbskills[18] = 'DIVINATION';
$dbskills[19] = 'DODGE';
$dbskills[20] = 'DOUBLE_ATTACK';
$dbskills[21] = 'DRAGON_PUNCH';
$dbskills[22] = 'DUEL_WIELD';
$dbskills[23] = 'EAGLE_STRIKE';
$dbskills[24] = 'EVOCATION';
$dbskills[25] = 'FEIGN_DEATH';
$dbskills[26] = 'FLYING_KICK';
$dbskills[27] = 'FORAGE';
$dbskills[28] = 'HAND_TO_HAND';
$dbskills[29] = 'HIDE';
$dbskills[30] = 'KICK';
$dbskills[31] = 'MEDITATE';
$dbskills[32] = 'MEND';
$dbskills[33] = 'OFFENSE';
$dbskills[34] = 'PARRY';
$dbskills[35] = 'PICK_LOCK';
$dbskills[36] = 'PIERCING';
$dbskills[37] = 'RIPOSTE';
$dbskills[38] = 'ROUND_KICK';
$dbskills[39] = 'SAFE_FALL';
$dbskills[40] = 'SENSE_HEADING';
$dbskills[41] = 'SINGING';
$dbskills[42] = 'SNEAK';
$dbskills[43] = 'SPECIALIZE_ABJURE';
$dbskills[44] = 'SPECIALIZE_ALTERATION';
$dbskills[45] = 'SPECIALIZE_CONJURATION';
$dbskills[46] = 'SPECIALIZE_DIVINATION';
$dbskills[47] = 'SPECIALIZE_EVOCATION';
$dbskills[48] = 'PICK_POCKETS';
$dbskills[49] = 'STRINGED_INSTRUMENTS';
$dbskills[50] = 'SWIMMING';
$dbskills[51] = 'THROWING';
$dbskills[52] = 'CLICKY';
$dbskills[53] = 'TRACKING';
$dbskills[54] = 'WIND_INSTRUMENTS';
$dbskills[55] = 'FISHING';
$dbskills[56] = 'POISON_MAKING';
$dbskills[57] = 'TINKERING';
$dbskills[58] = 'RESEARCH';
$dbskills[59] = 'ALCHEMY';
$dbskills[60] = 'BAKING';
$dbskills[61] = 'TAILORING';
$dbskills[62] = 'SENSE_TRAPS';
$dbskills[63] = 'BLACKSMITHING';
$dbskills[64] = 'FLETCHING';
$dbskills[65] = 'BREWING';
$dbskills[66] = 'ALCOHOL_TOLERANCE';
$dbskills[67] = 'BEGGING';
$dbskills[68] = 'JEWELRY_MAKING';
$dbskills[69] = 'POTTERY';
$dbskills[70] = 'PERCUSSION_INSTRUMENTS';
$dbskills[71] = 'INTIMIDATION';
$dbskills[72] = 'BERSERKING';
$dbskills[73] = 'TAUNT';

// spell effects
$dbspelleffects      = [];
$dbspelleffects[0]   = 'Increase Hitpoints'; // or decrease
$dbspelleffects[1]   = 'Increase AC';
$dbspelleffects[2]   = 'Increase ATK';
$dbspelleffects[3]   = 'In/Decrease Movement';
$dbspelleffects[4]   = 'Increase STR';
$dbspelleffects[5]   = 'Increase DEX';
$dbspelleffects[6]   = 'Increase AGI';
$dbspelleffects[7]   = 'Increase STA';
$dbspelleffects[8]   = 'Increase INT';
$dbspelleffects[9]   = 'Increase WIS';
$dbspelleffects[10]  = 'Increase CHA';
$dbspelleffects[11]  = 'In/Decrease Attack Speed';
$dbspelleffects[12]  = 'Invisibility';
$dbspelleffects[13]  = 'See Invisible';
$dbspelleffects[14]  = 'WaterBreathing';
$dbspelleffects[15]  = 'Increase Mana';
$dbspelleffects[18]  = 'Pacify';
$dbspelleffects[19]  = 'Increase Faction';
$dbspelleffects[20]  = 'Blindness';
$dbspelleffects[21]  = 'Stun';
$dbspelleffects[22]  = 'Charm';
$dbspelleffects[23]  = 'Fear';
$dbspelleffects[24]  = 'Stamina';
$dbspelleffects[25]  = 'Bind Affinity';
$dbspelleffects[26]  = 'Gate';
$dbspelleffects[27]  = 'Cancel Magic';
$dbspelleffects[28]  = 'Invisibility versus Undead';
$dbspelleffects[29]  = 'Invisibility versus Animals';
$dbspelleffects[30]  = 'Frenzy Radius';
$dbspelleffects[31]  = 'Mesmerize';
$dbspelleffects[32]  = 'Summon Item';
$dbspelleffects[33]  = 'Summon Pet:';
$dbspelleffects[35]  = 'Increase Disease Counter';
$dbspelleffects[36]  = 'Increase Poison Counter';
$dbspelleffects[40]  = 'Invunerability';
$dbspelleffects[41]  = 'Destroy Target';
$dbspelleffects[42]  = 'Shadowstep';
$dbspelleffects[44]  = 'Lycanthropy';
$dbspelleffects[46]  = 'Increase Fire Resist';
$dbspelleffects[47]  = 'Increase Cold Resist';
$dbspelleffects[48]  = 'Increase Poison Resist';
$dbspelleffects[49]  = 'Increase Disease Resist';
$dbspelleffects[50]  = 'Increase Magic Resist';
$dbspelleffects[52]  = 'Sense Undead';
$dbspelleffects[53]  = 'Sense Summoned';
$dbspelleffects[54]  = 'Sense Animals';
$dbspelleffects[55]  = 'Increase Absorb Damage';
$dbspelleffects[56]  = 'True North';
$dbspelleffects[57]  = 'Levitate';
$dbspelleffects[58]  = 'Illusion:';
$dbspelleffects[59]  = 'Increase Damage Shield';
$dbspelleffects[61]  = 'Identify';
$dbspelleffects[63]  = 'Memblur';
$dbspelleffects[64]  = 'SpinStun';
$dbspelleffects[65]  = 'Infravision';
$dbspelleffects[66]  = 'Ultravision';
$dbspelleffects[67]  = 'Eye Of Zomm';
$dbspelleffects[68]  = 'Reclaim Energy';
$dbspelleffects[69]  = 'Increase Max Hitpoints';
$dbspelleffects[71]  = 'Summon Skeleton Pet:';
$dbspelleffects[73]  = 'Bind Sight';
$dbspelleffects[74]  = 'Feign Death';
$dbspelleffects[75]  = 'Voice Graft';
$dbspelleffects[76]  = 'Sentinel';
$dbspelleffects[77]  = 'Locate Corpse';
$dbspelleffects[78]  = 'Increase Absorb Magic Damage';
$dbspelleffects[79]  = 'Increase HP when cast';
$dbspelleffects[81]  = 'Resurrect';
$dbspelleffects[82]  = 'Summon PC';
$dbspelleffects[83]  = 'Teleport';
$dbspelleffects[85]  = 'Add Proc:';
$dbspelleffects[86]  = 'Reaction Radius';
$dbspelleffects[87]  = 'Increase Magnification';
$dbspelleffects[88]  = 'Evacuate';
$dbspelleffects[89]  = 'Increase Player Size';
$dbspelleffects[90]  = 'Cloak';
$dbspelleffects[91]  = 'Summon Corpse';
$dbspelleffects[92]  = 'Increase hate';
$dbspelleffects[93]  = 'Stop Rain';
$dbspelleffects[94]  = 'Make Fragile (Delete if combat)';
$dbspelleffects[95]  = 'Sacrifice';
$dbspelleffects[96]  = 'Silence';
$dbspelleffects[97]  = 'Increase Mana Pool';
$dbspelleffects[98]  = 'Increase Haste v2';
$dbspelleffects[99]  = 'Root';
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
$dbspelleffects[134] = 'Limit: Max Level';
$dbspelleffects[135] = 'Limit: Resist(Magic allowed)';
$dbspelleffects[136] = 'Limit: Target';
$dbspelleffects[137] = 'Limit: Effect(Hitpoints allowed)';
$dbspelleffects[138] = 'Limit: Spell Type(Detrimental only)';
$dbspelleffects[139] = 'Limit: Spell';
$dbspelleffects[140] = 'Limit: Min Duration';
$dbspelleffects[141] = 'Limit: Instant spells only';
$dbspelleffects[142] = 'LimitMinLevel';
$dbspelleffects[143] = 'Limit: Min Casting Time';
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
$dbspelleffects[192] = 'Increase hate';
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
$dbspelleffects[289] = 'Improved Spell Effect: ';
$dbspelleffects[294] = 'Increase Critial Spell Chance';
$dbspelleffects[299] = 'Wake the Dead';
$dbspelleffects[311] = 'Limit: Combat Skills Not Allowed';
$dbspelleffects[314] = 'Fixed Duration Invisbility (not documented on Lucy)';
$dbspelleffects[323] = 'Add Defensive Proc:';
$dbspelleffects[330] = 'Critical Damage Mob';


// spell targets
$dbspelltargets     = [];
$dbspelltargets[1]  = "";
$dbspelltargets[2]  = "Area of effect over the caster";
$dbspelltargets[3]  = "Group teleport";
$dbspelltargets[4]  = "Area of effect around the caster";
$dbspelltargets[5]  = "Single target";
$dbspelltargets[6]  = "Self only";
$dbspelltargets[8]  = "Area of effect around the target";
$dbspelltargets[9]  = "Animal";
$dbspelltargets[10] = "Undead only";
$dbspelltargets[11] = "Summoned beings";
$dbspelltargets[13] = "Tap";
$dbspelltargets[14] = "Caster's pet";
$dbspelltargets[15] = "Target's corpse";
$dbspelltargets[16] = "Plant";
$dbspelltargets[17] = "Giant";
$dbspelltargets[18] = "Dragon";
$dbspelltargets[24] = "Area of effect on undeads";
$dbspelltargets[36] = "Area - PC Only";
$dbspelltargets[40] = "Friendly area of effect";
$dbspelltargets[41] = "Group";

// item skills
$dbiskills     = [];
$dbiskills[0]  = "One Hand Slash";
$dbiskills[1]  = "Two Hands Slash";
$dbiskills[2]  = "Piercing";
$dbiskills[3]  = "One Hand Blunt";
$dbiskills[4]  = "Two Hands Blunt";
$dbiskills[45] = "Hand to hand";

// item types
$dbitypes     = [];
$dbitypes[0]  = "1HS";
$dbitypes[1]  = "2HS";
$dbitypes[2]  = "Piercing";
$dbitypes[3]  = "1HB";
$dbitypes[4]  = "2HB";
$dbitypes[5]  = "Archery";
$dbitypes[6]  = "Unknown";
$dbitypes[7]  = "Throwing range items";
$dbitypes[8]  = "Shield";
$dbitypes[9]  = "Unknown";
$dbitypes[10] = "Armor";
$dbitypes[11] = "Gems";
$dbitypes[12] = "Lockpicks";
$dbitypes[13] = "Unknown";
$dbitypes[14] = "Food";
$dbitypes[15] = "Drink";
$dbitypes[16] = "Light";
$dbitypes[17] = "Combinable";
$dbitypes[18] = "Bandages";
$dbitypes[19] = "Throwing";
$dbitypes[20] = "Scroll";
$dbitypes[21] = "Potion";
$dbitypes[22] = "Unknown";
$dbitypes[23] = "Wind Instrument";
$dbitypes[24] = "Stringed Instrument";
$dbitypes[25] = "Brass Instrument";
$dbitypes[26] = "Percussion Instrument";
$dbitypes[27] = "Arrow";
$dbitypes[28] = "Unknown";
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
$dbitypes[41] = "Unknown";
$dbitypes[42] = "Poison";
$dbitypes[43] = "Unknown";
$dbitypes[44] = "Unknown";
$dbitypes[45] = "Martial";
$dbitypes[46] = "Unknown";
$dbitypes[47] = "Unknown";
$dbitypes[48] = "Unknown";
$dbitypes[49] = "Unknown";
$dbitypes[50] = "Unknown";
$dbitypes[51] = "Unknown";
$dbitypes[52] = "Charm";
$dbitypes[53] = "Unknown";
$dbitypes[54] = "Augmentation";

$dbiaugrestrict[1]  = "Armor Only";
$dbiaugrestrict[2]  = "Weapons Only";
$dbiaugrestrict[3]  = "1h Weapons Only";
$dbiaugrestrict[4]  = "2h Weapons Only";
$dbiaugrestrict[5]  = "1h Slash Only";
$dbiaugrestrict[6]  = "1h Blunt Only";
$dbiaugrestrict[7]  = "Piercing Only";
$dbiaugrestrict[8]  = "Hand To Hand Only";
$dbiaugrestrict[9]  = "2h Slash Only";
$dbiaugrestrict[10] = "2h Blunt Only";
$dbiaugrestrict[11] = "2h Pierce Only";
$dbiaugrestrict[12] = "Bows Only";

$dbbardskills[23] = "Wind";
$dbbardskills[24] = "Strings";
$dbbardskills[25] = "Brass";
$dbbardskills[26] = "Percussions";
$dbbardskills[51] = "All instruments";

$NPCTypeArray = [
    '###' => 'Boss',
    '##'  => 'Mini-Boss',
    '#'   => 'Named',
    '~'   => 'Quest NPC',
    '!'   => 'Hidden',
    '_'   => 'Event Spawned',
];

// deities
$dbdeities      = [];
$dbdeities[0]   = "Unknown";
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
$dbideities        = [];
$dbideities[65536] = "Veeshan";
$dbideities[32768] = "Tunare";
$dbideities[16384] = "The Tribunal";
$dbideities[8192]  = "Solusek Ro";
$dbideities[4096]  = "Rodcet Nife";
$dbideities[2048]  = "Rallos Zek";
$dbideities[1024]  = "Quellious";
$dbideities[512]   = "Prexus";
$dbideities[256]   = "Mithaniel Marr";
$dbideities[128]   = "Karana";
$dbideities[64]    = "Innoruuk";
$dbideities[32]    = "Bristlebane";
$dbideities[16]    = "Erollisi Marr";
$dbideities[8]     = "Cazic Thule";
$dbideities[4]     = "Brell Serilis";
$dbideities[2]     = "Bertoxxulous";

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
$dbbodytypes     = [
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
    9  => "Alchemy",
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
    1   => "Nuke",
    2   => "Heal",
    4   => "Root",
    8   => "Buff",
    16  => "Escape",
    32  => "Pet",
    64  => "Lifetap",
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
    9 => "Corruption",
];

// Array of ALL races through VoA Expansion

$dbiracenames[0]   = "Invalid";
$dbiracenames[1]   = "Human";
$dbiracenames[2]   = "Barbarian";
$dbiracenames[3]   = "Erudite";
$dbiracenames[4]   = "Wood Elf";
$dbiracenames[5]   = "High Elf";
$dbiracenames[6]   = "Dark Elf";
$dbiracenames[7]   = "Half Elf";
$dbiracenames[8]   = "Dwarf";
$dbiracenames[9]   = "Troll";
$dbiracenames[10]  = "Ogre";
$dbiracenames[11]  = "Halfling";
$dbiracenames[12]  = "Gnome";
$dbiracenames[13]  = "Aviak";
$dbiracenames[14]  = "Werewolf";
$dbiracenames[15]  = "Brownie";
$dbiracenames[16]  = "Centaur";
$dbiracenames[17]  = "Golem";
$dbiracenames[18]  = "Giant";
$dbiracenames[19]  = "Trakanon";
$dbiracenames[20]  = "Venril Sathir";
$dbiracenames[21]  = "Evil Eye";
$dbiracenames[22]  = "Beetle";
$dbiracenames[23]  = "Kerran";
$dbiracenames[24]  = "Fish";
$dbiracenames[25]  = "Fairy";
$dbiracenames[26]  = "Froglok";
$dbiracenames[27]  = "Froglok";
$dbiracenames[28]  = "Fungusman";
$dbiracenames[29]  = "Gargoyle";
$dbiracenames[30]  = "Gasbag";
$dbiracenames[31]  = "Gelatinous Cube";
$dbiracenames[32]  = "Ghost";
$dbiracenames[33]  = "Ghoul";
$dbiracenames[34]  = "Bat";
$dbiracenames[35]  = "Eel";
$dbiracenames[36]  = "Rat";
$dbiracenames[37]  = "Snake";
$dbiracenames[38]  = "Spider";
$dbiracenames[39]  = "Gnoll";
$dbiracenames[40]  = "Goblin";
$dbiracenames[41]  = "Gorilla";
$dbiracenames[42]  = "Wolf";
$dbiracenames[43]  = "Bear";
$dbiracenames[44]  = "Guard";
$dbiracenames[45]  = "Demi Lich";
$dbiracenames[46]  = "Imp";
$dbiracenames[47]  = "Griffin";
$dbiracenames[48]  = "Kobold";
$dbiracenames[49]  = "Dragon";
$dbiracenames[50]  = "Lion";
$dbiracenames[51]  = "Lizard Man";
$dbiracenames[52]  = "Mimic";
$dbiracenames[53]  = "Minotaur";
$dbiracenames[54]  = "Orc";
$dbiracenames[55]  = "Beggar";
$dbiracenames[56]  = "Pixie";
$dbiracenames[57]  = "Drachnid";
$dbiracenames[58]  = "Solusek Ro";
$dbiracenames[59]  = "Goblin";
$dbiracenames[60]  = "Skeleton";
$dbiracenames[61]  = "Shark";
$dbiracenames[62]  = "Tunare";
$dbiracenames[63]  = "Tiger";
$dbiracenames[64]  = "Treant";
$dbiracenames[65]  = "Vampire";
$dbiracenames[66]  = "Rallos Zek";
$dbiracenames[67]  = "Human";
$dbiracenames[68]  = "Tentacle Terror";
$dbiracenames[69]  = "Will-O-Wisp";
$dbiracenames[70]  = "Zombie";
$dbiracenames[71]  = "Human";
$dbiracenames[72]  = "Ship";
$dbiracenames[73]  = "Launch";
$dbiracenames[74]  = "Piranha";
$dbiracenames[75]  = "Elemental";
$dbiracenames[76]  = "Puma";
$dbiracenames[77]  = "Dark Elf";
$dbiracenames[78]  = "Erudite";
$dbiracenames[79]  = "Bixie";
$dbiracenames[80]  = "Reanimated Hand";
$dbiracenames[81]  = "Halfling";
$dbiracenames[82]  = "Scarecrow";
$dbiracenames[83]  = "Skunk";
$dbiracenames[84]  = "Snake Elemental";
$dbiracenames[85]  = "Spectre";
$dbiracenames[86]  = "Sphinx";
$dbiracenames[87]  = "Armadillo";
$dbiracenames[88]  = "Clockwork Gnome";
$dbiracenames[89]  = "Drake";
$dbiracenames[90]  = "Barbarian";
$dbiracenames[91]  = "Alligator";
$dbiracenames[92]  = "Troll";
$dbiracenames[93]  = "Ogre";
$dbiracenames[94]  = "Dwarf";
$dbiracenames[95]  = "Cazic Thule";
$dbiracenames[96]  = "Cockatrice";
$dbiracenames[97]  = "Daisy Man";
$dbiracenames[98]  = "Vampire";
$dbiracenames[99]  = "Amygdalan";
$dbiracenames[100] = "Dervish";
$dbiracenames[101] = "Efreeti";
$dbiracenames[102] = "Tadpole";
$dbiracenames[103] = "Kedge";
$dbiracenames[104] = "Leech";
$dbiracenames[105] = "Swordfish";
$dbiracenames[106] = "Guard";
$dbiracenames[107] = "Mammoth";
$dbiracenames[108] = "Eye";
$dbiracenames[109] = "Wasp";
$dbiracenames[110] = "Mermaid";
$dbiracenames[111] = "Harpy";
$dbiracenames[112] = "Guard";
$dbiracenames[113] = "Drixie";
$dbiracenames[114] = "Ghost Ship";
$dbiracenames[115] = "Clam";
$dbiracenames[116] = "Seahorse";
$dbiracenames[117] = "Ghost";
$dbiracenames[118] = "Ghost";
$dbiracenames[119] = "Sabertooth";
$dbiracenames[120] = "Wolf";
$dbiracenames[121] = "Gorgon";
$dbiracenames[122] = "Dragon";
$dbiracenames[123] = "Innoruuk";
$dbiracenames[124] = "Unicorn";
$dbiracenames[125] = "Pegasus";
$dbiracenames[126] = "Djinn";
$dbiracenames[127] = "Invisible Man";
$dbiracenames[128] = "Iksar";
$dbiracenames[129] = "Scorpion";
$dbiracenames[130] = "Vah Shir";
$dbiracenames[131] = "Sarnak";
$dbiracenames[132] = "Draglock";
$dbiracenames[133] = "Drolvarg";
$dbiracenames[134] = "Mosquito";
$dbiracenames[135] = "Rhinoceros";
$dbiracenames[136] = "Xalgoz";
$dbiracenames[137] = "Goblin";
$dbiracenames[138] = "Yeti";
$dbiracenames[139] = "Iksar";
$dbiracenames[140] = "Giant";
$dbiracenames[141] = "Boat";
$dbiracenames[142] = "Uknown";
$dbiracenames[143] = "Uknown";
$dbiracenames[144] = "Burynai";
$dbiracenames[145] = "Goo";
$dbiracenames[146] = "Sarnak Spirit";
$dbiracenames[147] = "Iksar Spirit";
$dbiracenames[148] = "Fish";
$dbiracenames[149] = "Scorpion";
$dbiracenames[150] = "Erollisi";
$dbiracenames[151] = "Tribunal";
$dbiracenames[152] = "Bertoxxulous";
$dbiracenames[153] = "Bristlebane";
$dbiracenames[154] = "Fay Drake";
$dbiracenames[155] = "Undead Sarnak";
$dbiracenames[156] = "Ratman";
$dbiracenames[157] = "Wyvern";
$dbiracenames[158] = "Wurm";
$dbiracenames[159] = "Devourer";
$dbiracenames[160] = "Iksar Golem";
$dbiracenames[161] = "Undead Iksar";
$dbiracenames[162] = "Man-Eating Plant";
$dbiracenames[163] = "Raptor";
$dbiracenames[164] = "Sarnak Golem";
$dbiracenames[165] = "Dragon";
$dbiracenames[166] = "Animated Hand";
$dbiracenames[167] = "Succulent";
$dbiracenames[168] = "Holgresh";
$dbiracenames[169] = "Brontotherium";
$dbiracenames[170] = "Snow Dervish";
$dbiracenames[171] = "Dire Wolf";
$dbiracenames[172] = "Manticore";
$dbiracenames[173] = "Totem";
$dbiracenames[174] = "Ice Spectre";
$dbiracenames[175] = "Enchanted Armor";
$dbiracenames[176] = "Snow Rabbit";
$dbiracenames[177] = "Walrus";
$dbiracenames[178] = "Geonid";
$dbiracenames[179] = "Uknown";
$dbiracenames[180] = "Uknown";
$dbiracenames[181] = "Yakkar";
$dbiracenames[182] = "Faun";
$dbiracenames[183] = "Coldain";
$dbiracenames[184] = "Dragon";
$dbiracenames[185] = "Hag";
$dbiracenames[186] = "Hippogriff";
$dbiracenames[187] = "Siren";
$dbiracenames[188] = "Giant";
$dbiracenames[189] = "Giant";
$dbiracenames[190] = "Othmir";
$dbiracenames[191] = "Ulthork";
$dbiracenames[192] = "Dragon";
$dbiracenames[193] = "Abhorrent";
$dbiracenames[194] = "Sea Turtle";
$dbiracenames[195] = "Dragon";
$dbiracenames[196] = "Dragon";
$dbiracenames[197] = "Ronnie Test";
$dbiracenames[198] = "Dragon";
$dbiracenames[199] = "Shik\'Nar";
$dbiracenames[200] = "Rockhopper";
$dbiracenames[201] = "Underbulk";
$dbiracenames[202] = "Grimling";
$dbiracenames[203] = "Worm";
$dbiracenames[204] = "Evan Test";
$dbiracenames[205] = "Shadel";
$dbiracenames[206] = "Owlbear";
$dbiracenames[207] = "Rhino Beetle";
$dbiracenames[208] = "Vampire";
$dbiracenames[209] = "Earth Elemental";
$dbiracenames[210] = "Air Elemental";
$dbiracenames[211] = "Water Elemental";
$dbiracenames[212] = "Fire Elemental";
$dbiracenames[213] = "Wetfang Minnow";
$dbiracenames[214] = "Thought Horror";
$dbiracenames[215] = "Tegi";
$dbiracenames[216] = "Horse";
$dbiracenames[217] = "Shissar";
$dbiracenames[218] = "Fungal Fiend";
$dbiracenames[219] = "Vampire";
$dbiracenames[220] = "Stonegrabber";
$dbiracenames[221] = "Scarlet Cheetah";
$dbiracenames[222] = "Zelniak";
$dbiracenames[223] = "Lightcrawler";
$dbiracenames[224] = "Shade";
$dbiracenames[225] = "Sunflower";
$dbiracenames[226] = "Sun Revenant";
$dbiracenames[227] = "Shrieker";
$dbiracenames[228] = "Galorian";
$dbiracenames[229] = "Netherbian";
$dbiracenames[230] = "Akheva";
$dbiracenames[231] = "Grieg Veneficus";
$dbiracenames[232] = "Sonic Wolf";
$dbiracenames[233] = "Ground Shaker";
$dbiracenames[234] = "Vah Shir Skeleton";
$dbiracenames[235] = "Wretch";
$dbiracenames[236] = "Seru";
$dbiracenames[237] = "Recuso";
$dbiracenames[238] = "Vah Shir";
$dbiracenames[239] = "Guard";
$dbiracenames[240] = "Teleport Man";
$dbiracenames[241] = "Werewolf";
$dbiracenames[242] = "Nymph";
$dbiracenames[243] = "Dryad";
$dbiracenames[244] = "Treant";
$dbiracenames[245] = "Fly";
$dbiracenames[246] = "Tarew Marr";
$dbiracenames[247] = "Solusek Ro";
$dbiracenames[248] = "Clockwork Golem";
$dbiracenames[249] = "Clockwork Brain";
$dbiracenames[250] = "Banshee";
$dbiracenames[251] = "Guard of Justice";
$dbiracenames[252] = "Mini POM";
$dbiracenames[253] = "Diseased Fiend";
$dbiracenames[254] = "Solusek Ro Guard";
$dbiracenames[255] = "Bertoxxulous";
$dbiracenames[256] = "The Tribunal";
$dbiracenames[257] = "Terris Thule";
$dbiracenames[258] = "Vegerog";
$dbiracenames[259] = "Crocodile";
$dbiracenames[260] = "Bat";
$dbiracenames[261] = "Hraquis";
$dbiracenames[262] = "Tranquilion";
$dbiracenames[263] = "Tin Soldier";
$dbiracenames[264] = "Nightmare Wraith";
$dbiracenames[265] = "Malarian";
$dbiracenames[266] = "Knight of Pestilence";
$dbiracenames[267] = "Lepertoloth";
$dbiracenames[268] = "Bubonian";
$dbiracenames[269] = "Bubonian Underling";
$dbiracenames[270] = "Pusling";
$dbiracenames[271] = "Water Mephit";
$dbiracenames[272] = "Stormrider";
$dbiracenames[273] = "Junk Beast";
$dbiracenames[274] = "Broken Clockwork";
$dbiracenames[275] = "Giant Clockwork";
$dbiracenames[276] = "Clockwork Beetle";
$dbiracenames[277] = "Nightmare Goblin";
$dbiracenames[278] = "Karana";
$dbiracenames[279] = "Blood Raven";
$dbiracenames[280] = "Nightmare Gargoyle";
$dbiracenames[281] = "Mouth of Insanity";
$dbiracenames[282] = "Skeletal Horse";
$dbiracenames[283] = "Saryrn";
$dbiracenames[284] = "Fennin Ro";
$dbiracenames[285] = "Tormentor";
$dbiracenames[286] = "Soul Devourer";
$dbiracenames[287] = "Nightmare";
$dbiracenames[288] = "Rallos Zek";
$dbiracenames[289] = "Vallon Zek";
$dbiracenames[290] = "Tallon Zek";
$dbiracenames[291] = "Air Mephit";
$dbiracenames[292] = "Earth Mephit";
$dbiracenames[293] = "Fire Mephit";
$dbiracenames[294] = "Nightmare Mephit";
$dbiracenames[295] = "Zebuxoruk";
$dbiracenames[296] = "Mithaniel Marr";
$dbiracenames[297] = "Undead Knight";
$dbiracenames[298] = "The Rathe";
$dbiracenames[299] = "Xegony";
$dbiracenames[300] = "Fiend";
$dbiracenames[301] = "Test Object";
$dbiracenames[302] = "Crab";
$dbiracenames[303] = "Phoenix";
$dbiracenames[304] = "Dragon";
$dbiracenames[305] = "Bear";
$dbiracenames[306] = "Giant";
$dbiracenames[307] = "Giant";
$dbiracenames[308] = "Giant";
$dbiracenames[309] = "Giant";
$dbiracenames[310] = "Giant";
$dbiracenames[311] = "Giant";
$dbiracenames[312] = "Giant";
$dbiracenames[313] = "War Wraith";
$dbiracenames[314] = "Wrulon";
$dbiracenames[315] = "Kraken";
$dbiracenames[316] = "Poison Frog";
$dbiracenames[317] = "Nilborien";
$dbiracenames[318] = "Valorian";
$dbiracenames[319] = "War Boar";
$dbiracenames[320] = "Efreeti";
$dbiracenames[321] = "War Boar";
$dbiracenames[322] = "Valorian";
$dbiracenames[323] = "Animated Armor";
$dbiracenames[324] = "Undead Footman";
$dbiracenames[325] = "Rallos Zek Minion";
$dbiracenames[326] = "Arachnid";
$dbiracenames[327] = "Crystal Spider";
$dbiracenames[328] = "Zebuxoruk\'s Cage";
$dbiracenames[329] = "BoT Portal";
$dbiracenames[330] = "Froglok";
$dbiracenames[331] = "Troll";
$dbiracenames[332] = "Troll";
$dbiracenames[333] = "Troll";
$dbiracenames[334] = "Ghost";
$dbiracenames[335] = "Pirate";
$dbiracenames[336] = "Pirate";
$dbiracenames[337] = "Pirate";
$dbiracenames[338] = "Pirate";
$dbiracenames[339] = "Pirate";
$dbiracenames[340] = "Pirate";
$dbiracenames[341] = "Pirate";
$dbiracenames[342] = "Pirate";
$dbiracenames[343] = "Frog";
$dbiracenames[344] = "Troll Zombie";
$dbiracenames[345] = "Luggald";
$dbiracenames[346] = "Luggald";
$dbiracenames[347] = "Luggalds";
$dbiracenames[348] = "Drogmore";
$dbiracenames[349] = "Froglok Skeleton";
$dbiracenames[350] = "Undead Froglok";
$dbiracenames[351] = "Knight of Hate";
$dbiracenames[352] = "Arcanist of Hate";
$dbiracenames[353] = "Veksar";
$dbiracenames[354] = "Veksar";
$dbiracenames[355] = "Veksar";
$dbiracenames[356] = "Chokadai";
$dbiracenames[357] = "Undead Chokadai";
$dbiracenames[358] = "Undead Veksar";
$dbiracenames[359] = "Vampire";
$dbiracenames[360] = "Vampire";
$dbiracenames[361] = "Rujarkian Orc";
$dbiracenames[362] = "Bone Golem";
$dbiracenames[363] = "Synarcana";
$dbiracenames[364] = "Sand Elf";
$dbiracenames[365] = "Vampire";
$dbiracenames[366] = "Rujarkian Orc";
$dbiracenames[367] = "Skeleton";
$dbiracenames[368] = "Mummy";
$dbiracenames[369] = "Goblin";
$dbiracenames[370] = "Insect";
$dbiracenames[371] = "Froglok Ghost";
$dbiracenames[372] = "Dervish";
$dbiracenames[373] = "Shade";
$dbiracenames[374] = "Golem";
$dbiracenames[375] = "Evil Eye";
$dbiracenames[376] = "Box";
$dbiracenames[377] = "Barrel";
$dbiracenames[378] = "Chest";
$dbiracenames[379] = "Vase";
$dbiracenames[380] = "Table";
$dbiracenames[381] = "Weapon Rack";
$dbiracenames[382] = "Coffin";
$dbiracenames[383] = "Bones";
$dbiracenames[384] = "Jokester";
$dbiracenames[385] = "Nihil";
$dbiracenames[386] = "Trusik";
$dbiracenames[387] = "Stone Worker";
$dbiracenames[388] = "Hynid";
$dbiracenames[389] = "Turepta";
$dbiracenames[390] = "Cragbeast";
$dbiracenames[391] = "Stonemite";
$dbiracenames[392] = "Ukun";
$dbiracenames[393] = "Ixt";
$dbiracenames[394] = "Ikaav";
$dbiracenames[395] = "Aneuk";
$dbiracenames[396] = "Kyv";
$dbiracenames[397] = "Noc";
$dbiracenames[398] = "Ra`tuk";
$dbiracenames[399] = "Taneth";
$dbiracenames[400] = "Huvul";
$dbiracenames[401] = "Mutna";
$dbiracenames[402] = "Mastruq";
$dbiracenames[403] = "Taelosian";
$dbiracenames[404] = "Discord Ship";
$dbiracenames[405] = "Stone Worker";
$dbiracenames[406] = "Mata Muram";
$dbiracenames[407] = "Lightning Warrior";
$dbiracenames[408] = "Succubus";
$dbiracenames[409] = "Bazu";
$dbiracenames[410] = "Feran";
$dbiracenames[411] = "Pyrilen";
$dbiracenames[412] = "Chimera";
$dbiracenames[413] = "Dragorn";
$dbiracenames[414] = "Murkglider";
$dbiracenames[415] = "Rat";
$dbiracenames[416] = "Bat";
$dbiracenames[417] = "Gelidran";
$dbiracenames[418] = "Discordling";
$dbiracenames[419] = "Girplan";
$dbiracenames[420] = "Minotaur";
$dbiracenames[421] = "Dragorn Box";
$dbiracenames[422] = "Runed Orb";
$dbiracenames[423] = "Dragon Bones";
$dbiracenames[424] = "Muramite Armor Pile";
$dbiracenames[425] = "Crystal Shard";
$dbiracenames[426] = "Portal";
$dbiracenames[427] = "Coin Purse";
$dbiracenames[428] = "Rock Pile";
$dbiracenames[429] = "Murkglider Egg Sack";
$dbiracenames[430] = "Drake";
$dbiracenames[431] = "Dervish";
$dbiracenames[432] = "Drake";
$dbiracenames[433] = "Goblin";
$dbiracenames[434] = "Kirin";
$dbiracenames[435] = "Dragon";
$dbiracenames[436] = "Basilisk";
$dbiracenames[437] = "Dragon";
$dbiracenames[438] = "Dragon";
$dbiracenames[439] = "Puma";
$dbiracenames[440] = "Spider";
$dbiracenames[441] = "Spider Queen";
$dbiracenames[442] = "Animated Statue";
$dbiracenames[443] = "Uknown";
$dbiracenames[444] = "Uknown";
$dbiracenames[445] = "Dragon Egg";
$dbiracenames[446] = "Dragon Statue";
$dbiracenames[447] = "Lava Rock";
$dbiracenames[448] = "Animated Statue";
$dbiracenames[449] = "Spider Egg Sack";
$dbiracenames[450] = "Lava Spider";
$dbiracenames[451] = "Lava Spider Queen";
$dbiracenames[452] = "Dragon";
$dbiracenames[453] = "Giant";
$dbiracenames[454] = "Werewolf";
$dbiracenames[455] = "Kobold";
$dbiracenames[456] = "Sporali";
$dbiracenames[457] = "Gnomework";
$dbiracenames[458] = "Orc";
$dbiracenames[459] = "Corathus";
$dbiracenames[460] = "Coral";
$dbiracenames[461] = "Drachnid";
$dbiracenames[462] = "Drachnid Cocoon";
$dbiracenames[463] = "Fungus Patch";
$dbiracenames[464] = "Gargoyle";
$dbiracenames[465] = "Witheran";
$dbiracenames[466] = "Dark Lord";
$dbiracenames[467] = "Shiliskin";
$dbiracenames[468] = "Snake";
$dbiracenames[469] = "Evil Eye";
$dbiracenames[470] = "Minotaur";
$dbiracenames[471] = "Zombie";
$dbiracenames[472] = "Clockwork Boar";
$dbiracenames[473] = "Fairy";
$dbiracenames[474] = "Witheran";
$dbiracenames[475] = "Air Elemental";
$dbiracenames[476] = "Earth Elemental";
$dbiracenames[477] = "Fire Elemental";
$dbiracenames[478] = "Water Elemental";
$dbiracenames[479] = "Alligator";
$dbiracenames[480] = "Bear";
$dbiracenames[481] = "Scaled Wolf";
$dbiracenames[482] = "Wolf";
$dbiracenames[483] = "Spirit Wolf";
$dbiracenames[484] = "Skeleton";
$dbiracenames[485] = "Spectre";
$dbiracenames[486] = "Bolvirk";
$dbiracenames[487] = "Banshee";
$dbiracenames[488] = "Banshee";
$dbiracenames[489] = "Elddar";
$dbiracenames[490] = "Forest Giant";
$dbiracenames[491] = "Bone Golem";
$dbiracenames[492] = "Horse";
$dbiracenames[493] = "Pegasus";
$dbiracenames[494] = "Shambling Mound";
$dbiracenames[495] = "Scrykin";
$dbiracenames[496] = "Treant";
$dbiracenames[497] = "Vampire";
$dbiracenames[498] = "Ayonae Ro";
$dbiracenames[499] = "Sullon Zek";
$dbiracenames[500] = "Banner";
$dbiracenames[501] = "Flag";
$dbiracenames[502] = "Rowboat";
$dbiracenames[503] = "Bear Trap";
$dbiracenames[504] = "Clockwork Bomb";
$dbiracenames[505] = "Dynamite Keg";
$dbiracenames[506] = "Pressure Plate";
$dbiracenames[507] = "Puffer Spore";
$dbiracenames[508] = "Stone Ring";
$dbiracenames[509] = "Root Tentacle";
$dbiracenames[510] = "Runic Symbol";
$dbiracenames[511] = "Saltpetter Bomb";
$dbiracenames[512] = "Floating Skull";
$dbiracenames[513] = "Spike Trap";
$dbiracenames[514] = "Totem";
$dbiracenames[515] = "Web";
$dbiracenames[516] = "Wicker Basket";
$dbiracenames[517] = "Nightmare/Unicorn";
$dbiracenames[518] = "Horse";
$dbiracenames[519] = "Nightmare/Unicorn";
$dbiracenames[520] = "Bixie";
$dbiracenames[521] = "Centaur";
$dbiracenames[522] = "Drakkin";
$dbiracenames[523] = "Giant";
$dbiracenames[524] = "Gnoll";
$dbiracenames[525] = "Griffin";
$dbiracenames[526] = "Giant Shade";
$dbiracenames[527] = "Harpy";
$dbiracenames[528] = "Mammoth";
$dbiracenames[529] = "Satyr";
$dbiracenames[530] = "Dragon";
$dbiracenames[531] = "Dragon";
$dbiracenames[532] = "Dyn\'Leth";
$dbiracenames[533] = "Boat";
$dbiracenames[534] = "Weapon Rack";
$dbiracenames[535] = "Armor Rack";
$dbiracenames[536] = "Honey Pot";
$dbiracenames[537] = "Jum Jum Bucket";
$dbiracenames[538] = "Toolbox";
$dbiracenames[539] = "Stone Jug";
$dbiracenames[540] = "Small Plant";
$dbiracenames[541] = "Medium Plant";
$dbiracenames[542] = "Tall Plant";
$dbiracenames[543] = "Wine Cask";
$dbiracenames[544] = "Elven Boat";
$dbiracenames[545] = "Gnomish Boat";
$dbiracenames[546] = "Barrel Barge Ship";
$dbiracenames[547] = "Goo";
$dbiracenames[548] = "Goo";
$dbiracenames[549] = "Goo";
$dbiracenames[550] = "Merchant Ship";
$dbiracenames[551] = "Pirate Ship";
$dbiracenames[552] = "Ghost Ship";
$dbiracenames[553] = "Banner";
$dbiracenames[554] = "Banner";
$dbiracenames[555] = "Banner";
$dbiracenames[556] = "Banner";
$dbiracenames[557] = "Banner";
$dbiracenames[558] = "Aviak";
$dbiracenames[559] = "Beetle";
$dbiracenames[560] = "Gorilla";
$dbiracenames[561] = "Kedge";
$dbiracenames[562] = "Kerran";
$dbiracenames[563] = "Shissar";
$dbiracenames[564] = "Siren";
$dbiracenames[565] = "Sphinx";
$dbiracenames[566] = "Human";
$dbiracenames[567] = "Campfire";
$dbiracenames[568] = "Brownie";
$dbiracenames[569] = "Dragon";
$dbiracenames[570] = "Exoskeleton";
$dbiracenames[571] = "Ghoul";
$dbiracenames[572] = "Clockwork Guardian";
$dbiracenames[573] = "Mantrap";
$dbiracenames[574] = "Minotaur";
$dbiracenames[575] = "Scarecrow";
$dbiracenames[576] = "Shade";
$dbiracenames[577] = "Rotocopter";
$dbiracenames[578] = "Tentacle Terror";
$dbiracenames[579] = "Wereorc";
$dbiracenames[580] = "Worg";
$dbiracenames[581] = "Wyvern";
$dbiracenames[582] = "Chimera";
$dbiracenames[583] = "Kirin";
$dbiracenames[584] = "Puma";
$dbiracenames[585] = "Boulder";
$dbiracenames[586] = "Banner";
$dbiracenames[587] = "Elven Ghost";
$dbiracenames[588] = "Human Ghost";
$dbiracenames[589] = "Chest";
$dbiracenames[590] = "Chest";
$dbiracenames[591] = "Crystal";
$dbiracenames[592] = "Coffin";
$dbiracenames[593] = "Guardian CPU";
$dbiracenames[594] = "Worg";
$dbiracenames[595] = "Mansion";
$dbiracenames[596] = "Floating Island";
$dbiracenames[597] = "Cragslither";
$dbiracenames[598] = "Wrulon";
$dbiracenames[599] = "Spell Particle 1";
$dbiracenames[600] = "Invisible Man of Zomm";
$dbiracenames[601] = "Robocopter of Zomm";
$dbiracenames[602] = "Burynai";
$dbiracenames[603] = "Frog";
$dbiracenames[604] = "Dracolich";
$dbiracenames[605] = "Iksar Ghost";
$dbiracenames[606] = "Iksar Skeleton";
$dbiracenames[607] = "Mephit";
$dbiracenames[608] = "Muddite";
$dbiracenames[609] = "Raptor";
$dbiracenames[610] = "Sarnak";
$dbiracenames[611] = "Scorpion";
$dbiracenames[612] = "Tsetsian";
$dbiracenames[613] = "Wurm";
$dbiracenames[614] = "Balrog";
$dbiracenames[615] = "Hydra Crystal";
$dbiracenames[616] = "Crystal Sphere";
$dbiracenames[617] = "Gnoll";
$dbiracenames[618] = "Sokokar";
$dbiracenames[619] = "Stone Pylon";
$dbiracenames[620] = "Demon Vulture";
$dbiracenames[621] = "Wagon";
$dbiracenames[622] = "God of Discord";
$dbiracenames[623] = "Wrulon Mount";
$dbiracenames[624] = "Ogre NPC - Male";
$dbiracenames[625] = "Sokokar Mount";
$dbiracenames[626] = "Giant (Rallosian mats)";
$dbiracenames[627] = "Sokokar (w saddle)";
$dbiracenames[628] = "10th Anniversary Banner";
$dbiracenames[629] = "10th Anniversary Cake";
$dbiracenames[630] = "Wine Cask";
$dbiracenames[631] = "Hydra Mount";
$dbiracenames[632] = "Hydra NPC";
$dbiracenames[633] = "Wedding Flowers";
$dbiracenames[634] = "Wedding Arbor";
$dbiracenames[635] = "Wedding Altar";
$dbiracenames[636] = "Powder Keg";
$dbiracenames[637] = "Apexus";
$dbiracenames[638] = "Bellikos";
$dbiracenames[639] = "Brell\'s First Creation";
$dbiracenames[640] = "Brell";
$dbiracenames[641] = "Crystalskin Ambuloid";
$dbiracenames[642] = "Cliknar Queen";
$dbiracenames[643] = "Cliknar Soldier";
$dbiracenames[644] = "Cliknar Worker";
$dbiracenames[645] = "Coldain";
$dbiracenames[646] = "Coldain";
$dbiracenames[647] = "Crystalskin Sessiloid";
$dbiracenames[648] = "Genari";
$dbiracenames[649] = "Gigyn";
$dbiracenames[650] = "Greken - Young Adult";
$dbiracenames[651] = "Greken - Young";
$dbiracenames[652] = "Cliknar Mount";
$dbiracenames[653] = "Telmira";
$dbiracenames[654] = "Spider Mount";
$dbiracenames[655] = "Bear Mount";
$dbiracenames[656] = "Rat Mount";
$dbiracenames[657] = "Sessiloid Mount";
$dbiracenames[658] = "Morell Thule";
$dbiracenames[659] = "Marionette";
$dbiracenames[660] = "Book Dervish";
$dbiracenames[661] = "Topiary Lion";
$dbiracenames[662] = "Rotdog";
$dbiracenames[663] = "Amygdalan";
$dbiracenames[664] = "Sandman";
$dbiracenames[665] = "Grandfather Clock";
$dbiracenames[666] = "Gingerbread Man";
$dbiracenames[667] = "Beefeater";
$dbiracenames[668] = "Rabbit";
$dbiracenames[669] = "Blind Dreamer";
$dbiracenames[670] = "Cazic Thule";
$dbiracenames[671] = "Topiary Lion Mount";
$dbiracenames[672] = "Rot Dog Mount";
$dbiracenames[673] = "Goral Mount";
$dbiracenames[674] = "Selyran Mount";
$dbiracenames[675] = "Sclera Mount";
$dbiracenames[676] = "Braxy Mount";
$dbiracenames[677] = "Kangon Mount";
$dbiracenames[678] = "Erudite";
$dbiracenames[679] = "Wurm Mount";