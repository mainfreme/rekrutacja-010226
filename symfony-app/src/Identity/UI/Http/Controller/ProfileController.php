<?php

declare(strict_types=1);

namespace App\Identity\UI\Http\Controller;

use App\Identity\Application\Command\UpdateUserTokenCommand;
use App\Identity\Application\Exception\UserCannotUpdateException;
use App\Identity\Application\Exception\UserNotFoundException;
use App\Identity\Domain\ValueObject\AuthToken;
use App\Identity\UI\Form\ProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Attribute\Target;
use App\Identity\Application\Query\GetUserProfileQuery;

class ProfileController extends AbstractController
{
    public function __construct(
        #[Target('query.bus')]
        private MessageBusInterface $queryBus,
        #[Target('command.bus')]
        private MessageBusInterface $commandBus,
    ) {}

    #[Route('/profile', name: 'profile')]
    public function profile(Request $request): Response
    {
        $session = $request->getSession();
        $userId = $session->get('user_id');

        if (!$userId) {
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(ProfileType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            try {
                $tokenVo = new AuthToken($data['token']);
                $this->commandBus->dispatch(new UpdateUserTokenCommand($userId, $tokenVo));
                $this->addFlash('success', 'Poprawnie dodano token');
            } catch (UserCannotUpdateException $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        try {
            $user = $this->dispatch(new GetUserProfileQuery($userId));
        } catch (UserNotFoundException $e) {
            $this->addFlash('error', $e->getMessage());
            $session->clear();
            return $this->redirectToRoute('home');
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    private function dispatch(object $query): mixed
    {
        $envelope = $this->queryBus->dispatch($query);
        $handledStamp = $envelope->last(HandledStamp::class);

        return $handledStamp?->getResult();
    }
}
