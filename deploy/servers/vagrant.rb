
# Extended Server Syntax
# ======================
# This can be used to drop a more detailed server definition into the
# server list. The second argument is a, or duck-types, Hash and is
# used to set extended properties on the server.

ip = `grep 'config.vm.network' ../Vagrantfile | grep -v '#' | grep -Po 'ip:\s+\"[0-9\.]+\"' | grep -Po '[0-9\.]+'`

server ip, user: 'vagrant', :ssh_options => { :keys => ['../.vagrant/machines/default/virtualbox/private_key'] },
    roles: %w{app web db shared_db amqp backend}, :cron => true, :webinar_path => '/vagrant_wbr'

# Custom Options
# ==================

set :deploy_to,              '/vagrant'
set :composer_working_dir,   '/vagrant'
set :composer_install_flags, '--no-interaction'
set :symfony_env,            'dev'
set :controllers_to_clear,   []
set :assets_env,             'dev'

load 'recipes/vagrant.rb'
