<?php

namespace Laragento\Cms\Database\Seeders;

use Illuminate\Database\Seeder;
use Laragento\Cms\Models\BlockType;
use Laragento\Cms\Models\Element\Element;
use Laragento\Cms\Models\Element\ElementType;
use Laragento\Cms\Models\Element\ElementField;

class ElementTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            'TextLineElement' => ['content'],
            'HeadingElement' => ['content','format'],
            'LinkElement' => ['link_url', 'link_title', 'open_blank'],
            'TextFieldElement' => ['content'],
            'FormattedTextFieldElement' => ['content'],
            'ImageElement' => ['path','width','height','name','alt_txt','allowed_types']
        ];
        foreach ($types as $key => $fields) {
            print_r($key);
            $el = factory(ElementType::class)->create([
                'title' => $key,
            ]);
            $id = $el->id;
            foreach ($fields as $field) {

                factory(ElementField::class)->create([
                    'title' => $field,
                    'element_type_id' => $id
                ]);
            }
        }
    }
}
