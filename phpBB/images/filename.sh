#!/bin/sh
[ -f $1 ] || exit 1


while read line
do
    a=($line)
    PARA1=`echo ${a[0]}`
    PARA2=`echo ${a[1]} | sed -e "s/.*\.\([^.]*\)\$/\1/g"`
    #サーバかドメインか何かしらのハッシュ値　全てのファイル共通っぽい
    PARA3="a732c32113e71006fd761db98b26f8a3_"
    echo ${a[1]} ${PARA3}${PARA1}.${PARA2}
done < $1
