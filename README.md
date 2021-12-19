# AiChat Test
API to be consume by front development to give away voucher based on selected active campaign.

# Disclaimer
- Build on PHP v. 7.2 
- Laravel v. 7.29

# Installation for local development environment

1. Open Terminal / CMD / Git Bash
2. Clone this repository to your local development environemnt
3. Open project folder root
4. type 'composer install'
5. copy file '.env.example' to '.env' and setting the configuration 
    - APP_TIMEZONE: your timezone
    - DB_DATABASE: your database name
    - DB_USERNAME: your database username
    - DB_PASSWORD: your database password
6. type 'php artisan migrate:fresh --seed' on Terminal / CMD / Git Bash on project folder root
7. type 'php artisan serve' on Terminal / CMD / Git Bash on project folder root to start server

# API Documentation using Postman
Link: https://documenter.getpostman.com/view/3619632/UVRAJ6yP#04a57bab-77cb-40cc-a827-37470de1b0d9
File: AiChat-test.postman_collection.json

## Using file
1. you must install Postman first
2. import file 'AiChat-test.postman_collection.json' to your postman collection
3. change 'base_url' variable on your setting Variables Collection (default: http://localhost:8000/api)

## Steps to execute
### API Customer Eligible Check
1. open request 'customer/check-eligible-campaign-voucher'
2. input request parameter
    - customer_email: 
    test@aichat.id & customer email with id = 2 (default) is eligible, you can check another customer on table in database to check eligible or not
    - campaign_slug: campaign-a (don't change, because this is from seeder)
3. Send request, condition:
    - if campaign not accessable, it will return response with message "Campaign Not Found"
    - if customer does not have voucher active (locked down or qualified after upload photo), the request will make locked down voucher for selected customer
    - if customer does have voucher active (locked down or qualified after upload photo), it will return the voucher data (locked down = expired time, qualified = code)
- note:
    - You can change field 'end_at' in table 'm_campaign' in database to check is campaign is active or not


### API Validation Photo Submission
1. open request 'customer/validate-photo-submission'
2. input request parameter
    - customer_email: eligible customer email
    - campaign_slug: campaign-a (don't change, because this is from seeder)
3. Send request, condition:
    - if customer send request without check eligble , it will return response with message "Voucher Not Acquired"
    - if customer send request exceeds 10 minutes, it will return response with message "Voucher Not Acquired"
    - if customer have voucher active or has send this request , it will return the voucher code


# Packages & Library

All packages that used comes from the laravel framework, such as:
    - fideloper/proxy
    - fruitcake/laravel-cors
    - guzzlehttp/guzzle
    - etc
list packages can be seen on file 'composer.json'