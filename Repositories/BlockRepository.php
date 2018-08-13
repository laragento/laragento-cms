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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Laragento\Cms\Helpers\FileHelper;

class BlockRepository extends AbstractContentRepository implements BlockRepositoryInterface
{
    public function __construct(FileHelper $fileHelper)
    {
        $this->fileHelper = $fileHelper;
    }


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
                    if ($field instanceof UploadedFile) {
                        $file = $field;
                        $field = $this->handleUploadedFile($file);
                    }
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

    protected function handleUploadedFile($file)
    {

        $path = $this->fileHelper->preparePath('block/images');
        $this->fileHelper->storeUploadedFile($file, $path);
        return substr($path, -33) . '/' . $file->getClientOriginalName();
    }
}