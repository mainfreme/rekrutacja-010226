<?php

declare(strict_types=1);

namespace App\Frontend\Application\UI\Http\Controller;

use App\Catalog\Application\Exception\PhotoNotFindException;
use App\Catalog\Application\Query\GetPhotosWithUsersQuery;
use App\Catalog\Application\Query\GetUserLikesQuery;
use App\Frontend\Application\UI\Form\PhotoFilterType;
use App\Identity\Application\Exception\UserNotFoundException;
use App\Identity\Application\Query\GetUserByIdQuery;
use App\Shared\Application\Dto\PhotoFilterDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class HomeController extends AbstractController
{
    public function __construct(
        #[Target('query.bus')]
        private MessageBusInterface $queryBus,
        private SerializerInterface $serializer,
    ) {}

    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(PhotoFilterType::class);
        $form->handleRequest($request);

        $filterDto = new PhotoFilterDto();
        if ($form->isSubmitted() && $form->isValid()) {
            $filterDto = $this->serializer->denormalize($form->getData(), PhotoFilterDto::class);
        }
        try {
            $photos = $this->dispatch(new GetPhotosWithUsersQuery($filterDto)) ?? [];
        } catch (PhotoNotFindException) {
            $photos = [];
        }

        $userId = $request->getSession()->get('user_id');

        $currentUser = null;
        $userLikes = [];

        if ($userId) {
            try {
                $currentUser = $this->dispatch(new GetUserByIdQuery($userId));

                if ($currentUser && !empty($photos)) {
                    $userLikes = $this->dispatch(new GetUserLikesQuery($userId, $photos));
                }
            } catch (UserNotFoundException) {
                $request->getSession()->remove('user_id');
            }
        }

        return $this->render('home/index.html.twig', [
            'form' => $form,
            'photos' => $photos,
            'currentUser' => $currentUser,
            'userLikes' => $userLikes,
        ]);
    }

    /**
     * Dispatch query and return handled result
     */
    private function dispatch(object $query): mixed
    {
        $envelope = $this->queryBus->dispatch($query);
        $handledStamp = $envelope->last(HandledStamp::class);

        return $handledStamp?->getResult();
    }
}
