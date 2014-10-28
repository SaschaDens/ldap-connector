# Ldap-connector
Provides an solution for authentication users with LDAP for Laravel 4.2.x

## Installation
1. Install this package through Composer:

    ```js
    composer require dsdevbe/ldap-connector:1.*
    ```

1. Change the authentication driver in the Laravel config to use the ldap driver. You can find this in the following file `app/config/auth.php`

    ```php
    'driver' => 'ldap',
    ```
1. Create a new configuration file `ldap.php` in the configuration folder of Laravel `app/config/ldap.php` and modify to your needs.

    ```php
    <?php

    return array(
        'account_suffix'        =>  "@domain.local",
        'domain_controllers'    =>  array("192.168.0.1", "dc02.domain.local"), // Load balancing domain controllers
        'base_dn'               =>  'DC=domain,DC=local',
        'admin_username'        =>  'dummy',    // Just needs to be an valid account to query other users if they exists
        'admin_password'        =>  'password'
    );
    ```
1. Once this is done you arrived at the final step and you will need to add a service provider. Open `app/config/app.php`, and add a new item to the providers array.
	
	```php
	'Dsdevbe\LdapConnector\LdapConnectorServiceProvider'
	```

## Usage
The LDAP plugin is an extension of the AUTH class and will act the same as normal usage with Eloquent driver.
    
    ```php
    if (Auth::attempt(array('username' => $email, 'password' => $password)))
    {
        return Redirect::intended('dashboard');
    }
    ```

You can find more examples on [Laravel Auth Documentation](http://laravel.com/docs/security#authenticating-users) on using the `Auth::` function.