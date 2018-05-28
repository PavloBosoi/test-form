Test task.
Laravel, Vue, Bootstrap used.
For Geo IP detection package https://github.com/Torann/laravel-geoip was used.
For Weather data package https://github.com/nWidart/Laravel-forecast was used.

To run this properly:
- install PHP dependencies - composer install
- install JS dependencies - npm install
- configure .env file (DB config, etc.)
- apply migrations

About solution:
- simple task with one Vue component, one controller and one model
- All fields are mandatory
- valid phone should have 6 or 7 digits
- empty entries are eliminated on submit stage