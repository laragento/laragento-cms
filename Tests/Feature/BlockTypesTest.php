<?php

namespace Laragento\Cms\Tests\Feature;


use Laragento\Cms\Models\Block;
use Laragento\Cms\Models\BlockType;
use Laragento\Cms\Models\Element\Element;
use Laragento\Cms\Models\Element\ElementType;
use Laragento\Cms\Tests\CmsTestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BlockTypesTest extends CmsTestCase
{

    use DatabaseTransactions;

    /**
     * @group Cms
     * @test
     */
    public function an_authenticated_admin_can_retrieve_all_block_types()
    {
        // We have a signed in admin
        $this->actingAs($this->admin, 'admins');
        $count = BlockType::all()->count();

        // We hit the controller
        $this->json('get', 'admin/cms/blocktype/')
            ->assertStatus(200)
            ->assertJsonCount($count);
    }

    /**
     * @group Cms
     * @test
     */
    public function an_authenticated_admin_can_retrieve_a_specific_block_type()
    {
        // We have a signed in admin
        $this->actingAs($this->admin, 'admins');

        $blocktype = factory(BlockType::class)->create();

        // We hit the controller
        $this->json('get', 'admin/cms/blocktype/'.$blocktype->id)
            ->assertStatus(200)
            ->assertJson(['title' => $blocktype->title]);
    }


    /**
     * @group Cms
     * @test
     */
    public function an_unauthenticated_admin_cannot_retrieve_any_block_type()
    {
        // We hit the controller as a guest and assert the redirection
        $this->get('admin/cms/blocktype/99999')->assertRedirect('admin/login');
    }

    /**
     * @group Cms
     * @test
     */
    public function an_authenticated_admin_can_create_a_block_type()
    {
        // We have a signed in admin
        $this->actingAs($this->admin, 'admins');

        $blocktype = factory(BlockType::class)->make();

        // We hit the controller
        $this->json('post', 'admin/cms/blocktype/new',$blocktype->toArray())
            ->assertStatus(201);
        $this->assertDatabaseHas('lg_cms_block_types', ['title' => $blocktype->title]);
    }

    /**
     * @group Cms
     * @test
     */
    public function an_authenticated_admin_can_update_a_block_type()
    {
        // We have a signed in admin
        $this->actingAs($this->admin, 'admins');

        $blocktype = factory(BlockType::class)->create();
        $data = $blocktype->toArray();
        $newTitle = $blocktype->title . ' - updated';
        $data['title'] = $newTitle;

        // We hit the controller
        $this->json('patch', 'admin/cms/blocktype/'.$blocktype->id, $data)
            ->assertStatus(200);
        $this->assertDatabaseHas('lg_cms_block_types', ['title' => $newTitle]);
    }

    /**
     * @group Cms
     * @test
     */
    public function an_authenticated_admin_can_add_elements_to_a_block_type()
    {

        // We have a signed in admin
        $this->actingAs($this->admin, 'admins');

        $elementtype = ElementType::whereTitle('TextLineElement')->first();

        $blocktype = factory(BlockType::class)->create();
        $data = [
            'title' => 'TestElement',
            'element_type_id' => $elementtype->id
        ];
        // We hit the controller
        $this->json('post', 'admin/cms/blocktype/'.$blocktype->id.'/element', $data)
            ->assertStatus(201);
        $this->assertDatabaseHas('lg_cms_elements', ['title' => $data['title']]);
    }

    /**
     * @test
     */
    public function an_authenticated_admin_can_update_elements_on_a_block_type()
    {
        // We have a signed in admin
        $this->actingAs($this->admin, 'admins');

        $elementtype = ElementType::whereTitle('TextLineElement')->first();
        $element = factory(Element::class)->create(['element_type_id' => $elementtype->id]);

        $newData = [
            'sort_nr' => 999,
            'title' => $element->title . ' - bearbeitet'
        ];

        // We hit the controller
        $this->json('patch', 'admin/cms/blocktype/element/'.$element->id, $newData)
            ->assertStatus(200);
        $this->assertDatabaseHas('lg_cms_elements', ['title' => $element->title . ' - bearbeitet']);
        $this->assertDatabaseHas('lg_cms_elements', ['sort_nr' => 999]);
    }

    /**
     * @group Cms
     * @test
     */
    public function an_authenticated_admin_can_remove_elements_from_a_block_type()
    {
        // We have a signed in admin
        $this->actingAs($this->admin, 'admins');

        $elementtype = ElementType::whereTitle('TextLineElement')->first();
        $element = factory(Element::class)->create(['element_type_id' => $elementtype->id]);

        // We hit the controller
        $this->json('delete', 'admin/cms/blocktype/element/'.$element->id)
            ->assertStatus(204);
        $this->assertDatabaseMissing('lg_cms_elements', ['id' => $element->id]);
    }


    /**
     * @group Cms
     * @test
     */
    public function an_authenticated_admin_can_destroy_a_block_type_without_relations()
    {
        // We have a signed in admin
        $this->actingAs($this->admin, 'admins');

        $blocktype = factory(BlockType::class)->create();


        // We hit the controller
        $this->json('delete', 'admin/cms/blocktype/'.$blocktype->id)
            ->assertStatus(204);
        $this->assertDatabaseMissing('lg_cms_block_types', ['id' => $blocktype->id]);

    }

    /**
     * @group Cms
     * @test
     */
    public function an_authenticated_admin_cannot_destroy_a_block_type_with_relations()
    {
        // We have a signed in admin
        $this->actingAs($this->admin, 'admins');

        $blocktype = factory(BlockType::class)->create();
        factory(Block::class)->create(['type_id' => $blocktype->id]);


        // We hit the controller
        $this->json('delete', 'admin/cms/blocktype/'.$blocktype->id)
            ->assertStatus(400);


    }
}
