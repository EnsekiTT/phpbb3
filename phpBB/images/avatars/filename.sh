#!/bin/sh
[ -f $1 ] || exit 1


while read line
do
    a=($line)
    PARA1=`echo ${a[0]}`
    PARA2=`echo ${a[1]} | sed -e "s/.*\.\([^.]*\)\$/\1/g"`
    #サーバかドメインか何かしらのハッシュ値　全てのファイル共通っぽい
    PARA3="3c78571d28212a6ade8f1c77e0f12fbc_"
    mv ${a[1]} ${PARA3}${PARA1}.${PARA2}
done < $1
