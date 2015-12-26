<?php

namespace spec\Dsdevbe\LdapConnector;

use Dsdevbe\LdapConnector\Adapter\LdapInterface;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use PhpSpec\ObjectBehavior;

class LdapUserProviderSpec extends ObjectBehavior
{
    public function let(HasherContract $hasher, LdapInterface $interface)
    {
        $this->beConstructedWith($hasher, $interface);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Dsdevbe\LdapConnector\LdapUserProvider');
    }

    public function it_validate_user_by_credentials(LdapInterface $interface)
    {
        $user = [
            'username' => 'john.doe@example.com',
            'password' => 'johnpassdoe',
        ];

        $interface->connect($user['username'], $user['password'])->shouldBeCalled();
        $this->retrieveByCredentials($user);
    }

    public function it_retrieves_user_by_id(LdapInterface $interface)
    {
        $identifier = 'john.doe@example.com';

        $interface->getUserInfo($identifier)->shouldBeCalled();
        $this->retrieveById($identifier);
    }
}
