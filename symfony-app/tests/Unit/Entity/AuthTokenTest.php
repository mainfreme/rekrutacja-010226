<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Identity\Domain\Entity\AuthToken;
use App\Identity\Domain\Entity\User;
use PHPUnit\Framework\TestCase;

class AuthTokenTest extends TestCase
{
    public function testCreateAuthToken(): void
    {
        $user = new User();
        $user->setUsername('testuser');
        $user->setEmail('test@example.com');

        $token = new AuthToken();
        $token->setToken('abc123xyz');
        $token->setUser($user);

        $this->assertEquals('abc123xyz', $token->getToken());
        $this->assertSame($user, $token->getUser());
        $this->assertInstanceOf(\DateTimeInterface::class, $token->getCreatedAt());
    }
}
