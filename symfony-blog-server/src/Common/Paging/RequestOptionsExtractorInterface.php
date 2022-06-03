<?php

namespace App\Common\Paging;

use Symfony\Component\HttpFoundation\RequestStack;

interface RequestOptionsExtractorInterface
{
    function getRequestStack(): RequestStack;

    /**
     * @return RequestOptions Gets all the request options (paging + sorting + filtering)
     */
    function getRequestOptions(): RequestOptions;

    function getPagingOptions(): RequestOptions;

    function getSortingOptions(): RequestOptions;

    function getFilterOptions(): RequestOptions;
}
