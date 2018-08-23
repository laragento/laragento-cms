<?php

namespace Laragento\Cms\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laragento\Admin\Helpers\AuthHelper;
use Laragento\Cms\Models\BlockType;
use Laragento\Cms\Models\Page;
use Laragento\Cms\Repositories\BlockRepositoryInterface;
use Laragento\Cms\Repositories\PageRepositoryInterface;

class PageController extends Controller
{
    protected $pageRepository;
    protected $pages;
    protected $auth;
    protected $blockRepository;
    protected $blockTypes;

    public function __construct(
        PageRepositoryInterface $pageRepository,
        BlockRepositoryInterface $blockRepository,
        AuthHelper $authHelper
    ) {
        $this->pageRepository = $pageRepository;
        $this->blockRepository = $blockRepository;
        $this->pages = $this->pageRepository->all();
        $this->blockTypes = BlockType::all();
        $this->auth = $authHelper;
    }

    /**
     * @inheritDoc
     */
    public function index()
    {
        if (!$this->auth->isRoot()) {
            abort(404);
        }
        //For the future
        $this->pages = $this->pageRepository->allByOwner();

        if (request()->expectsJson()) {
            return response()->json($this->pages, 200);
        }
        return view('cms::page.index', [
            'pages' => $this->pages
        ]);
    }

    public function show($pageSlug)
    {
        $page = $this->pageRepository->pageBySlug($pageSlug);
        if (!$page) {
            abort(404);
        }
        $blocks = $page->blocks()->orderBy('sortnr')->get();

        return view('cms::page.show', array_merge([
            'pages' => $this->pages,
            'allowedTypes' => $page->blockTypes,
            'blocktypes' => $this->blockTypes,
        ], compact(['page', 'blocks'])));
    }

    public function create()
    {
        if (!$this->auth->isRoot()) {
            abort(404);
        }

        return view('cms::page.new', [
            'pages' => $this->pages,
            'blocktypes' => $this->blockTypes
        ]);
    }


    /**
     * @inheritDoc
     */
    public function store()
    {
        if (!$this->auth->isRoot()) {
            abort(404);
        }

        $validator = $this->validatePageData();
        if ($validator->fails()) {
            $validator->getMessageBag()->add('field-error', 'Bitte prüfen Sie die Eingabefelder');
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $pageData = request()->all();
            $pageData['is_active'] = 1;
            $page = $this->pageRepository->store($pageData);

            if (request()->expectsJson()) {
                return response()->json($page, 201);
            }
            session()->flash('status', 'Seite erfolgreich erstellt');
            return redirect()->route('cms.page.edit', ['page' => $page->id]);
        }
    }

    public function edit(Page $page)
    {
        $blocks = $page->blocks->load('blockType');
        $groupedBlocks = $blocks->groupBy('blockType.title')->all();

        // For the future
        /*if ($page->user_id != Auth::guard('admins')->user()->id) {
            abort(403, 'You are not authorized to retrieve this entity.');
        }*/

        if (request()->expectsJson()) {
            return response()->json($page, 200);
        }

        return view('cms::page.edit', array_merge([
            'pages' => $this->pages,
            'allowedTypes' => $page->blockTypes,
            'blocktypes' => $this->blockTypes,
        ], compact(['page', 'groupedBlocks'])));
    }

    /**
     * @inheritDoc
     */
    public function update($pageId)
    {
        $validator = $this->validatePageData($pageId);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('field-error', 'Bitte prüfen Sie die Eingabefelder');
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $newData = request()->all();
            $newData['is_active'] == 'on' ? $newData['is_active'] = 1 : $newData['is_active'] = 0;
            $page = $this->pageRepository->store($newData, $pageId);
            if (request()->expectsJson()) {
                return response()->json($page, 200);
            }
            session()->flash('status', 'Seite erfolgreich geändert');
            return redirect()->back();
        }
    }

    /**
     * @inheritDoc
     */
    public function destroy($pageId)
    {
        if (!$this->auth->isRoot()) {
            abort(404);
        }
        $this->pageRepository->destroy($pageId);
        if (request()->expectsJson()) {
            return response()->json([], 204);
        }
        return redirect()->back();
    }

    protected function validatePageData($pageId = null)
    {
        $rules = [
            'title' => 'required|unique:lg_cms_pages,id,' . $pageId,
            'blocktypes' => 'required'
        ];

        $validator = Validator::make(request()->all(),
            $rules
        );
        return $validator;
    }

}
