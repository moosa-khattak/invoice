<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class UserAuthenticatableTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_user_implements_authenticatable(): void
    {
        $user = new User();
        $this->assertInstanceOf(Authenticatable::class, $user);
    }
}
