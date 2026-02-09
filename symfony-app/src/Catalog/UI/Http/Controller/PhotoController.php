<?php

declare(strict_types=1);

namespace App\Catalog\UI\Http\Controller;

use App\Catalog\Application\Command\ToggleLikeCommand;
use App\Catalog\Application\Exception\LikeException;
use App\Catalog\Application\Exception\PhotoNotExistException;
use App\Catalog\Application\Query\ImportFileQuery;
use App\Catalog\Domain\Entity\Photo;
use App\Catalog\UI\Form\PhotoType;
use App\Identity\Application\Exception\UserNotFoundException;
use App\Identity\Application\Query\GetUserProfileQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

class PhotoController extends AbstractController
{
    public function __construct(
        #[Target('command.bus')]
        private MessageBusInterface $commandBus,
        #[Target('query.bus')]
        private MessageBusInterface $queryBus,
    ) {}

    #[Route('/photo/{id}/like', name: 'photo_like')]
    public function like(int $id, Request $request): Response
    {
        $session = $request->getSession();
        $userId = $session->get('user_id');

        if (!$userId) {
            $this->addFlash('error', 'You must be logged in to like photos.');
            return $this->redirectToRoute('home');
        }

        try {
            $this->commandBus->dispatch(new ToggleLikeCommand($id, $userId));
            $this->addFlash('success', 'Like toggled!');
        } catch (PhotoNotExistException $e) {
            $this->addFlash('error', 'Photo not found.');
        } catch (LikeException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('home');
    }

    #[Route('/photo/import', name: 'photo-add')]
    public function addPhoto(Request $request): Response
    {
        $session = $request->getSession();
        $userId = $session->get('user_id');

        if (!$userId) {
            return $this->redirectToRoute('home');
        }

        try {
            $user = $this->dispatch(new GetUserProfileQuery($userId));
        } catch (UserNotFoundException $e) {
            $this->addFlash('error', $e->getMessage());
            $session->clear();
            return $this->redirectToRoute('home');
        }

        $product = new Photo();
        $form = $this->createForm(PhotoType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $tokenImages = $this->dispatch(new ImportFileQuery($user));

            throw new \Exception($tokenImages);
        }

//        return $this->render('profile/add_image.html.twig', [
//            'user' => $user,
//            'form' => $form,
//        ]);
    }

    private function dispatch(object $query): mixed
    {
        $envelope = $this->queryBus->dispatch($query);
        $handledStamp = $envelope->last(HandledStamp::class);

        return $handledStamp?->getResult();
    }

}
