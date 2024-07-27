###############
### READ ME ###
###############

Flow chat is in here 
 - `https://www.figma.com/board/s3xSCN7ZzJ4MXxLckCbZr7/Laravel-Library-Management?node-id=0-1&t=S75kelFppJ8cAhzM-1`

- First run this for building docker container
    `docker-compose up -d --build`

- Run composer install command to install required dependencies
    `docker-compose exec app composer install`

- Run this comment for table migration
    `docker-compose exec app php artisan migrate`

- Run this for Laravel passport client 
    `docker-compose exec app php artisan passport:client --personal`

- Run this for database seeding
    `docker-compose exec app php artisan db:seed`

- Laravel project will run and 
    - main url : `http://localhost:8000`
    - api url  : `http://localhost:8000/api`
    - super admin credential
        - email     : `admin@example.com`
        - password  : `password`

- phpMyAdmin will run at 
    - url : `http://localhost:8080/`
    - credential
        - username : `root`
        - password : `secret`

- import postman collection file for APIs
    - file name: `Laravel Libbrary.postman_collection.json`