# Костыли для работы с вагрантом

Capistrano::DSL::Paths.module_eval do
      def current_path
        Pathname.new('/vagrant')
      end
      def release_path
        Pathname.new('/vagrant')
      end
      def shared_path
        Pathname.new('/vagrant')
      end
end

[
    "deploy",
    "deploy:check",
    "deploy:check:directories",
    "deploy:check:linked_dirs",
    "deploy:check:linked_files",
    "deploy:check:make_linked_dirs",
    "deploy:cleanup",
    "deploy:cleanup_rollback",
    "deploy:finished",
    "deploy:finishing",
    "deploy:finishing_rollback",
    "deploy:log_revision",
    "deploy:published",
    "deploy:publishing",
    "deploy:revert_release",
    "deploy:reverted",
    "deploy:reverting",
    "deploy:rollback",
    "deploy:set_current_revision",
    "deploy:started",
    "deploy:starting",
    "deploy:symlink:linked_dirs",
    "deploy:symlink:linked_files",
    "deploy:symlink:release",
    "deploy:symlink:shared",
    "deploy:updated",
    "deploy:updating",
    "install",
    "mysql_test:autostart"
].each { | item | Rake::Task[item].clear_actions() }

before :deploy, 'composer:install'
before :deploy, 'amqp:stop'
before :deploy, 'doctrine:migrations:migrate'
before :deploy, 'amqp:configure'
before :deploy, 'amqp:start'
before :deploy, 'crontab:install'
before :deploy, 'amqp:restart_legacy'
before :deploy, 'assets:install'
before :deploy, 'assets:build'
before :deploy, 'php:reload'
before :deploy, 'nginx:reload'
