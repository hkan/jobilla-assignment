## Jobilla Assignment

This is a demo application I prepared for Jobilla's software developer position's assignment.

## Installation & Usage

- Clone the repo or download and unzip its zip file to your local environment.
- Run `composer install` to install Laravel's dependencies.
- Run `yarn` to install Vue app's dependencies.
- Run `yarn run dev` to build the app for development environment, or `yarn run prod` to run in production.
- Create an environment file called `.env`. You can copy the `.env.example` as a starting point.
- Enter your database credentials into your environment file.
- Enter the remote API URL into your environment file. Current URL is `https://paikat.te-palvelut.fi/tpt-api/tyopaikat?englanti=true`.
- Run `php artisan migrate` to create the database tables.
- Run `php artisan jobs:fetch-remote` to fetch the data from remote API.
- Run `php artisan serve` to get the backend app up.

## License

The app is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
