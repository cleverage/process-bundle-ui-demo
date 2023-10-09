.ONESHELL:
SHELL := /bin/bash

DOCKER_RUN_PHP = docker compose -f .docker/docker-compose.yml -f .docker/docker-compose.override.yml run --rm php "bash" "-c"
DOCKER_COMPOSE = docker compose -f .docker/docker-compose.yml -f .docker/docker-compose.override.yml


start: up src/vendor doctrine/migrations assets/install server/start messenger/consume #[Global] Start application

src/vendor: #[Composer] install dependencies
	$(DOCKER_RUN_PHP) "composer install --no-interaction"

up: #[Docker] Start containers
	$(DOCKER_COMPOSE) up --remove-orphans --detach

stop: #[Docker] Down containers
	$(DOCKER_COMPOSE) stop

down: #[Docker] Down containers
	$(DOCKER_COMPOSE) down

build: #[Docker] Build containers
	$(DOCKER_COMPOSE) build

ps: # [Docker] Show running containers
	$(DOCKER_COMPOSE) ps

bash: #[Docker] Connect to php container with current host user
	$(DOCKER_COMPOSE) exec -u $$(id -u $${USER}):$$(id -g $${USER}) php bash

logs: #[Docker] Show logs
	$(DOCKER_COMPOSE) logs -f

messenger/consume: #[Symfony] Consume messages
	$(DOCKER_RUN_PHP) "bin/console mess:cons"

doctrine/migrations: #[Symfony] Run database migration
	$(DOCKER_RUN_PHP) "bin/console do:mi:mi --no-interaction"

assets/install: #[Symfony] Install assets
	$(DOCKER_RUN_PHP) "bin/console assets:install"

cache/clean: #[Symfony] Clean cache
	$(DOCKER_RUN_PHP) "bin/console c:c"

server/start: #[Symfony CLI] Start Symfony http server
	$(DOCKER_COMPOSE) exec -u $$(id -u $${USER}):$$(id -g $${USER}) php "bash" "-c" "symfony serve -d --no-tls"

server/stop: #[Symfony CLI] Stop Symfony http server
	$(DOCKER_COMPOSE) exec -u $$(id -u $${USER}):$$(id -g $${USER}) php "bash" "-c" "symfony server:stop"

server/restart: server/stop server/start #[Symfony CLI] Restart Symfony http server
