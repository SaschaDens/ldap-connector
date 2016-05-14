# Ldap-connector
[![Build Status](https://travis-ci.org/SaschaDens/ldap-connector.svg?branch=master)](https://travis-ci.org/SaschaDens/ldap-connector)
[![Latest Stable Version](https://poser.pugx.org/dsdevbe/ldap-connector/v/stable)](https://packagist.org/packages/dsdevbe/ldap-connector)
[![Total Downloads](https://poser.pugx.org/dsdevbe/ldap-connector/downloads)](https://packagist.org/packages/dsdevbe/ldap-connector)
[![License](https://poser.pugx.org/dsdevbe/ldap-connector/license)](https://packagist.org/packages/dsdevbe/ldap-connector)

Provides an solution for authentication users with LDAP for Laravel 5.x. It uses ADLDAP library on [Adldap2](https://github.com/Adldap2/Adldap2) to create a bridge between Laravel and LDAP

## Installation
- [Laravel 5.1 - 5.0](#laravel-51---50)
- [Laravel 5.2 - ...](#laravel-52---)

## Laravel 5.1 - 5.0
1. Install this package through Composer by adding the following line to `composer.json`
    ```js
        "dsdevbe/ldap-connector": "3.0.*"
    ```
    
    or you could use command-line
     ```js
    composer require "dsdevbe/ldap-connector:3.0.*"
    ```
    
1. Add the service provider in the app configuration by opening `config/app.php`, and add a new item to the providers array.
       
    ```
    Dsdevbe\LdapConnector\LdapConnectorServiceProvider::class
    ```
    
1. Change the authentication driver in the Laravel config to use the ldap driver. You can find this in the following file `config/auth.php`

    ```php
    'driver' => 'ldap',
    ```
1. Publish a new configuration file with `php artisan vendor:publish` in the configuration folder of Laravel you will find `config/ldap.php` and modify to your needs. For more detail of the configuration you can always check on [ADLAP documentation](http://adldap.sourceforge.net/wiki/doku.php?id=documentation_configuration)
    
    ```
    return array(
        'plugins' => array(
            'adldap' => array(
                'account_suffix'=>  '@domain.local',
                'domain_controllers'=>  array(
                    '192.168.0.1',
                    'dc02.domain.local'
                ), // Load balancing domain controllers
                'base_dn'   =>  'DC=domain,DC=local',
                'admin_username' => 'admin', // This is required for session persistance in the application
                'admin_password' => 'yourPassword',
            ),
        ),
    );
    ```
    
    Please note that the fields 'admin_username' and 'admin_password' are required for session persistance!
    
### Usage 
The LDAP plugin is an extension of the Auth class and will act the same as normal usage with Eloquent driver.
    
```php
if (Auth::attempt(array('username' => $username, 'password' => $password)))
{
    return Redirect::intended('dashboard');
}
```
You can find more examples on [Laravel Auth Documentation](http://laravel.com/docs/master/authentication) on using the `Auth::` function.

### Use AuthController
If you want to use the authentication controller that ships with Laravel you will need to change the following files.
By default `App\Http\Controllers\Auth\AuthController` checks for the `email` field if nothing is provided. To overwrite this value add the following line in the `AuthController`.

```php
protected $username = 'username';
```

Laravel documentation: [Authentication Quickstart](http://laravel.com/docs/master/authentication#authentication-quickstart)

### Ldap Groups
- `Auth::user()->getGroups()` returns `array` with groups the current user belongs to. 
- `Auth::user()->inGroup('GROUPNAME')` returns `boolean` if user belongs to `GROUPNAME`

### Ldap User Information
- `Auth::user()->getUsername()` returns authenticated username.
- `Auth::user()->getFirstname()` returns authenticated first name.
- `Auth::user()->getLastname()` returns authenticated last name.
- `Auth::user()->getEmail()` returns authenticated email address.

## Laravel 5.2 - ...
1. Install this package through Composer by adding the following line to `composer.json`
    ```js
    "dsdevbe/ldap-connector": "4.0.*"
    ```

    or you could use command-line 
    ```js
    composer require "dsdevbe/ldap-connector:4.0.*"
    ```

1. Add the service provider in the app configuration by opening `config/app.php`, and add a new item to the providers array.
       
    ```
    Dsdevbe\LdapConnector\LdapConnectorServiceProvider::class
    ```
    
1. Change the authentication driver in the Laravel config to use the ldap driver. You can find this in the following file `config/auth.php`

    ```php
    'providers' => [
        'users' => [
            'driver' => 'ldap',
            'adldap' => [
                'account_suffix'=>  '@domain.local',
                'domain_controllers'=>  array(
                    '192.168.0.1',
                    'dc02.domain.local'
                ), // Load balancing domain controllers
                'base_dn'   =>  'DC=domain,DC=local',
                'admin_username' => 'admin', // This is required for session persistance in the application
                'admin_password' => 'yourPassword',
            ],
        ],
    ],
    ```
    Please note that the fields 'admin_username' and 'admin_password' are required for session persistance!
    
### Usage 
The LDAP plugin is an extension of the Auth class and will act the same as normal usage with Eloquent driver.
    
```php
if (Auth::attempt(array('username' => $username, 'password' => $password)))
{
    return Redirect::intended('dashboard');
}
```
You can find more examples on [Laravel Auth Documentation](http://laravel.com/docs/master/authentication) on using the `Auth::` function.

### Use AuthController
If you want to use the authentication controller that ships with Laravel you will need to change the following files.
By default `App\Http\Controllers\Auth\AuthController` checks for the `email` field if nothing is provided. To overwrite this value add the following line in the `AuthController`.

```php
protected $username = 'username';
```

Laravel documentation: [Authentication Quickstart](http://laravel.com/docs/master/authentication#authentication-quickstart)

### Ldap User Information
Difference with ldap-connector V3 is that now the adLDAP model is directly exposed on the user model. This means that you can fetch all data directly from the user.
To access the adldap model you can use now `Auth::user()->getAdLDAP()`.

Examples:
- `Auth::user()->getAdLDAP()->getAccountName()`
- `Auth::user()->getAdLDAP()->getFirstName()`

To fetch more properties please check [adLDAP2 documentation](https://github.com/Adldap2/Adldap2/blob/v5.2/docs/models/USER.md)

## Contributing
Feel free to contribute to this project for new features or bug fixes. We are open for improvements!
