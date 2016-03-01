<?php

namespace Dsdevbe\LdapConnector;

use Arr;
use Dsdevbe\LdapConnector\Adapter\LdapInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as UserProviderInterface;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class LdapUserProvider implements UserProviderInterface
{
    /**
     * @var HasherContract
     */
    protected $_hasher;

    /**
     * @var LdapInterface;
     */
    protected $_adapter;

    public function __construct(HasherContract $hasher, LdapInterface $adapter)
    {
        $this->_hasher = $hasher;
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
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string                                      $token
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
        $username = $credentials['username'];
        $password = $credentials['password'];

        if ($this->_adapter->connect($username, $password)) {
            return $this->_adapter->getUserInfo($username, $password);
        }

        return;
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array                                       $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return $this->_hasher->check($credentials['password'], $user->getAuthPassword());
    }
}
