<?php


namespace EuroMillions\shared\helpers;

use Phalcon\Paginator\Adapter\NativeArray as PaginatorArray;

trait PaginatedControllerTrait
{
    protected function getPaginatorAsArray(array $collection, $limit, $currentPage)
    {
        $paginator = new PaginatorArray(
            [
                'data' => $collection,
                'limit' => $limit,
                'page'  => $currentPage,
            ]
        );
        return $paginator;
    }

}