<?php

declare(strict_types=1);

namespace App\Controller;

use App\Likes\LikeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LikeRepositoryInterface;
use App\Repository\UserRepository;
use App\Repository\PhotoRepository;

class PhotoController extends AbstractController
{

    public function __construct(
        private LikeRepositoryInterface $likeRepository,
        private LikeService $likeService,
        private UserRepository $userRepository,
        private PhotoRepository $photoRepository,
    ) {}

    #[Route('/photo/{id}/like', name: 'photo_like')]
    public function like($id, Request $request): Response
    {
        $session = $request->getSession();
        $userId = $session->get('user_id');

        if (!$userId) {
            $this->addFlash('error', 'You must be logged in to like photos.');
            return $this->redirectToRoute('home');
        }

        $user = $this->userRepository->find($userId);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $photo = $this->photoRepository->find($id);
        if (!$photo) {
            throw $this->createNotFoundException('Photo not found');
        }

        $this->likeRepository->setUser($user);

        if ($this->likeRepository->hasUserLikedPhoto($photo)) {
            $this->likeRepository->unlikePhoto($photo);
            $this->addFlash('info', 'Photo unliked!');
        } else {
            $this->likeService->execute($photo);
            $this->addFlash('success', 'Photo liked!');
        }

        return $this->redirectToRoute('home');
    }
}
