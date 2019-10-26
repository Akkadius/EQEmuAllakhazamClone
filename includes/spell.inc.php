<?php

function SpellDescription($spell, $n, $csv = false) {
    global $dbspelleffects, $items_table, $dbiracenames, $spells_table, $server_max_level;
    $print_buffer = "<tr>";
    if (($spell["effectid$n"] != 254) AND ($spell["effectid$n"] != 10)) {
        $maxlvl = $spell["effect_base_value$n"];
        $minlvl = $server_max_level;
        for ($i = 1; $i <= 16; $i++) {
            if ($spell["classes" . $i] < $minlvl) {
                $minlvl = $spell["classes" . $i];
            }
        }
        $min = CalcSpellEffectValue(
            $spell["formula" . $n],
            $spell["effect_base_value$n"],
            $spell["max$n"],
            $minlvl
        );
        $max = CalcSpellEffectValue(
            $spell["formula" . $n],
            $spell["effect_base_value$n"],
            $spell["max$n"],
            $server_max_level
        );
        $base_limit = $spell["effect_limit_value$n"];
        if (($min < $max) AND ($max < 0)) {
            $tn  = $min;
            $min = $max;
            $max = $tn;
        }
        if ($csv == true) {
            $print_buffer .= ",,";
        } else {
            $print_buffer .= "<td><b>Effect $n</b></td>";
        }
        switch ($spell["effectid$n"]) {
            case 3: // Increase Movement (% / 0)				
				$print_buffer .= "<td>";
					if ($max < 0) { // Decrease
						$print_buffer .= "Decrease Movement";
						if ($min != $max) {
							$print_buffer .= " by " . abs($min) . "% (Level $minlvl) to " . abs($max) . "% (Level $maxlvl)";
						} else {
							$print_buffer .= " by " . abs(100) . "%";
						}
					} else {
						$print_buffer .= "Increase Movement";
						if ($min != $max) {
							$print_buffer .= " by " . $min . "% (Level $minlvl) to " . ($max) . "% (Level $maxlvl)";
						} else {
							$print_buffer .= " by " . ($max) . "%";
						}
					}
				$print_buffer .= "</td>";
                break;
            case 11: // Decrease OR Inscrease AttackSpeed (max/min = percentage of speed / normal speed, IE, 70=>-30% 130=>+30%
				$print_buffer .= "<td>";
					if ($max < 100) { // Decrease
						$print_buffer .= "Decrease Attack Speed";
						if ($min != $max) {
							$print_buffer .= " by " . (100 - $min) . "% (Level $minlvl) to " . (100 - $max) . "% (Level $maxlvl)";
						} else {
							$print_buffer .= " by " . (100 - $max) . "%";
						}
					} else {
						$print_buffer .= "Increase Attack Speed";
						if ($min != $max) {
							$print_buffer .= " by " . ($min - 100) . "% (Level $minlvl) to " . ($max - 100) . "% (Level $maxlvl)";
						} else {
							$print_buffer .= " by " . ($max - 100) . "%";
						}
					}
				$print_buffer .= "</td>";
                break;
            case 21: // stun
				$print_buffer .= "<td>";
					$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
					if ($min != $max) {
						$print_buffer .= " (" . ($min / 1000) . " Second(s) (Level $minlvl) to " . ($max / 1000) . " Second(s) (Level $maxlvl))";
					} else {
						$print_buffer .= " (" . ($max / 1000) . " Second(s))";
					}
				$print_buffer .= "</td>";
                break;
            case 32: // summonitem
				$print_buffer .= "<td>";
					$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
					$name = get_field_result(
						"name",
						"SELECT name FROM $items_table WHERE id=" . $spell["effect_base_value$n"]
					);
					if (($name != "") AND ($csv == false)) {
						$print_buffer .= ": <a href=?a=item&id=" . $spell["effect_base_value$n"] . ">$name</a>";
					} else {
						$print_buffer .= ": $name";
					}
				$print_buffer .= "</td>";
                break;
            case 87: // Increase Magnification
            case 98: // Increase Haste v2
            case 114: // Increase Agro Multiplier
            case 119: // Increase Haste v3
            case 123: // Increase Spell Damage
            case 124: // Increase Spell Damage
            case 125: // Increase Spell Healing
            case 127: // Increase Spell Haste
            case 128: // Increase Spell Duration
            case 129: // Increase Spell Range
            case 130: // Decrease Spell/Bash Hate
            case 131: // Decrease Chance of Using Reagent
            case 132: // Decrease Spell Mana Cost
            case 158: // Increase Chance to Reflect Spell
            case 168: // Increase Melee Mitigation
            case 169: // Increase Chance to Critical Hit
            case 172: // Increase Chance to Avoid Melee
            case 173: // Increase Chance to Riposte
            case 174: // Increase Chance to Dodge
            case 175: // Increase Chance to Parry
            case 176: // Increase Chance to Dual Wield
            case 177: // Increase Chance to Double Attack
            case 180: // Increase Chance to Resist Spell
            case 181: // Increase Chance to Resist Fear Spell
            case 183: // Increase All Skills Skill Check
            case 184: // Increase Chance to Hit With all Skills
            case 185: // Increase All Skills Damage Modifier
            case 186: // Increase All Skills Minimum Damage Modifier
            case 188: // Increase Chance to Block
            case 200: // Increase Proc Modifier
            case 201: // Increase Range Proc Modifier
            case 216: // Increase Accuracy
            case 227: // Reduce Skill Timer
            case 266: // Add Attack Chance
            case 273: // Increase Critical Dot Chance
            case 294: // Increase Critical Spell Chance
				$print_buffer .= "<td>";
					$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
					if ($min != $max) {
						$print_buffer .= " by $min% (Level $minlvl) to $max% (Level $maxlvl)";
					} else {
						$print_buffer .= " by $max%";
					}
				$print_buffer .= "</td>";
                break;
            case 15: // Increase Mana per tick
            case 100: // Increase Hitpoints v2 per tick
				$print_buffer .= "<td>";
					$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
					if ($min != $max) {
						$print_buffer .= " by " . abs($min) . " (Level $minlvl) to " . abs(
								$max
							) . " (Level $maxlvl) per Tic (Total " . abs($min * $duration) . " to " . abs(
												$max * $duration
											) . ")";
					} else {
						$print_buffer .= " by $max per Tic (Total " . abs($max * $duration) . ")";
					}
				$print_buffer .= "</td>";
                break;
            case 30: // Frenzy Radius
            case 86: // Reaction Radius
				$print_buffer .= "<td>";
					$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
					$print_buffer .= " (" . $spell["effect_base_value$n"] . "/" . $spell["effect_limit_value$n"] . ")";
				$print_buffer .= "</td>";
                break;
            case 22: // Charm
            case 23: // Fear
            case 31: // Mesmerize
				$print_buffer .= "<td>";
					$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
					$print_buffer .= " up to Level " . $spell["effect_limit_value$n"];
				$print_buffer .= "</td>";
                break;
            case 33: // Summon Pet:
            case 68: // Summon Skeleton Pet:
			case 71: // Summon Undead:
            case 106: // Summon Warder:
            case 108: // Summon Familiar:
            case 113: // Summon Horse:
            case 152: // Summon Pets:
				$print_buffer .= "<td>";
					$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
					if ($csv == false) {
						$print_buffer .= " <a href='?a=pet&name=" . $spell["teleport_zone"] . "'>" . $spell["teleport_zone"] . "</a>";
					} else {
						$print_buffer .= ": " . $spell["teleport_zone"];
					}
				$print_buffer .= "</td>";
                break;
            case 13: // See Invisible
            case 18: // Pacify
            case 20: // Blindness
            case 25: // Bind Affinity
            case 26: // Gate
            case 28: // Invisibility versus Undead
            case 29: // Invisibility versus Animals
            case 40: // Invunerability
            case 41: // Destroy Target
            case 42: // Shadowstep
            case 44: // Lycanthropy
            case 52: // Sense Undead
            case 53: // Sense Summoned
            case 54: // Sense Animals
            case 56: // True North
            case 57: // Levitate
            case 61: // Identify
            case 64: // SpinStun
            case 65: // Infravision
            case 66: // UltraVision
            case 67: // Eye of Zomm
            case 68: // Reclaim Energy
            case 73: // Bind Sight
            case 74: // Feign Death
            case 75: // Voice Graft
            case 76: // Sentinel
            case 77: // Locate Corpse
            case 82: // Summon PC
            case 90: // Cloak
            case 93: // Stop Rain
            case 94: // Make Fragile (Delete if combat)
            case 95: // Sacrifice
            case 96: // Silence
            case 99: // Root
            case 101: // Complete Heal (with duration)
            case 103: // Call Pet
            case 104: // Translocate target to their bind point
            case 105: // Anti-Gate
            case 115: // Food/Water
            case 117: // Make Weapons Magical
            case 135: // Limit: Resist(Magic allowed)
            case 137: // Limit: Effect(Hitpoints allowed)
            case 138: // Limit: Spell Type(Detrimental only)
            case 141: // Limit: Instant spells only
            case 150: // Death Save - Restore Full Health
            case 151: // Suspend Pet - Lose Buffs and Equipment
            case 154: // Remove Detrimental
            case 156: // Illusion: Target
            case 178: // Lifetap from Weapon Damage
            case 179: // Instrument Modifier
            case 182: // Hundred Hands Effect
            case 194: // Fade
            case 195: // Stun Resist
            case 205: // Rampage
            case 206: // Area of Effect Taunt
            case 311: // Limit: Combat Skills Not Allowed
            case 314: // Fixed Duration Invisbility
            case 299: // Wake the Dead
				$print_buffer .= "<td>";
					$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
				$print_buffer .= "</td>";
                break;
            case 58: // Illusion:
				$print_buffer .= "<td>";
					$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
					$print_buffer .= $dbiracenames[$spell["effect_base_value$n"]];
				$print_buffer .= "</td>";
                break;
            case 63: // Memblur
            case 120: // Set Healing Effectiveness
            case 330: // Critical Damage Mob
				$print_buffer .= "<td>";
					$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
					$print_buffer .= " ($max%)";
				$print_buffer .= "</td>";
                break;
            case 81: // Resurrect
				$print_buffer .= "<td>";
					$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
					$print_buffer .= " and restore " . $spell["effect_base_value$n"] . "% experience";
				$print_buffer .= "</td>";
                break;
            case 83: // Teleport
            case 88: // Evacuate
            case 145: // Teleport v2
				$print_buffer .= "<td>";
					$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
					if ($csv == false) {
						$print_buffer .= " <a href=?a=zone&name=" . $spell["teleport_zone"] . ">" . $spell["teleport_zone"] . "</a>";
					} else {
						$print_buffer .= " : " . $spell["teleport_zone"];
					}
				$print_buffer .= "</td>";
                break;
            case 85: // Add Proc:
            case 289: // Improved Spell Effect:
            case 323: // Add Defensive Proc:
				$print_buffer .= "<td>";
					$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
					$name = get_field_result(
						"name",
						"SELECT name FROM $spells_table WHERE id=" . $spell["effect_base_value$n"]
					);
					if ($csv == false) {
						$print_buffer .= "<a href=?a=spell&id=" . $spell["effect_base_value$n"] . ">$name</a>";
					} else {
						$print_buffer .= ": $name";
					}
				$print_buffer .= "</td>";
                break;
            case 89: // Increase Player Size
				$print_buffer .= "<td>";
					$name = $dbspelleffects[$spell["effectid$n"]];
					$min -= 100;
					$max -= 100;
					if ($max < 0) {
						$name = str_replace("Increase", "Decrease", $name);
					}
					$print_buffer .= $name;
					if ($min != $max) {
						$print_buffer .= " by $min% (Level $minlvl) to $max% (Level $maxlvl)";
					} else {
						$print_buffer .= " by $max%";
					}
				$print_buffer .= "</td>";
                break;
            case 27: // Cancel Magic
            case 134: // Limit: Max Level
            case 157: // Spell-Damage Shield
				$print_buffer .= "<td>";
					$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
					$print_buffer .= " ($max)";
				$print_buffer .= "</td>";
                break;
            case 121: // Reverse Damage Shield
				$print_buffer .= "<td>";
					$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
					$print_buffer .= " (-$max)";
				$print_buffer .= "</td>";
                break;
            case 91: // Summon Corpse
				$print_buffer .= "<td>";
					$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
					$print_buffer .= " (max level $max)";
				$print_buffer .= "</td>";
                break;
            case 136: // Limit: Target
				$print_buffer .= "<td>";
					$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
					if ($max < 0) {
						$max = -$max;
						$v = " excluded";
					} else {
						$v = "";
					}
					$print_buffer .= " (" . $dbspelltargets[$max] . "$v)";
				$print_buffer .= "</td>";
                break;
            case 139: // Limit: Spell
				$print_buffer .= "<td>";
					$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
					$max          = $spell["effect_base_value$n"];
					if ($max < 0) {
						$max = -$max;
						$v = " excluded";
					}
					$name = get_field_result("name", "SELECT name FROM $spells_table WHERE id=$max");
					if ($csv == false) {
						$print_buffer .= "($name)";
					} else {
						$print_buffer .= " (<a href=?a=spell&id=" . $spell["effect_base_value$n"] . ">$name</a>$v)";
					}
				$print_buffer .= "</td>";
                break;
            case 140: // Limit: Min Duration
				$print_buffer .= "<td>";
					$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
					$min *= 6;
					$max *= 6;
					if ($min != $max) {
						$print_buffer .= " ($min Second(s) (Level $minlvl) to $max Second(s) (Level $maxlvl))";
					} else {
						$print_buffer .= " ($max Second(s))";
					}
				$print_buffer .= "</td>";
                break;
            case 143: // Limit: Min Casting Time
				$print_buffer .= "<td>";
					$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
					$min          *= 6;
					$max          *= 6;
					if ($min != $max) {
						$print_buffer .= " (" . ($min / 6000) . " Second(s) (Level $minlvl) to " . ($max / 6000) . " Second(s) (Level $maxlvl))";
					} else {
						$print_buffer .= " (" . ($max / 6000) . " Second(s))";
					}
				$print_buffer .= "</td>";
                break;
            case 148: // Stacking: Overwrite existing spell
				$print_buffer .= "<td>";
					$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
					$print_buffer .= " if slot " . ($spell["effectid$n"] - 200) . " is effect '" . $dbspelleffects[$spell["effect_base_value$n"]] . "' and <" . $spell["effect_limit_value$n"];
				$print_buffer .= "</td>";
                break;
            case 149: // Stacking: Overwrite existing spell
				$print_buffer .= "<td>";
					$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
					$print_buffer .= " if slot " . ($spell["effectid$n"] - 200) . " is effect '" . $dbspelleffects[$spell["effect_base_value$n"]] . "' and <" . $spell["effect_limit_value$n"];
				$print_buffer .= "</td>";
                break;
            case 147: // Increase Hitpoints (%)
				$print_buffer .= "<td>";
					$name = $dbspelleffects[$spell["effectid$n"]];
					if ($max < 0) {
						$name = str_replace("Increase", "Decrease", $name);
					}
					$print_buffer .= $name . " by " . $spell["effect_limit_value$n"] . " ($max% Max)";
				$print_buffer .= "</td>";
                break;
            case 153: // Balance Party Health
				$print_buffer .= "<td>";
					$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
					$print_buffer .= " ($max% Penalty)";
				$print_buffer .= "</td>";
                break;
            case 0: // In/Decrease hitpoints
            case 1: // Increase AC
            case 2: // Increase ATK
            case 4: // Increase STR
            case 5: // Increase DEX
            case 6: // Increase AGI
            case 7: // Increase STA
            case 8: // Increase INT
            case 9: // Increase WIS
            case 19: // Increase Faction
            case 35: // Increase Disease Counter
            case 36: // Increase Poison Counter
            case 46: // Increase Magic Fire
            case 47: // Increase Magic Cold
            case 48: // Increase Magic Poison
            case 49: // Increase Magic Disease
            case 50: // Increase Magic Resist
            case 55: // Increase Absorb Damage
            case 59: // Increase Damage Shield
            case 69: // Increase Max Hitpoints
            case 78: // Increase Absorb Magic Damage
            case 79: // Increase HP when cast
            case 92: // Increase hate
            case 97: // Increase Mana Pool
            case 111: // Increase All Resists
            case 112: // Increase Effective Casting
            case 116: // Decrease Curse Counter
            case 118: // Increase Singing Skill
            case 159: // Decrease Stats
            case 167: // Pet Power Increase
            case 192: // Increase hate
            default:
				$print_buffer .= "<td>";
					$name = $dbspelleffects[$spell["effectid$n"]];
					if ($max < 0) {
						$name = str_replace("Increase", "Decrease", $name);
					}
					$print_buffer .= $name;
					if ($min != $max) {
						$print_buffer .= " by $min (Level $minlvl) to $max (Level $maxlvl)";
					} else {
						if ($max < 0) {
							$max = -$max;
						}
						$print_buffer .= " by $max";
					}
				$print_buffer .= "</td>";
                break;
        }
		$print_buffer .= "</td></tr>";
    }

    return $print_buffer;
}
