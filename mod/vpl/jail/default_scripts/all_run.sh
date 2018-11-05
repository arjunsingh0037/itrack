#!/bin/bash
# This file is part of VPL for Moodle - http://vpl.dis.ulpgc.es/
# Script for running Hello program (in console) of available languages
# Copyright (C) 2015 Juan Carlos Rodríguez-del-Pino
# License http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
# Author Juan Carlos Rodríguez-del-Pino <jcrodriguez@dis.ulpgc.es>

# @vpl_script_description Run a "hello world" program of all programming languages available

cp common_script.sh common_script.sav
cat common_script.sh > all_execute
NG=0
FILES=*_hello.sh
touch .tuierrors
for HELLOSCRIPT in $FILES
do
	typeset -u LANGUAGE=$(echo "$HELLOSCRIPT" | sed -r "s/_hello.sh$//")
	RUNSCRIPT=$(echo "$HELLOSCRIPT" | sed -r "s/_hello.sh$/_run.sh/")
	VPLEXE=$(echo "$HELLOSCRIPT" | sed -r "s/_hello.sh$/_execute.sh/")
	echo -n "$LANGUAGE:"
	rm .curerror &>/dev/null
	. $HELLOSCRIPT &>.curerror
	cp common_script.sav common_script.sh
	echo "export VPL_SUBFILE0=$VPL_SUBFILE0" >> common_script.sh
	echo "export SOURCE_FILES=$VPL_SUBFILE0" >> common_script.sh
	eval ./$RUNSCRIPT batch &>>.curerror
	if [ -f vpl_execution ] ; then
		let "NG=NG+1"
		mv vpl_execution $VPLEXE
		echo " Compiled"
		echo "printf \"%2d %s: \" $NG $LANGUAGE" >> all_execute
		echo "./$VPLEXE" >> all_execute
	else
		if [ -f vpl_wexecution ] ; then
			echo " Use debug button to run graphic Hello World!"
			rm vpl_wexecution
		else
			echo " Hello program not generated"
		fi
	fi
	if [ -s .curerror ] ; then
		echo "- The compilation of $LANGUAGE has generated the folloging menssages:" >> .tuierrors
		cat .curerror >> .tuierrors
	fi
done
mv all_execute vpl_execution
chmod +x vpl_execution
echo
cat .tuierrors
