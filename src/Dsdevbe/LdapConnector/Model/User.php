<?php

namespace Dsdevbe\LdapConnector\Model;

use Adldap\Models\User as adLDAPUserModel;
use Illuminate\Contracts\Auth\Authenticatable;

class User implements Authenticatable
{
    /**
     * @var string
     */
    protected $_authIdentifier;

    /**
     * @var string
     */
    protected $_authPassword;

    /**
     * @var string
     */
    protected $_rememberToken;

    /**
     * @var adLDAPUserModel
     */
    protected $_adLDAP;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->_authIdentifier = $attributes['username'];
        $this->_authPassword = (isset($attributes['password'])) ? $attributes['password'] : null;
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return $this->_authIdentifier;
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->_authIdentifier;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->_authPassword;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->_rememberToken;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param string $value
     *
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->_rememberToken = $value;
    }

    public function getRememberTokenName()
    {
        // In LDAP no colomn to save
    }

    /**
     * @return adLDAPUserModel
     */
    public function getAdLDAP()
    {
        return $this->_adLDAP;
    }

    /**
     * @param adLDAPUserModel $userModel
     *
     * @return $this
     */
    public function setUserInfo(adLDAPUserModel $userModel)
    {
        $this->_adLDAP = $userModel;

        return $this;
    }
}
