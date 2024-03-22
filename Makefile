.ONESHELL:
SHELL := /bin/bash

DOCKER_RUN_PHP = docker compose -f .docker/docker-compose.yml -f .docker/docker-compose.override.yml run --rm php "bash" "-c"
DOCKER_COMPOSE = docker compose -f .docker/docker-compose.yml -f .docker/docker-compose.override.yml


start: upd doctrine/migrations assets/install #[Global] Start application (but not consumers, use supervisorctl/start)

src/vendor: #[Composer] install dependencies
	$(DOCKER_RUN_PHP) "composer install --no-interaction"

upd: #[Docker] Start containers detached
	touch .docker/.env
	make src/vendor
	$(DOCKER_COMPOSE) up --remove-orphans --detach

up: #[Docker] Start containers
	touch .docker/.env
	make src/vendor
	$(DOCKER_COMPOSE) up --remove-orphans
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

supervisorctl/start: #[Supervisor] Start consumers (see .docker/php/supervisor/process_ui.conf)
	$(DOCKER_COMPOSE) exec -u root:root php supervisorctl start all

supervisorctl/stop: #[Supervisor] Stop consumers (see .docker/php/supervisor/process_ui.conf)
	$(DOCKER_COMPOSE) exec -u root:root php supervisorctl stop all

supervisorctl/status: #[Supervisor] Show consumers status (see .docker/php/supervisor/process_ui.conf)
	$(DOCKER_COMPOSE) exec -u root:root php supervisorctl status

logs: #[Docker] Show logs
	$(DOCKER_COMPOSE) logs -f

doctrine/migrations: #[Symfony] Run database migration
	$(DOCKER_RUN_PHP) "bin/console do:mi:mi --no-interaction"

assets/install: #[Symfony] Install assets
	$(DOCKER_RUN_PHP) "bin/console assets:install"

cache/clean: #[Symfony] Clean cache
	$(DOCKER_RUN_PHP) "bin/console c:c"

xdebug/on: #[Docker] Enable xdebug
	$(DOCKER_COMPOSE) stop php
	XDEBUG_MODE=debug,develop $(DOCKER_COMPOSE) up --remove-orphans --detach

xdebug/off: #[Docker] Disable xdebug
	$(DOCKER_COMPOSE) stop php
	XDEBUG_MODE=off $(DOCKER_COMPOSE) up --remove-orphans --detach