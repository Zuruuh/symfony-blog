<?php

namespace App\Controller\Blog;

use App\Common\AbstractController;
use App\Entity\Post;
use App\Entity\User;
use App\Form\Post\PostType;
use App\Normalizers\Post\ShowPostNormalizer;
use App\Voter\PostVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/blog')]
class BlogController extends AbstractController
{
    #[Route(methods: ['GET'])]
    public function list(): Response
    {
        dump($this->getRequestOptions());
        dump($this->getPagingOptions());
        dump($this->getSortingOptions());
        dump($this->getFilterOptions());

        return $this->void();
    }

    #[Route('/{post}', methods: ['GET'])]
    #[ParamConverter('post', class: Post::class, options: ['mapping' => ['post' => 'slug']])]
    public function view(Post $post): Response
    {
        $data = $this->serialize(ShowPostNormalizer::class, $post);

        return $this->json($data);
    }

    #[Route(methods: ['POST'])]
    public function create(Request $request): Response
    {
        $form = $this->createForm(PostType::class)->submit($request->request->all());

        if (!$form->isValid()) {
            return $this->displayForm($form);
        }

        $post = $form->getData();
        $post->updateSlug();
        $post->setAuthor($this->getUser());

        $this->em->persist($post);
        $this->em->flush();

        $data = $this->serialize(ShowPostNormalizer::class, $post);

        return $this->json($data);
    }

    #[Route('/{post}', methods: ['DELETE'])]
    #[ParamConverter('post', class: Post::class, options: ['mapping' => ['post' => 'slug']])]
    public function delete(Post $post): Response
    {
        $this->denyAccessUnlessGranted(PostVoter::DELETE_ACTION, $post);

        $this->em->remove($post);
        $this->em->flush();

        return $this->void();
    }

    #[Route('/{post}', methods: ['PUT'])]
    #[ParamConverter('post', class: Post::class, options: ['mapping' => ['post' => 'slug']])]
    public function update(Post $post, Request $request): Response
    {
        $this->denyAccessUnlessGranted(PostVoter::EDIT_ACTION, $post);
        $form = $this
            ->createForm(PostType::class, $post, ['post' => $post])
            ->submit($request->request->all());

        if (!$form->isValid()) {
            $this->displayForm($form);
        }

        $post->updateSlug();
        $this->em->flush();

        $data = $this->serialize(ShowPostNormalizer::class, $post, ['show_author' => false]);

        return $this->json($data);
    }
}
