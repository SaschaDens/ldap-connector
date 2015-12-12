# Ldap-connector
[![Build Status](https://travis-ci.org/SaschaDens/ldap-connector.svg)](https://travis-ci.org/SaschaDens/ldap-connector)
[![Latest Stable Version](https://poser.pugx.org/dsdevbe/ldap-connector/v/stable)](https://packagist.org/packages/dsdevbe/ldap-connector)
[![Total Downloads](https://poser.pugx.org/dsdevbe/ldap-connector/downloads)](https://packagist.org/packages/dsdevbe/ldap-connector)
[![License](https://poser.pugx.org/dsdevbe/ldap-connector/license)](https://packagist.org/packages/dsdevbe/ldap-connector)

Provides an solution for authentication users with LDAP for Laravel 5.x. It uses ADLDAP 4.0 library forked on [Adldap2](https://github.com/Adldap2/Adldap2) to create a bridge between Laravel and LDAP

## Installation
1. Install this package through Composer for Laravel v5.x:
    ```js
    composer require dsdevbe/ldap-connector:3.*
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
    
## Usage
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
