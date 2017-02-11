#!/bin/sh

# Скрипт, чтобы выполнить любую капистрано-команду сразу для всех папок серверов на ds14
# Пример: перезапустить рассылки: ./ds14run.sh mailing:restart 

rm work.log
bundle exec cap ds14-shard01 $1 >> work.log &
bundle exec cap ds14-shard02 $1 >> work.log &
bundle exec cap ds14-shard03 $1 >> work.log &
bundle exec cap ds14-shard04 $1 >> work.log &
bundle exec cap ds14-shard05 $1 >> work.log &
bundle exec cap ds14-shard06 $1 >> work.log &
bundle exec cap ds14-shard07 $1 >> work.log &
bundle exec cap ds14-shard08 $1 >> work.log &
bundle exec cap ds14-shard09 $1 >> work.log &
bundle exec cap ds14-shard10 $1 >> work.log &
bundle exec cap ds14-shard11 $1 >> work.log &
bundle exec cap ds14-vs04 $1 >> work.log