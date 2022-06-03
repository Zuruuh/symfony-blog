<?php

namespace App\Validator;

use App\Constraint\Post\UniqueSlugConstraint;
use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueSlugValidator extends ConstraintValidator
{
    public function __construct(private PostRepository $postRepository) {}

    /**
     * @param string               $value
     * @param UniqueSlugConstraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        $slug = Post::generateSlug($value);
        $post = $this->postRepository->findOneBy(['slug' => $slug]);

        if ($constraint->getPost() !== $post && $post) {
            $this
                ->context
                ->buildViolation($constraint::ALREADY_IN_USE)
                ->addViolation()
            ;
        }
    }
}