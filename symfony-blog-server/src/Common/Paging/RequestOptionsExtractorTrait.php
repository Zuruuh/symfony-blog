<?php

namespace App\Common\Paging;

use App\Common\Paging\Exceptions\RequestStackUnavailableException;
use Symfony\Component\HttpFoundation\RequestStack;

trait RequestOptionsExtractorTrait
{
    /**
     * @throws RequestStackUnavailableException
     */
    public function getRequestStack(): RequestStack
    {
        if (!property_exists($this, 'requestStack') || !$this->requestStack instanceof RequestStack) {
            throw new RequestStackUnavailableException();
        }

        return $this->requestStack;
    }

    public function getRequestOptions(): RequestOptions
    {
        return new RequestOptions();
    }

    public function getPagingOptions(): RequestOptions
    {
        return new RequestOptions();
    }

    public function getSortingOptions(): RequestOptions
    {
        return new RequestOptions();
    }

    public function getFilterOptions(): RequestOptions
    {
        return new RequestOptions();
    }

}