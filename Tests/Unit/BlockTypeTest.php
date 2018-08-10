<?php


namespace Laragento\Cms\Tests\Unit;


use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laragento\Cms\Models\BlockType;
use Laragento\Cms\Models\Page;
use Laragento\Cms\Tests\CmsTestCase;

class BlockTypeTest extends CmsTestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_has_a_page()
    {

        $pages = factory(Page::class,2)->create();
        $type = factory(BlockType::class)->create();
        $type->pages()->attach($pages);
        $this->assertEquals($pages->pluck('id'), $type->pages()->pluck('id'));

    }
}