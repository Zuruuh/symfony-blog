phpstan_bin := "vendor/bin/phpstan analyse --configuration ./phpstan.dist.neon"
symfony_bin := "bin/console"

symfony_cache:
    {{symfony_bin}} --env=dev cache:warmup

phpstan: symfony_cache
	{{phpstan_bin}}
