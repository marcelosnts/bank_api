## Bank API

Built on: 
- Laravel 7.0
- PHP 7.2.5
- SQLite

You may import the HAR file **api_doc.har** on project root to your API 

client to take a look at the api endpoints;

# Running the project

Step 1.

```
git clone git@github.com:marcelosnts/bank_api.git

cd .\bank_api

composer install
```

Step 2.
- Rename the **.example** files (Removing the ".exemple"):
  - .env.example
  - database/database.sqlite.example

Step 3.
- It might be needed to generate a APP_KEY:

```
php artisan key:generate
```

Step 4.
- Run the tables migrations:

```
php artisan migrate
```

Step 5.
- Execute the default user seeder:

```
php artisan db:seed --class=UserSeeder
// email: user@email.com
// password: 123123
```

After that the server should be ready to run, so:

```
php artisan serve
// http://localhost:8000
```

With your API client you can login with the default user and the response token 

should be used on the **Authorization** header as a Bearer token:

```
// Authorization Bearer :token
```
