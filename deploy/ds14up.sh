#!/bin/sh

rm dep.log
bundle exec cap ds14-shard01 deploy branch=production --trace >> dep.log &
bundle exec cap ds14-shard02 deploy branch=production --trace >> dep.log &
bundle exec cap ds14-shard03 deploy branch=production --trace >> dep.log &
bundle exec cap ds14-shard04 deploy branch=production --trace >> dep.log &
bundle exec cap ds14-shard05 deploy branch=production --trace >> dep.log &
bundle exec cap ds14-shard06 deploy branch=production --trace >> dep.log &
bundle exec cap ds14-shard07 deploy branch=production --trace >> dep.log &
bundle exec cap ds14-shard08 deploy branch=production --trace >> dep.log &
bundle exec cap ds14-shard09 deploy branch=production --trace >> dep.log &
bundle exec cap ds14-shard10 deploy branch=production --trace >> dep.log &
bundle exec cap ds14-shard11 deploy branch=production --trace >> dep.log &
bundle exec cap ds14-vs04 deploy branch=production --trace >> dep.log