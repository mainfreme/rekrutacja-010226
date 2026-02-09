<?php

declare(strict_types=1);

namespace App\Identity\UI\Http\Controller;

use App\Identity\Domain\Entity\User;
use App\Identity\Domain\ValueObject\Username;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use App\Identity\Domain\ValueObject\AuthToken;
use App\Identity\Application\Query\LoginQuery;
use App\Identity\Application\Exception\InvalidTokenException;
use App\Identity\Application\Exception\UserNotFoundException;

class AuthController extends AbstractController
{
    public function __construct(
        #[Target('query.bus')]
        private MessageBusInterface $queryBus
    ) {}

    #[Route('/auth/{username}/{token}', name: 'auth_login')]
    public function login(string $username, string $token, Request $request): Response
    {
        try {
            $usernameVO = new Username($username);
            $tokenVO = new AuthToken($token);
        } catch (\InvalidArgumentException $e) {
            return new Response($e->getMessage(), 401);
        }

        try {
            $envelope = $this->queryBus->dispatch(new LoginQuery($usernameVO, $tokenVO));
            $handledStamp = $envelope->last(HandledStamp::class);

            /** @var User|null $user */
            $user = $handledStamp?->getResult();

            if (!$user) {
                throw new UserNotFoundException();
            }
        } catch (InvalidTokenException $e) {
            return new Response($e->getMessage(), 401);
        } catch (UserNotFoundException $e) {
            return new Response($e->getMessage(), 404);
        }

        $session = $request->getSession();
        $session->set('user_id', $user->getId());
        $session->set('username', $user->getUsername());

        $this->addFlash('success', 'Welcome back, ' . $user->getUsername() . '!');

        return $this->redirectToRoute('home');
    }

    #[Route('/logout', name: 'logout')]
    public function logout(Request $request): Response
    {
        $session = $request->getSession();
        $session->clear();

        $this->addFlash('info', 'You have been logged out successfully.');

        return $this->redirectToRoute('home');
    }
}
