# ValueSERP Search Integration - Laravel App

This Laravel project allows users to search Google using the ValueSERP API, view search results, and export them to a CSV file.

---

## Features

-  Google Search via ValueSERP API
-  Display Organic Search Results
-  Pagination Support
-  Export Results to CSV
-  Related Searches & Related Questions


---

##  Technologies Used

- Laravel 10+
- Bootstrap 5
- ValueSERP API
- Blade Templates
- HTTP Client (Laravel `Http` Facade)

---

##  Setup Instructions
1. **Clone the repository**
```bash
https://github.com/Sakibansari12/value-serp.git
cd value-serp
composer install

Copy .env and configure
VALUESERP_KEY=9C6F1EBAE04D4CCDB9139A2855E4EF60
php artisan serve
