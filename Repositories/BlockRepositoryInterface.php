<?php


namespace Laragento\Cms\Repositories;


use Laragento\Cms\Models\Element\ElementFieldValue;

interface BlockRepositoryInterface extends AbstractContentRepositoryInterface
{


    /**
     * @inheritDoc
     */
    public function storeValues($element);
}