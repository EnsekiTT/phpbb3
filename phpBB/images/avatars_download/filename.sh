#!/bin/sh
[ -f $1 ] || exit 1


while read line
do
    a=($line)
    PARA1=`echo ${a[0]}`
    PARA2=`echo ${a[1]} | sed -e "s/.*\.\([^.]*\)\$/\1/g"`
    echo ${a[1]}
    echo "${PARA1}.${PARA2}"
done < $1
