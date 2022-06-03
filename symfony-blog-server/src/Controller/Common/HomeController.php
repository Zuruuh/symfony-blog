<?php

namespace App\Controller\Common;

use App\Common\AbstractController;
use App\Normalizers\User\SelfNormalizer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', methods: ['GET'])]
    public function self(): Response
    {
        $data = $this->serialize(SelfNormalizer::class, $this->getUser());

        return $this->json($data);
    }
}