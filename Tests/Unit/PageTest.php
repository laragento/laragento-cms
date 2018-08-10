<?php


namespace Laragento\Cms\Tests\Unit;


use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laragento\Cms\Models\BlockType;
use Laragento\Cms\Models\Page;
use Laragento\Cms\Tests\CmsTestCase;

class PageTest extends CmsTestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_has_a_block_type()
    {
        $types = factory(BlockType::class,2)->create();
        $page = factory(Page::class)->create();
        $page->blockTypes()->attach($types);
        $this->assertEquals($types->pluck('id'), $page->blocktypes()->pluck('id'));

    }
}