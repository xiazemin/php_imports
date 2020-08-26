#!/bin/bash
cat $1 |grep -v 'use '|grep -oE "[a-zA-Z_\\]*\\\\+[a-zA-z_\\]*" |uniq |sort
#cat $1 |grep 'use ' |grep -vE "[a-zA-z]+use"  |awk '{print $2}' |uniq |sort
#cat $1 |grep -oE "[a-zA-Z_\\]*::" |awk -F':' '{print $1}' |uniq |sort
