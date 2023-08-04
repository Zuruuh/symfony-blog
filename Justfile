set shell := ["bash", "-uc"]

symfony_bin := "symfony"
docker_bin := "docker"
compose_file := "compose.dev.yaml"
docker_compose_bin := docker_bin + " compose -f " + compose_file

phpstan_config := "tools/phpstan.dist.neon"
deptrac_config := "tools/deptrac.yaml"
psalm_config := "tools/psalm.dist.xml"
php_cs_fixer_config := "tools/.php-cs-fixer.dist.php"

phpstan_bin := "tools/vendor/bin/phpstan"
deptrac_bin := "tools/vendor/bin/deptrac"
psalm_bin := "tools/vendor/bin/psalm"
php_cs_fixer_bin := "tools/vendor/bin/php-cs-fixer"

cache:
    mkdir .cache 2> /dev/null || exit 0

install: cache
    {{symfony_bin}} composer install

stop:
    {{docker_compose_bin}} down
    {{symfony_bin}} server:stop

start: install stop
    {{docker_compose_bin}} up -d
    {{symfony_bin}} server:start --no-tls -d --dir src/Infrastructure/Symfony

log service="server":
    if [[ {{service}} = "server" ]]; then {{symfony_bin}} server:log; else {{docker_compose_bin}} logs {{service}} -fn 30; fi

symfony_cache: install
    {{symfony_bin}} console --env=dev cache:warmup

## Tooling

phpstan: symfony_cache
	{{phpstan_bin}} analyse --configuration {{phpstan_config}}

deptrac: install
    {{deptrac_bin}} analyse --config-file {{deptrac_config}} --cache-file .cache/deptrac.cache

psalm: symfony_cache
    {{psalm_bin}} --config {{psalm_config}}

lint: install
    {{php_cs_fixer_bin}} fix --dry-run --allow-risky yes --diff --config {{php_cs_fixer_config}}

fix: install
    {{php_cs_fixer_bin}} fix --allow-risky yes --diff --config {{php_cs_fixer_config}}

static_analysis: phpstan psalm deptrac
