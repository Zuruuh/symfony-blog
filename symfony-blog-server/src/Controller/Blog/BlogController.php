<?php

namespace App\Controller\Blog;

use App\Common\Http\AbstractController;
use App\Entity\Post;
use App\Form\Post\PostType;
use App\Manager\PostManager;
use App\Normalizer\Post\ShowPostNormalizer;
use App\Repository\PostRepository;
use App\Voter\PostVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/blog')]
class BlogController extends AbstractController
{
    #[Route(methods: ['GET'])]
    public function list(PostRepository $postRepository): Response
    {
        /*
        dump($this->getRequestOptions());
        dump($this->getPagingOptions());
        dump($this->getSortingOptions());
        dump($this->getFilterOptions());
        */

        return $this->serializeToJson(ShowPostNormalizer::class, $postRepository->findAll(), ['show_author' => false]);
    }

    #[Route('/{post}', methods: ['GET'])]
    #[ParamConverter('post', class: Post::class, options: ['mapping' => ['post' => 'slug']])]
    public function view(Post $post): Response
    {
        return $this->serializeToJson(ShowPostNormalizer::class, $post);
    }

    #[Route(methods: ['POST'])]
    public function create(PostManager $postManager): Response
    {
        $form = $this->createAndSubmitForm(PostType::class);

        if (!$form->isValid()) {
            return $this->displayForm($form);
        }

        $post = $form->getData();
        $postManager->forPost($post)->save();

        $this->em->flush();

        return $this->serializeToJson(ShowPostNormalizer::class, $post);
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
    public function update(Post $post, PostManager $postManager): Response
    {
        $this->denyAccessUnlessGranted(PostVoter::EDIT_ACTION, $post);
        $form = $this->createAndSubmitForm(PostType::class, $post, ['post' => $post]);

        if (!$form->isValid()) {
            $this->displayForm($form);
        }

        $postManager->forPost($post)->update();
        $this->em->flush();

        return $this->serializeToJson(ShowPostNormalizer::class, $post, ['show_author' => false]);
    }
}
