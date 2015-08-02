<?php
namespace Dsdevbe\LdapConnector\Model;

use Illuminate\Contracts\Auth\Authenticatable;

class User implements Authenticatable
{
    /**
     * @var String
     */
    protected $_authIdentifier;

    /**
     * @var String
     */
    protected $_authPassword;

    /**
     * @var String
     */
    protected $_rememberToken;

    /**
     * @var array
     */
    protected $_groups;

    public function __construct(array $attributes)
    {
        $this->_authIdentifier = $attributes['username'];
        $this->_authPassword = (isset($attributes['password'])) ? $attributes['password'] : null;
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
     * @param  string $value
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
     * @return array
     */
    public function getGroups()
    {
        return $this->_groups;
    }

    /**
     * @param array $groups
     */
    public function setGroups(array $groups)
    {
        $this->_groups = $groups;
    }

    public function inGroup($groupName)
    {
        return in_array($groupName, $this->_groups);
    }

}