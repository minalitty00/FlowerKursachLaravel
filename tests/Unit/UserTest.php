<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * Test that isAdmin returns true for admin role.
     */
    public function test_is_admin_returns_true_for_admin_role(): void
    {
        $user = new User(['role' => 'admin']);
        $this->assertTrue($user->isAdmin());
    }

    /**
     * Test that isAdmin returns false for user role.
     */
    public function test_is_admin_returns_false_for_user_role(): void
    {
        $user = new User(['role' => 'user']);
        $this->assertFalse($user->isAdmin());
    }

    /**
     * Test that isAdmin returns false for null role.
     */
    public function test_is_admin_returns_false_for_null_role(): void
    {
        $user = new User(['role' => null]);
        $this->assertFalse($user->isAdmin());
    }
}
