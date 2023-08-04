set shell := ["bash", "-uc"]

symfony_bin := "symfony"
docker_bin := "docker"
compose_file := "compose.dev.yaml"
docker_compose_bin := docker_bin + " compose -f " + compose_file

phpstan_bin := "vendor/bin/phpstan"
deptrac_bin := "vendor/bin/deptrac"

install:
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
	{{phpstan_bin}} analyse --configuration ./phpstan.dist.neon

deptrac: install
    {{deptrac_bin}} analyse --config-file ./deptrac.yaml