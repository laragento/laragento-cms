<?php


namespace Laragento\Cms\Repositories;


use Illuminate\Support\Facades\Auth;
use Laragento\Cms\Models\Block;
use Laragento\Cms\Models\BlockType;
use Laragento\Cms\Models\Element\Element;


class BlockTypeRepository extends AbstractContentRepository implements BlockTypeRepositoryInterface
{

    public function all()
    {
        return BlockType::all();
    }

    public function first($identifier)
    {
        return BlockType::whereId($identifier)->first();
    }

    public function store($data, $id = null)
    {

        if (!$id) {
            $blockType = BlockType::create($data);
        } else {
            $blockType = BlockType::whereId($id)->first();
            $blockType->update($data);
        }

        return $blockType;
    }

    public function destroy($id)
    {
        BlockType::destroy($id);
    }

}