# User Management System
## Startup application
### Start docker
```shell
docker-compose up -d
```
### Execute migrations
```shell
docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction
```
### Create a default admin user
```shell
docker-compose exec php bin/console app:create-admin-user
```
## Run the application
http://localhost:8000/
