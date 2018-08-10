<?php

namespace Laragento\Cms\Tests\Feature;


use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laragento\Cms\Models\Block;
use Laragento\Cms\Models\BlockType;
use Laragento\Cms\Models\Element\Element;
use Laragento\Cms\Models\Element\ElementField;
use Laragento\Cms\Models\Element\ElementFieldValue;
use Laragento\Cms\Models\Element\ElementType;
use Laragento\Cms\Models\Page;
use Laragento\Cms\Tests\CmsTestCase;

class BlocksTest extends CmsTestCase
{

    use DatabaseTransactions;

    /**
     * @group Cms
     * @test
     */
    public function an_authenticated_admin_can_retrieve_all_blocks_of_a_page()
    {
        // We have a signed in admin
        $this->actingAs($this->admin, 'admins');

        $initialcount = Block::all()->count();

        // We make a page owned by this customer
        $page = factory(Page::class)->create(['user_id' => $this->admin->id]);
        $typeId = BlockType::all()->first()->id;

        // We make 4 blocks belonging to this Page
        factory(Block::class, 4)->create(['page_id' => $page->id, 'type_id' => $typeId]);

        // We hit the controller
        $this->json('get', 'admin/cms/page/' . $page->id . '/block')
            ->assertStatus(200)
            ->assertJsonCount(4 + $initialcount);
    }

    /**
     * @group Cms
     * @test
     */
    public function an_authenticated_admin_can_retrieve_a_specific_block()
    {
        // We have a signed in admin
        $this->actingAs($this->admin, 'admins');

        // We make a page owned by this customer
        $page = factory(Page::class)->create(['user_id' => $this->admin->id]);

        // We make a block belonging to this page
        $typeId = BlockType::all()->first()->id;
        $block = factory(Block::class)->create(['page_id' => $page->id, 'type_id' => $typeId]);

        // We hit the controller
        $this->json('get', 'admin/cms/page/' . $page->id . '/block/' . $block->id)
            ->assertStatus(200)
            ->assertJson([
                'title' => $block->title
            ]);
    }

    /**
     * @group Cms
     * @test
     */
    public function an_unauthenticated_admin_cannot_retrieve_any_block()
    {
        // We hit the controller as a guest and assert the redirection
        $this->get('admin/cms/page/1/block/99999')->assertRedirect('admin/login');
        $this->get('admin/cms/page/1/block/')->assertRedirect('admin/login');
    }

    /**
     * @group Cms
     * @test
     */
    public function an_authenticated_admin_can_create_a_specific_block_for_a_page()
    {

        // We have a signed in admin
        $this->actingAs($this->admin, 'admins');

        // We have a page and a blockType
        $blocktype = $this->createBlockTypeWithTextLineAndLink();

        $page = factory(Page::class)->create();

        // We make a block
        $block = [];
        $block['title'] = 'Testblock';
        $block['type_id'] = $blocktype->id;

        // We hit the controller
        $newBlock = $this->json('post', 'admin/cms/page/' . $page->id . '/block/new',
            $block)->assertStatus(201)->decodeResponseJson();
        $values = ElementFieldValue::whereBlockId($newBlock['id'])->get();

        $this->assertDatabaseHas('lg_cms_blocks', ['title' => $block['title']]);
        $this->assertCount(4, $values);
    }

    /**
     * @group Cms
     * @test
     */
    public function an_authenticated_admin_can_update_a_specific_block()
    {

        // We have a signed in admin
        $this->actingAs($this->admin, 'admins');

        // We have a block
        $block = factory(Block::class)->create(['type_id' => factory(BlockType::class)->create()->id]);

        // We update the block
        $newBlock = $block->toArray();
        $newBlock['title'] = $block->title . ' - updated';

        // We hit the controller
        $this->json('patch', 'admin/cms/page/' . $block->page->id . '/block/' . $block->id,
            $newBlock)->assertStatus(200);
        $this->assertDatabaseHas('lg_cms_blocks', ['title' => $newBlock['title']]);
    }

    /**
     * @group Cms
     * @test
     */
    public function an_authenticated_admin_can_update_the_contents_of_a_block()
    {

        $this->withoutExceptionHandling();

        // We have a signed in admin
        $this->actingAs($this->admin, 'admins');

        $page = factory(Page::class)->create();
        $type = $this->createBlockTypeWithTextLineAndLink();
        $block = factory(Block::class)->create(['page_id' => $page->id, 'type_id' => $type->id]);
        $elements = $block->blockType->elements;
        $id = 0;
        foreach ($elements as $element) {
            $fields = $element->fields();
            foreach ($fields as $field) {
                if ($field->title == 'content') {
                    $id = $field->id;
                }
                ElementFieldValue::create([
                    'block_id' => $block->id,
                    'element_id' => $element->id,
                    'element_field_id' => $field->id,
                    'value' => null
                ]);
            }
        }

        $value = $block->values()->whereElementFieldId($id)->first();
        $newData = [
            'fields' => [
                $value->id => $value->value . ' - updated'
            ]
        ];


        // We hit the controller
        $this->json('patch', 'admin/cms/page/' . $page->id . '/block/' . $block->id . '/content', $newData)
            ->assertStatus(200);
        //$this->assertDatabaseHas('lg_cms_element_field_values', ['value' => $value->value . ' - updated']);
    }

    /**
     * @test
     */
    public function an_authenticated_admin_can_update_blocks_on_a_page()
    {
        // We have a signed in admin
        $this->actingAs($this->admin, 'admins');

        $page = factory(Page::class)->create();
        $type = $this->createBlockTypeWithTextLineAndLink();
        $block = factory(Block::class)->create(['page_id' => $page->id, 'type_id' => $type->id]);

        $newData = $block->toArray();
        $newData['title'] = $block->title . ' - updated';
        $newData['sortnr'] = $block->sortnr + 999;

        // We hit the controller
        $this->json('patch', 'admin/cms/page/' . $page->id . '/block/' . $block->id, $newData)
            ->assertStatus(200);
        $this->assertDatabaseHas('lg_cms_blocks',
            ['title' => $block->title . ' - updated', 'sortnr' => $block->sortnr + 999]);
    }

    /**
     * @group Cms
     * @test
     */
    public function an_authenticated_admin_can_remove_blocks_from_a_page()
    {
        // We have a signed in admin
        $this->actingAs($this->admin, 'admins');

        $page = factory(Page::class)->create();
        $type = $this->createBlockTypeWithTextLineAndLink();
        $block = factory(Block::class)->create(['page_id' => $page->id, 'type_id' => $type->id]);

        // We hit the controller
        $this->json('delete', 'admin/cms/page/' . $page->id . '/block/' . $block->id)
            ->assertStatus(204);
        $this->assertDatabaseMissing('lg_cms_blocks', ['id' => $block->id]);
    }

    /**
     * @group Cms
     * @test
     */
    public function an_authenticated_admin_can_destroy_a_block()
    {
        // We have a signed in admin
        $this->actingAs($this->admin, 'admins');

        // We have a page and the specific type
        $page = factory(Page::class)->create();
        $type = factory(BlockType::class)->create();
        $block = factory(Block::class)->create(['page_id' => $page->id, 'type_id' => $type->id]);

        // We hit the controller
        $this->json('delete', 'admin/cms/page/' . $page->id . '/block/' . $block->id)->assertStatus(204);

        // We assert the deleted database entry
        $this->assertDatabaseMissing('lg_cms_blocks', ['id' => $block->id]);
    }

    protected function createBlockTypeWithTextLineAndLink()
    {
        $blockType = factory(BlockType::class)->create(['title' => 'Text Mit Link']);
        $textElementType = factory(ElementType::class)->create(['title' => 'DemoTextLineElement']);
        $linkElementType = factory(ElementType::class)->create(['title' => 'DemoLinkElement']);
        factory(ElementField::class)->create(['title' => 'link_url', 'element_type_id' => $linkElementType->id]);
        factory(ElementField::class)->create(['title' => 'link_name', 'element_type_id' => $linkElementType->id]);
        factory(ElementField::class)->create(['title' => 'link_blank', 'element_type_id' => $linkElementType->id]);
        factory(ElementField::class)->create(['title' => 'content', 'element_type_id' => $textElementType->id]);
        factory(Element::class)->create([
            'title' => 'my Link element',
            'block_type_id' => $blockType->id,
            'element_type_id' => $linkElementType->id
        ]);
        factory(Element::class)->create([
            'title' => 'my Text element',
            'block_type_id' => $blockType->id,
            'element_type_id' => $textElementType->id
        ]);

        return $blockType;
    }

}
