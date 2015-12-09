<?php

namespace Dsdevbe\LdapConnector\Adapter;

use adLDAP\adLDAP as adLDAPService;
use Dsdevbe\LdapConnector\Model\User as UserModel;

class Adldap implements LdapInterface
{
    protected $_ldap;

    protected $_username;

    protected $_password;

    protected function mapDataToUserModel($user, array $groups)
    {
        $model = new UserModel([
            'username' => $user->samaccountname,
            'password' => $this->_password,
        ]);
        $model->setGroups($groups);
        $model->setUserInfo([
            'username'  => $user->samaccountname,
            'firstname' => $user->givenname,
            'lastname'  => $user->sn,
            'email'     => $user->mail,
        ]);

        return $model;
    }

    public function __construct($config)
    {
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
        $this->_username = $username;
        $this->_password = $password;

        return $this->_ldap->authenticate($username, $password);
    }

    /**
     * @return bool
     */
    public function isConnected()
    {
        return !!$this->_ldap->getLdapBind();
    }

    /**
     * @param string $username
     *
     * @return UserModel
     */
    public function getUserInfo($username)
    {
        $user = $this->_ldap->user()->infoCollection($username, ['samaccountname','givenname','sn','mail']);

        if (!$user) {
            return;
        }

        $groups = $this->_ldap->user()->groups($username);

        return $this->mapDataToUserModel($user, $groups);
    }
}
