# Agriculture API 



API helps you to insert Field, Tractor and then Generate the Reports

## Installation

Clone the branch to your local server path and then cd to the path you have copied

Run the following command

```
    composer update
```

```
    cp .env.example .env
```

## Edit the .env file and add the following

Database username
Database password
Database host

Add Key for JWT
Add Key for APP_KEY

Then run the following command

## Run migrate
```
    php artisan migrate
```

## Seed the Database
```
    php artisan db:seed
```

```
    php -S localhost:8000 public/index.php

```

Now open your browser and open http://localhost:8000 you will see the landing page




## This API use ACL 

This API has some restriction like the registration is not public, so you have to login as 
admin then only you can add users

Admin User : admin@password.com

Password : password

## API Document

Please open http://localhost:8000/api/documentation there you will get all the API details

## DB Structure

Please find "db.png"


