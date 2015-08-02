# Ldap-connector
Provides an solution for authentication users with LDAP for Laravel 5.x. It uses ADLDAP 4.0 library forked on [Adldap2](https://github.com/Adldap2/Adldap2) to create a bridge between Laravel and LDAP

## Installation
1. Install this package through Composer for Laravel v5.x:
    ```js
    composer require dsdevbe/ldap-connector:3.*
    ```
    If you still want to use Ldap-connector for Laravel v4.2 please refer to the following package
    ```js
    composer require dsdevbe/ldap-connector:2.*
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
    
1. Once this is done you arrived at the final step and you will need to add a service provider. Open `config/app.php`, and add a new item to the providers array.
	
	```
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

You can find more examples on [Laravel Auth Documentation](http://laravel.com/docs/master/authentication) on using the `Auth::` function.