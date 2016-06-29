<?php

namespace Dsdevbe\LdapConnector\Adapter;

use Adldap\Adldap as adLDAPService;
use Adldap\Models\User as adLDAPUserModel;
use Dsdevbe\LdapConnector\Model\User as UserModel;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class Adldap implements LdapInterface
{
    /**
     * @var HasherContract
     */
    protected $_hasher;

    /**
     * @var adLDAPService
     */
    protected $_ldap;

    /**
     * @var string
     */
    protected $_username;

    /**
     * @var string
     */
    protected $_password;

    public function __construct(HasherContract $hasher, array $config)
    {
        $this->_hasher = $hasher;
        $this->_ldap = new adLDAPService($config);
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    public function connect($username, $password)
    {
        return $this->_ldap->authenticate($username, $password);
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return UserModel
     */
    public function getUserInfo($username, $password = null)
    {
        $user = $this->_ldap->search()->where('samaccountname', '=', $username)->first();

        if (!$user) {
            return;
        }

        return $this->mapDataToUserModel($user, $password);
    }

    /**
     * @return bool
     */
    public function isConnectedToLdap()
    {
        return $this->_ldap->getConnection()->isBound();
    }

    protected function mapDataToUserModel(adLDAPUserModel $user, $password)
    {
        $model = new UserModel([
            'username' => $user->getAccountName(),
            'password' => ($password) ? $this->_hasher->make($password) : null,
        ]);
        $model->setUserInfo($user);

        return $model;
    }
}
