<?php

namespace Dsdevbe\LdapConnector;

use Auth;
use Dsdevbe\LdapConnector\Adapter\Adldap;
use Dsdevbe\LdapConnector\Exception\MissingConfiguration;
use Illuminate\Support\ServiceProvider;

class LdapConnectorServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        Auth::provider('ldap', function ($app, array $config) {
            if (!$this->hasLdapConfiguration($config)) {
                throw new MissingConfiguration('Please check if your configuration is available in config/auth.php');
            }
            $ldap = new Adldap($app['hash'], $config['adldap']);

            return new LdapUserProvider($ldap);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['auth'];
    }

    /**
     * @param $config
     *
     * @return bool
     */
    protected function hasLdapConfiguration($config)
    {
        return isset($config['adldap']) && is_array($config['adldap']);
    }
}
