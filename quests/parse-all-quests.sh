#!/bin/bash

QuestsDir="/usr/local/emu/bin/quests"


if test $# -ne 0
then
  echo "usage: $0" 1>&2
  echo "  Parses all quests in $QuestsDir" 1>&2
  exit 1
fi


( for QuestFile in `find $QuestsDir -name "*.pl"`
  do
    ZoneDir=`dirname $QuestFile`
    echo "`basename $ZoneDir` `basename $QuestFile`"
    ./parse_quest.pl `basename $ZoneDir` `basename $QuestFile`
  done
) 2>&1 | tee parse-all-quests.log
