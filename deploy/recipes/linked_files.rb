# encoding: utf-8

namespace :deploy do
    task :linked_files_resolve do
        on roles(:all) do
            fetch(:linked_files_pattern).each do | pattern |
                within shared_path do
                    set :linked_files,  fetch(:linked_files, []) + capture(:find, pattern, '2>/dev/null', ';', :true).lines().collect { | line | line.rstrip }
                end
            end
        end
    end

    before 'deploy:symlink:shared', 'deploy:linked_files_resolve'
end
