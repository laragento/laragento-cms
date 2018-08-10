<?php


namespace Laragento\Cms\Repositories;


use Illuminate\Support\Facades\Auth;
use Laragento\Cms\Models\Block;
use Laragento\Cms\Models\BlockType;
use Laragento\Cms\Models\Element\ElementFieldValue;
use Laragento\Cms\Models\ElementLink;
use Laragento\Cms\Models\ElementText;
use Laragento\Cms\Models\ElementTitle;
use Laragento\Cms\Models\ElementType;

class BlockRepository extends AbstractContentRepository implements BlockRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function allByOwner()
    {

        return Block::whereHas('page', function ($query) {
            $query->where(['user_id' => Auth::guard('admins')->user()->id]);
        })->get();

    }

    public function first($identifier)
    {
        return Block::whereId($identifier)->first();
    }

    /**
     * @inheritDoc
     */
    public function store($data, $id = null)
    {
        if (!$id) {
            $block = Block::create($data['meta']);
            foreach ($data['fields'] as $element => $fields) {
                foreach ($fields as $key => $field) {
                    ElementFieldValue::create([
                        'block_id' => $block->id,
                        'element_id' => $element,
                        'element_field_id' => $key,
                        'value' => $field
                    ]);
                }
            }
        } else {
            $block = Block::whereId($id)->first();
            $block->update($data['meta']);
            foreach ($data['fields'] as $element => $fields) {
                foreach ($fields as $key => $field) {
                    $value = ElementFieldValue::where([
                        'block_id' => $id,
                        'element_id' => $element,
                        'element_field_id' => $key]);
                    if ($value) {
                        $value->update(['value' => $field]);
                    } else {
                        ElementFieldValue::create([
                            'block_id' => $id,
                            'element_id' => $element,
                            'element_field_id' => $key,
                            'value' => $field
                        ]);
                    }


                }
            }
        }
        return $block;
    }

    /**
     * @inheritDoc
     */
    public function storeValues($data)
    {
        foreach ($data['fields'] as $id => $value) {
            $fieldValue = ElementFieldValue::find($id);

            $fieldValue->update([
                'value' => $value
            ]);
        }
    }

    public function destroy($id)
    {
        Block::destroy($id);
    }

    private function saveElement($id, $type, $value, $elId = null)
    {
        $storeId = 1;
        $typeId = ElementType::whereTitle($type)->first()->id;
        $data = [
            'store_id' => $storeId,
            'block_id' => $id,
            'type_id' => $typeId,
            'name' => 'todo',
        ];
        $function = 'save' . ucfirst($type) . 'Element';
        $this->$function($data, $value, $elId);
    }

    private function saveLinkElement($data, $value, $id = null)
    {
        if (isset($value['link_target'])) {
            $data['link_target'] = $value['link_target'];
        }
        if (isset($value['link_name'])) {
            $data['link_name'] = $value['link_name'];
        }
        if (isset($value['link_target_blank'])) {
            $data['link_target_blank'] = $value['link_target_blank'];
        }
        if ($id) {
            $element = ElementLink::whereId($id)->first();
            $element->update($data);
        } else {
            $element = ElementLink::create($data);
        }
        return $element;
    }

    private function saveTitleElement($data, $value, $id = null)
    {
        $data['value'] = $value;
        if ($id) {
            $element = ElementTitle::whereId($id)->first();
            $element->update($data);
        } else {
            $element = ElementTitle::create($data);
        }
        return $element;
    }

    private function saveTextElement($data, $value, $id = null)
    {
        $data['value'] = $value;
        if ($id) {
            $element = ElementText::whereId($id)->first();
            $element->update($data);
        } else {
            $element = ElementText::create($data);
        }
        return $element;
    }
}