<?php


namespace Laragento\Cms\Repositories;


use Illuminate\Support\Facades\Auth;
use Laragento\Cms\Models\Page;

class PageRepository extends AbstractContentRepository implements PageRepositoryInterface
{
    public function allByOwner()
    {
       return Page::whereUserId(Auth::guard('admins')->user()->id)->get();
    }

    public function all()
    {
        return Page::all();
    }

    public function first($identifier)
    {
        return Page::whereId($identifier)->first();
    }


    /**
     * @inheritDoc
     */
    public function store($data, $id = null)
    {
        if (!isset($data['user_id'])) {
            $data['user_id'] = Auth::user()->id;
        }
        $data['slug'] = str_slug($data['title']);
        if (!$id) {
            $page = Page::create($data);
        } else {
            $page = Page::whereId($id)->first();
            $page->update($data);
        }
        $page->blockTypes()->sync($data['blocktypes']);
        return $page;
    }

    public function pageBySlug($slug)
    {
        return Page::whereSlug($slug)->first();
    }

    public function destroy($id)
    {
        Page::destroy($id);
    }

}