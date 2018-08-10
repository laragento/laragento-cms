<?php


namespace Laragento\Cms\Repositories;


use Laragento\Cms\Models\Page;

class AbstractContentRepository implements AbstractContentRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function all()
    {
        // TODO: Implement all() method.
    }

    /**
     * @inheritDoc
     */
    public function allByOwner()
    {
        // TODO: Implement all() method.
    }

    /**
     * @inheritDoc
     */
    public function find()
    {
        // TODO: Implement find() method.
    }

    /**
     * @inheritDoc
     */
    public function first($identifier)
    {
        // TODO: Implement first() method.
    }

    /**
     * @inheritDoc
     */
    public function forceFirst($identifier)
    {
        // TODO: Implement forceFirst() method.
    }

    /**
     * @inheritDoc
     */
    public function get()
    {
        // TODO: Implement get() method.
    }

    public function store($data, $id = null)
    {
        // TODO: Implement store() method.
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }


}