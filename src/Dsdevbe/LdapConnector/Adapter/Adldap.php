<?php
namespace Dsdevbe\LdapConnector\Adapter;

use adLDAP\adLDAP as adLDAPService;
use Dsdevbe\LdapConnector\Model\User as UserModel;

class Adldap implements LdapInterface
{
    protected $_ldap;

    protected $_username;

    protected $_password;

    protected function mapDataToUserModel($username, array $groups)
    {
        $model = new UserModel([
            'username' => $username,
            'password' => $this->_password,
        ]);
        $model->setGroups($groups);
        return $model;
    }

    public function __construct($config)
    {
        $this->_ldap = new adLDAPService($config);
    }

    /**
     * @param String $username
     * @param String $password
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
     * @param String $username
     * @return UserModel
     */
    public function getUserInfo($username)
    {
        $user = $this->_ldap->user()->info($username);

        if (!$user) {
            return null;
        }

        $groups = $this->_ldap->user()->groups($username);
        return $this->mapDataToUserModel($username, $groups);
    }
}
