<?php namespace Dsdevbe\LdapConnector;

use Illuminate\Auth\Guard;
use Illuminate\Support\ServiceProvider;
use LdapManager;
use Exception;

class LdapConnectorServiceProvider extends ServiceProvider {

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
		$this->package('dsdevbe/ldap-connector');

        \Auth::extend('ldap', function($app) {
            $provider = new LdapUserProvider($this->getConfig());
            return new Guard($provider, $app['session.store']);
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
		return array('auth');
	}

    /**
     * Get ldap configuration
     *
     * @return array
     */
    public function getConfig()
    {
        if(!$this->app['config']['ldap']) throw new Exception('LDAP config not found. Check if app/config/ldap.php exists.');

        return $this->app['config']['ldap'];
    }
}
