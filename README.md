# How to run application

```bash
# run docker composer services
docker-compose up -d
# exec php container
docker-compose exec php bash
# install dependencies inside `php` container
composer install
# run console inside `php` container 
bin/console
```

# How to bind ports

```bash
cp docker-compose.override.example.yml docker-compose.override.yml
```

RabbitMQ Management UI (user: guest, password: guest): http://localhost:15672/

# How to change user ID in php docker container

There are a few options

- add ENVs in `.env`
```bash
DOCKER_COMPOSE__USER_ID=1000
DOCKER_COMPOSE__GROUP_ID=1000
```
- export ENVs
```bash
export DOCKER_COMPOSE__USER_ID=1000
export DOCKER_COMPOSE__GROUP_ID=1000
```
