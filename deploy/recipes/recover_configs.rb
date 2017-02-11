namespace :recover_configs do
    desc 'Перезалить конфиги'
    task :recover do
        on roles(:all), in: :parallel do |server|
            within shared_path do
                host = server.hostname
                parameters = "prod_config/#{fetch(:stage)}/parameters.#{host}.yml"
                contents = File.read(parameters)
                execute :echo, Shellwords.escape(contents.force_encoding('binary')), '>', "#{shared_path}/app/config/parameters.yml"
            end
        end
    end
    task :backup do
        on roles(:all), in: :parallel do |server|
            within shared_path do
                host = server.hostname
                Dir.mkdir("prod_config_backup/#{fetch(:stage)}") unless File.directory?("prod_config_backup/#{fetch(:stage)}")
                parameters = "prod_config_backup/#{fetch(:stage)}/parameters.#{host}.yml"
                content = capture(:cat, "#{shared_path}/app/config/parameters.yml")
                File.write(parameters, content)
            end
        end
    end
end


#bundle exec cap ds14-shard01 recover_configs:backup
#bundle exec cap ds14-shard02 recover_configs:backup
#bundle exec cap ds14-shard03 recover_configs:backup
#bundle exec cap ds14-shard04 recover_configs:backup
#bundle exec cap ds14-shard05 recover_configs:backup
#bundle exec cap ds14-shard06 recover_configs:backup
#bundle exec cap ds14-shard07 recover_configs:backup
#bundle exec cap ds14-shard08 recover_configs:backup
#bundle exec cap ds14-shard09 recover_configs:backup
#bundle exec cap ds14-shard10 recover_configs:backup
#bundle exec cap ds14-shard11 recover_configs:backup
#bundle exec cap ds14-vs04 recover_configs:backup
#bundle exec cap ds14-vs33 recover_configs:backup
#bundle exec cap kostin654.justclick.ru recover_configs:backup
#bundle exec cap production recover_configs:backup
#bundle exec cap vs03 recover_configs:backup

#bundle exec cap ds14-shard01 recover_configs:recover
#bundle exec cap ds14-shard02 recover_configs:recover
#bundle exec cap ds14-shard03 recover_configs:recover
#bundle exec cap ds14-shard04 recover_configs:recover
#bundle exec cap ds14-shard05 recover_configs:recover
#bundle exec cap ds14-shard06 recover_configs:recover
#bundle exec cap ds14-shard07 recover_configs:recover
#bundle exec cap ds14-shard08 recover_configs:recover
#bundle exec cap ds14-shard09 recover_configs:recover
#bundle exec cap ds14-shard10 recover_configs:recover
#bundle exec cap ds14-shard11 recover_configs:recover
#bundle exec cap ds14-vs04 recover_configs:recover
#bundle exec cap ds14-vs33 recover_configs:recover
#bundle exec cap kostin654.justclick.ru recover_configs:recover
#bundle exec cap production recover_configs:recover
#bundle exec cap vs03 recover_configs:recover
