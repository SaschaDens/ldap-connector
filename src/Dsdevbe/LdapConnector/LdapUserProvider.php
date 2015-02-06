<?php namespace Dsdevbe\LdapConnector;

use Exception;
use adLDAP\adLDAP;
use adLDAP\collections\adLDAPUserCollection;
use adLDAP\adLDAPException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as UserProviderInterface;

class LdapUserProvider implements UserProviderInterface {

    /**
     * Configuration to connect to LDAP.
     *
     * @var string
     */
    protected $config;

    /**
     * Stores connection to LDAP.
     *
     * @var adLDAP
     */
    protected $adldap;

    /**
     * Creates a new LdapUserProvider and connect to Ldap
     *
     * @param array $config
     * @return void
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->connectLdap();
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed $identifier
     * @return Authenticatable
     */
    public function retrieveById($identifier)
    {
        $info = $this->adldap->user()->infoCollection($identifier);
        if($info)
        {
            return new LdapUser($this->mapCollectionToArray($info));
        }
    }

    /**
     * Retrieve a user by by their unique identifier and "remember me" token.
     *
     * @param  mixed $identifier
     * @param  string $token
     * @return Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        // TODO: Implement retrieveByToken() method.
    }

    /**
     * @param Authenticatable $user
     * @param string $token
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        // TODO: Implement updateRememberToken() method.
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array $credentials
     * @return Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if($this->adldap->user()->info($credentials['username']))
        {
            return new LdapUser($credentials);
        }
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $username = $credentials['username'];
        $password = $credentials['password'];

        return $this->adldap->authenticate($username, $password);
    }

    /**
     * @param adLDAPUserCollection $collection
     * @return array
     */
    public function mapCollectionToArray(adLDAPUserCollection $collection)
    {
        $arr = array(
            'username'      =>  $collection->samaccountname,
            'displayname'   =>  $collection->displayname,
            'email'         =>  $collection->mail,
            'memberof'      =>  $collection->memberof
        );

        return $arr;
    }

    /**
     * Connect to LDAP
     *
     * @throws \Exception
     */
    public function connectLdap()
    {
        try
        {
            $this->adldap = new adLDAP($this->config);
        } catch(adLDAPException $e)
        {
            throw new Exception($e->getMessage());
        }
    }

}