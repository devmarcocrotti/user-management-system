# User Management System
## Getting Started
To set up and run locally, make sure you have Git and Docker installed on your system. Clone the repository, then run the following commands within project dir:
#### Start docker
```shell
docker-compose up -d
```
#### Execute migrations
```shell
docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction
```
#### Create a default admin user
```shell
docker-compose exec php bin/console app:create-admin-user
```
## Run the application
Open your browser at http://localhost:8000/ then login with username and password
```
Admin credentials
username: admin
password: admin
```
## Endpoint documentation
- `/login` | Public login route
- `/register` | Public register route
- `/` | Dashboard - show logged user info
- `/logout` | Logout route
- `/admin` | Reserved to admin - Show users list 
- `/admin/profile/{{uuid}` | Reserved to admin - Show user info
- `/admin/profile/{{uuid}/edit` | Reserved to admin - Edit user email
