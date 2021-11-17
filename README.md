<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## Laravel API to fetch data from ClearBit
After clone the project run `composer install`

The API uses sqlite database connection by default so create database.sqlite file into database folder and then run `php artisan migrate` command.

The API uses [Mailtrap](https://mailtrap.io/) for mailing. You can set your own credentials in order to watch the mailing service.

In order to start Jobs worker run `php artisan queue:work` command in order to start processing jobs on the queue as a daemon.

## Endpoint URLs

POST /v1/api/company

Request data for a company that we need information for. Expected body params:
- `company_name` - Name of the company
- `company_domain` - Domain of the company
- `api_key` - Your own Clearbit API KEY. You can test with `sk_af064ceec549c5842671913037c2631d`


POST /v1/api/company/show

Obtain detailed information for the requested company by the domain. Expected body params:
- `company_domain` - Domain of the company


POST /v1/api/company/status

Obtain information for a certain task is not yet scrapped or it is. Expected body params:
- `company_domain` - Domain of the company
