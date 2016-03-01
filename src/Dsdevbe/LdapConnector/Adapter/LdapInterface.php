<?php

namespace Dsdevbe\LdapConnector\Adapter;

use Dsdevbe\LdapConnector\Model\User as UserModel;

interface LdapInterface
{
    /**
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    public function connect($username, $password);

    /**
     * @param $username
     * @param string|null $password
     *
     * @return UserModel
     */
    public function getUserInfo($username, $password = null);
}
