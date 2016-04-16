<?php

namespace spec\Dsdevbe\LdapConnector;

use Dsdevbe\LdapConnector\Adapter\LdapInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use PhpSpec\ObjectBehavior;

class LdapUserProviderSpec extends ObjectBehavior
{
    public function let(LdapInterface $interface)
    {
        $this->beConstructedWith($interface);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Dsdevbe\LdapConnector\LdapUserProvider');
    }

    public function it_validate_user_by_credentials()
    {
        $user = [
            'username' => 'john.doe@example.com',
            'password' => 'johnpassdoe',
        ];

        $this->retrieveByCredentials($user);
    }

    public function it_validate_password_against_ldap(LdapInterface $interface, Authenticatable $user)
    {
        $credentials = [
            'username' => 'john.doe@example.com',
            'password' => 'johnpassdoe',
        ];

        $interface->connect($credentials['username'], $credentials['password'])->shouldBeCalled();
        $this->validateCredentials($user, $credentials);
    }

    public function it_retrieves_user_by_id(LdapInterface $interface)
    {
        $identifier = 'john.doe@example.com';

        $interface->getUserInfo($identifier)->shouldBeCalled();
        $this->retrieveById($identifier);
    }
}
