<?php

namespace Laragento\Cms\Tests\Feature;

use Laragento\Cms\Models\Block;
use Laragento\Cms\Models\BlockType;
use Laragento\Cms\Models\Element\ElementField;
use Laragento\Cms\Models\Element\ElementType;
use Laragento\Cms\Models\Page;
use Laragento\Cms\Tests\CmsTestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PagesTest extends CmsTestCase
{
    use DatabaseTransactions;

    /**
     * @group Cms
     * @test
     */
    public function an_authenticated_admin_can_only_retrieve_all_pages_owned_by_himself()
    {
        // We have a signed in admin
        $this->actingAs($this->admin, 'admins');

        $initialcount = Page::all()->count();

        // We make a 4 pages belonging to this Page and 2 pages belonging to the foreign page
        factory(Page::class, 4)->create(['user_id' => $this->admin->id]);
        factory(Page::class, 2)->create(['user_id' => $this->admin->id + 999]);

        // We hit the controller
        $this->json('get', 'admin/cms/page')
            ->assertStatus(200)
            ->assertJsonCount(4 + $initialcount);
    }

    /**
     * @group Cms
     * @test
     */
    public function an_authenticated_admin_can_retrieve_a_specific_page_owned_by_himself()
    {
        // We have a signed in admin
        $this->actingAs($this->admin, 'admins');

        // We make a page owned by this customer
        $page = factory(Page::class)->create(['user_id' => $this->admin->id]);

        // We hit the controller
        $this->json('get', 'admin/cms/page/' . $page->id)
            ->assertStatus(200)
            ->assertJson([
                'title' => $page->title
            ]);
    }

    /**
     * @group Cms
     * @test
     */
    public function an_authenticated_admin_cannot_retrieve_any_foreign_page()
    {

        // We have a signed in admin
        $this->actingAs($this->admin, 'admins');

        // We make a page owned by another customer
        $page = factory(Page::class)->create(['user_id' => $this->admin->id + 999]);

        // We hit the controller
        $this->json('get', 'admin/cms/page/' . $page->id)
            ->assertStatus(403);
    }

    /**
     * @group Cms
     * @test
     */
    public function an_unauthenticated_admin_cannot_retrieve_any_page()
    {

        // We hit the controller as a guest and assert the redirection
        $this->get('admin/cms/page/99999')->assertRedirect('admin/login');
        $this->get('admin/cms/page/')->assertRedirect('admin/login');
    }

    /**
     * @group Cms
     * @test
     */
    public function an_authenticated_admin_can_create_a_page()
    {

        // We have a signed in admin
        $this->actingAs($this->admin, 'admins');
        $blocktypes = BlockType::all();
        // We have page content
        $page = factory(Page::class)->make(['title' => 'TestEntry999', 'user_id' => $this->admin->id]);
        $data = $page->toArray();
        $data['blocktypes'] = $blocktypes->pluck('id');
        // We hit the controller
        $this->json('post', 'admin/cms/page/new', $data)->assertStatus(201);

        // We assert the database entry
        $this->assertDatabaseHas('lg_cms_pages', ['title' => $page->title]);
    }

    /**
     * @group Cms
     * @test
     */
    public function an_authenticated_admin_can_update_a_page()
    {
        // We have a signed in admin
        $this->actingAs($this->admin, 'admins');

        // We have page content
        $blocktype = factory(BlockType::class)->create();
        $page = factory(Page::class)->create(['title' => 'TestEntry999', 'user_id' => $this->admin->id]);
        $page->blocktypes()->attach($blocktype);

        // We have updated data
        $newTitle = 'My TestTitel 1000';
        $newData = ['title' => $newTitle, 'is_active' => 'on', 'blocktypes' => [$blocktype->id]];

        // We hit the controller
        $this->json('patch', 'admin/cms/page/' . $page->id, $newData)->assertStatus(200);

        // We assert the database entry
        $this->assertDatabaseHas('lg_cms_pages', ['title' => $newTitle]);
    }

    /**
     * @group Cms
     * @test
     */
    public function an_authenticated_admin_can_add_blocks_to_a_page()
    {

        // We have a signed in admin
        $this->actingAs($this->admin, 'admins');

        $page = factory(Page::class)->create();
        $type = $this->createBlockTypeWithTextLineAndLink();
        $block = factory(Block::class)->make(['page_id' => $page->id, 'type_id' => $type->id]);

        // We hit the controller
        $this->json('post', 'admin/cms/page/'.$page->id.'/block/new', $block->toArray())
            ->assertStatus(201);
        $this->assertDatabaseHas('lg_cms_blocks', ['title' => $block['title']]);
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
        $this->json('patch', 'admin/cms/page/'.$page->id.'/block/'.$block->id, $newData)
            ->assertStatus(200);
        $this->assertDatabaseHas('lg_cms_blocks', ['title' => $block->title . ' - updated', 'sortnr' => $block->sortnr + 999]);
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
        $this->json('delete', 'admin/cms/page/'.$page->id.'/block/'.$block->id)
            ->assertStatus(204);
        $this->assertDatabaseMissing('lg_cms_blocks', ['id' => $block->id]);
    }

    /**
     * @group Cms
     * @test
     */
    public function an_authenticated_admin_can_destroy_a_page()
    {
        // We have a signed in admin
        $this->actingAs($this->admin, 'admins');

        // We have a page
        $page = factory(Page::class)->create(['title' => 'TestEntry999', 'user_id' => $this->admin->id]);

        // We hit the controller
        $this->json('delete', 'admin/cms/page/' . $page->id)->assertStatus(204);

        // We assert the deleted database entry
        $this->assertDatabaseMissing('lg_cms_pages', ['id' => $page->id]);
    }

    protected function createBlockTypeWithTextLineAndLink()
    {
        $blockType = factory(BlockType::class)->create([ 'title' => 'Text Mit Link']);
        $textElementType = factory(ElementType::class)->create(['title' => 'DemoTextLineElement']);
        $linkElementType = factory(ElementType::class)->create(['title' => 'DemoLinkElement']);
        factory(ElementField::class)->create(['title' => 'link_url', 'element_type_id' => $linkElementType->id]);
        factory(ElementField::class)->create(['title' => 'link_name', 'element_type_id' => $linkElementType->id]);
        factory(ElementField::class)->create(['title' => 'link_blank', 'element_type_id' => $linkElementType->id]);
        factory(ElementField::class)->create(['title' => 'content', 'element_type_id' => $textElementType->id]);

        return $blockType;
    }
}
