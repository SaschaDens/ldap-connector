<?php namespace Dsdevbe\LdapConnector;

use adLDAP\adLDAP;
use adLDAP\adLDAPException;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\UserProviderInterface;

class LdapUserProvider implements UserProviderInterface{

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
     * @return \Illuminate\Auth\UserInterface|null
     */
    public function retrieveById($identifier)
    {
        $info = $this->adldap->user()->infoCollection($identifier);
        if($info)
        {
            return new LdapUser($this->fetchObject($info));
        }
    }

    /**
     * Retrieve a user by by their unique identifier and "remember me" token.
     *
     * @param  mixed $identifier
     * @param  string $token
     * @return \Illuminate\Auth\UserInterface|null
     */
    public function retrieveByToken($identifier, $token)
    {
        // TODO: Implement retrieveByToken() method.
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Auth\UserInterface $user
     * @param  string $token
     * @return void
     */
    public function updateRememberToken(UserInterface $user, $token)
    {
        // TODO: Implement updateRememberToken() method.
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array $credentials
     * @return \Illuminate\Auth\UserInterface|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if($this->adldap->user()->info($credentials['username']))
        {
            return new LdapUser($credentials);
        }
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Auth\UserInterface $user
     * @param  array $credentials
     * @return bool
     */
    public function validateCredentials(UserInterface $user, array $credentials)
    {
        $username = $credentials['username'];
        $password = $credentials['password'];

        return $this->adldap->authenticate($username, $password);
    }

    /**
     * Converts infocollection object to array.
     *
     * @param $object
     * @return array
     */
    public function fetchObject($object)
    {
        $arr = array(
            'username'      =>  $object->samaccountname,
            'displayname'   =>  $object->displayname,
            'email'         =>  $object->mail,
            'memberof'      =>  $object->memberof
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
            throw new \Exception($e->getMessage());
        }
    }
}