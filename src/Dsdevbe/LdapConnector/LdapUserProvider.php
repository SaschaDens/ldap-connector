<?php

namespace Dsdevbe\LdapConnector;

use Arr;
use Dsdevbe\LdapConnector\Adapter\LdapInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as UserProviderInterface;

class LdapUserProvider implements UserProviderInterface
{
    /**
     * @var LdapInterface;
     */
    protected $_adapter;

    public function __construct(LdapInterface $adapter)
    {
        $this->_adapter = $adapter;
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param mixed $identifier
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        if (is_array($identifier)) {
            $identifier = Arr::get($identifier, 0);
        }

        return $this->_adapter->getUserInfo($identifier);
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param mixed  $identifier
     * @param string $token
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        return $this->_adapter->getUserInfo($identifier);
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param string                                     $token
     *
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        $user->setRememberToken($token);
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param array $credentials
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if ($this->_adapter->isConnectedToLdap()) {
            $username = $credentials['username'];
            $password = $credentials['password'];

            return $this->_adapter->getUserInfo($username, $password);
        }
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param array                                      $credentials
     *
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return $this->_adapter->connect($credentials['username'], $credentials['password']);
    }
}
