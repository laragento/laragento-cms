<?php

namespace Laragento\Cms\Database\Seeders;

use Illuminate\Database\Seeder;
use Laragento\Cms\Models\BlockType;
use Laragento\Cms\Models\Element\Element;
use Laragento\Cms\Models\Element\ElementType;
use Laragento\Cms\Models\Element\ElementField;

class UpdateElementTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Example for creating an ElementType */

        /*$types = [
            'TextLineElement' => ['content'],
            'TextFieldElement' => ['content'],
            'FormattedTextFieldElement' => ['content'],
            'ImageElement' => ['path','width','height','name','alt_txt','allowed_types']
        ];
        foreach ($types as $key => $fields) {
            $el = factory(ElementType::class)->create([
                'title' => $key,
            ]);
            foreach ($fields as $field) {
                factory(ElementField::class)->create([
                    'title' => $field,
                    'element_type_id' => $el->id,

                ]);
            }
        }*/
    }
}
