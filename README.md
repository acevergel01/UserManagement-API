# Setting Up

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/8.x/installation)

Clone the repository

    git clone --single-branch --branch master-0903000000001087-ace https://gitlab.com/pixel8is/training-ground/ojt-2021-backend.git .

Switch to the project folder

    cd api

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**).
Warning: This will drop all the tables in the database and will create new tables with admin credentials

    php artisan migrate:fresh --seed

Start the local development server

    php artisan serve

Default admin credentials
email:

    admin@gmail.com

password:

    123456

You can now access the server at http://localhost:8000

# API

## Endpoints

### Login

**POST /api/login**
```
params : {
email : '',
password :''
}
```
Returns message status and user token

### Signup
**POST /api/register**
```
params : {
username : '' ,
email : '' ,
password : '' ,
password_confirmation: '',
birthday : ''
}
```

### Authenticated Endpoints
```
headers : {
Accept : Application/json
Authentication : Bearer $token
}
```
### User

**GET /api/user**

Returns logged user information

**POST /api/user**
```
params: {
username : '' ,
email : '' ,
password : '' ,
password_confirmation: '',
birthday : ''
}
```
Adds new user

**PUT /api/user/:id**
```
params: {
email : '' ,
birthday : ''
}
```
Modifies existing user information

**PUT /api/user/:id**
```
params: {
password : ''
}
```
Modifies existing user password

**DELETE /api/user/:id**

Deletes user

**GET /api/userinfo**

Admin: returns all user information

Normal user: return all normal users information

### User Management

**GET /api/permission**

Returns all user permissions

**POST /api/permssion**
```
params: {
'user_id' : '',
'admin' : 'boolean',
'um_access' : 'boolean',
'um_modify' : 'boolean',
'ui_access' : 'boolean',
'ui_add' : 'boolean',
'ui_update' : 'boolean',
'ui_delete' : 'boolean',
'todo_access' : 'boolean',
'todo_add' : 'boolean',
'todo_update' : 'boolean',
'todo_delete' : 'boolean',
}
```
Adds new user permission

**PUT /api/permissions/:id**
```
params: {
'admin' : 'boolean',
'um_access' : 'boolean',
'um_modify' : 'boolean',
'ui_access' : 'boolean',
'ui_add' : 'boolean',
'ui_update' : 'boolean',
'ui_delete' : 'boolean',
'todo_access' : 'boolean',
'todo_add' : 'boolean',
'todo_update' : 'boolean',
'todo_delete' : 'boolean',
}
```
Modifies existing user permission