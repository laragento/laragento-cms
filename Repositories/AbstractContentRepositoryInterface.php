<?php


namespace Laragento\Cms\Repositories;


use Laragento\Core\Repositories\RepositoryInterface;

interface AbstractContentRepositoryInterface extends RepositoryInterface
{
    /**
     * @param $data
     * @param null $id
     * @return mixed
     */
    public function store($data, $id = null);

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id);

    /**
     * @return mixed
     */
    public function allByOwner();
}