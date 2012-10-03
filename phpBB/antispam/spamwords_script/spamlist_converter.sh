#!/bin/sh
[ -f $1 ] || exit 1

#INSERT INTO `moz-forum`.`phpbb_spam_words` (`word_id`, `word_text`, `word_regex`, `word_regex_auto`, `word_white_pattern`, `word_type`) VALUES を追加すること

while read line
do
    a=($line)
    PARA1=`echo ${a[0]}`
    PARA2=`echo ${a[1]}`
    PARA3=`echo ${a[2]}`
    PARA4=`echo ${a[3]}`

    #サーバかドメインか何かしらのハッシュ値　全てのファイル共通っぽい
    if test ${PARA2} != "NULL" ; then
        echo "(NULL, '"${PARA2}"', '0', '0', '0', '1')," 
    fi
    if test ${PARA3} != "NULL" ; then
        echo "(NULL, '"${PARA3}"', '0', '0', '0', '2'),"
    fi
    if test ${PARA4} != "NULL" ; then
        echo "(NULL, '"${PARA4}"', '0', '0', '0', '3'),"
    fi
done < $1
