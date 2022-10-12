# How to use this api

this is a test restful api with basttyyreactphp-orm , it incorporates a test rest Api with promise chaining


 ## setup database
 setup your mysql to have a database called react-database to have a table callsed users with three columns (id int not null auto_increment primary key,name varchar not null,email varchar not null), also insert some values 


 ## install dependencies 
 ```php 
 composer install
 ```

## ENDPOINTS
### get all users
localhost:8080/users

### get single users
localhost:8080/users/{id}

### add users-post
localhost:8080/users

### delete user -delete
localhost:8080/users/{id}