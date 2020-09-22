# Laravel eWUŚ

This package is Laravel wrapper for [etermed/ewus](https://github.com/etermed/ewus-php) package.

## Requirements

|  Version | Laravel |
| -------- | ------- |
| >= 0.0.1 |  >= 6.0 |

## Installation

```sh
composer require etermed/ewus-laravel
```

Next run artisan command to set current password to eWUŚ:

```sh
php artisan ewus:password --init
```

## Configuration (.env)

### Base

* **EWUS_SANDBOX_MODE** (*default: false*) - disables/enables sandbox mode
* **EWUS_CONNECTION** (*default: http*) - connection used for communication

### Password

* **EWUS_PASSWORD_LENGTH** (*default: 8*) - random generated password length

### Credentials

* **EWUS_CREDENTIALS_DOMAIN** - operator domain
* **EWUS_CREDENTIALS_LOGIN** - operator login
* **EWUS_CREDENTIALS_OPERATOR_ID** (*default: null*) - operator identificator, required only for certain domains
* **EWUS_CREDENTIALS_OPERATOR_TYPE** (*default: null*) - operator type, required only for certain domains

## Scheduling password changes

eWUŚ requires password changes every two weeks. We recommend changing it more frequently to be sure it will not expire. To automate this process you can create schedule which will call artisan command:

```php
// app/Console/Kernel.php

protected function schedule(Schedule $schedule)
{
    // other schedules

    $schedule->command('ewus:password --random')->weeklyOn(1, '00:00');
}
```