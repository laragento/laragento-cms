<?php

namespace Laragento\Cms\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Laragento\Cms\Helpers\FileHelper;
use Laragento\Cms\Models\Block;
use Laragento\Cms\Models\BlockType;
use Laragento\Cms\Models\Page;
use Laragento\Cms\Repositories\BlockRepositoryInterface;
use Laragento\Cms\Repositories\PageRepositoryInterface;

class BlockController extends Controller
{
    protected $blockRepository;
    protected $fileHelper;
    protected $pageRepository;
    protected $pages;

    public function __construct(
        BlockRepositoryInterface $blockRepository,
        PageRepositoryInterface $pageRepository,
        FileHelper $filehelper
    ) {
        $this->blockRepository = $blockRepository;
        $this->pageRepository = $pageRepository;
        $this->pages = $this->pageRepository->all();
        $this->fileHelper = $filehelper;

    }

    /**
     * @inheritDoc
     */
    public function index()
    {
        $blocks = $this->blockRepository->allByOwner();

        if (request()->expectsJson()) {
            return response()->json($blocks, 200);
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function create(Page $page)
    {

        if (request()->method() == 'GET') {
            return redirect(route('cms.page.edit', ['page' => $page->id]))->withInput();
        }
        $typeId = request()->get('block_type');
        if (!$typeId) {
            session()->flash('error', 'No Type given');
            return back()->withInput();
        }
        $type = BlockType::find($typeId);
        return view('cms::block.new', compact('page', 'type'))->with([
            'pages' => $this->pages
        ]);
    }

    /**
     * @inheritdoc
     */
    public function edit(Page $page, Block $block)
    {

        // for the future
        /*if ($block->page->user_id != Auth::guard('admins')->user()->id) {
            abort(403, 'You are not authorized to retrieve this entity.');
        }*/
        if (request()->expectsJson()) {
            return response()->json($block, 200);
        }

        return view('cms::block.edit', compact(['block', 'page']))
            ->with([
                'type' => $block->blockType,
                'pages' => $this->pages
            ]);
    }


    /**
     * @inheritDoc
     */
    public function store(Page $page)
    {
        $validator = $this->validateBlockData();
        if ($validator->fails()) {
            $validator->getMessageBag()->add('field-error', 'Bitte prüfen Sie die Eingabefelder');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $data = $this->retrieveData();
            $block = $this->blockRepository->store($data['data']);
            if ($block && $data['files'] && count($data['files']) > 0) {
                $files = $this->handleUploadedFiles($block, $data['files']);
                $block->files()->saveMany($files);
            }

            if (request()->expectsJson()) {
                return response()->json($block, 201);
            }
            session()->flash('status', 'Inhalt erfolgreich erstellt');
            return redirect(route('cms.block.edit', [
                'block' => $block->id,
                'page' => $page->id,
            ]))->with([
                'type' => $block->blockType->title
            ]);
        }

    }

    /**
     * @inheritDoc
     */
    public function update(Page $page, Block $block)
    {
        $validator = $this->validateBlockData($block->id);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('field-error', 'Bitte prüfen Sie die Eingabefelder');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $data = $this->retrieveData();
            $block = $this->blockRepository->store($data['data'], $block->id);

            if (request()->expectsJson()) {
                return response()->json($block->fresh(), 200);
            }
            session()->flash('status', 'Inhalt erfolgreich geändert');
            return redirect(route('cms.block.edit', [
                'block' => $block->id,
                'page' => $page->id,
            ]))->with([
                'type' => $block->blockType
            ]);
        }
    }

    /**
     * @inheritDoc
     */
    public function updateContent(Page $page, Block $block)
    {

            $data = $this->retrieveData();
            $this->blockRepository->storeValues($data['data']);
            //ToDo Missing File handling

            if (request()->expectsJson()) {
                return response()->json($block->fresh(), 200);
            }

            session()->flash('status', 'Inhalt erfolgreich geändert');
            return redirect(route('cms.block.edit', [
                'blockId' => $block->id,
                'pageId' => $page->id,
            ]))->with([
                'type' => $block->type->title
            ]);
    }

    /**
     * @inheritDoc
     */
    public function destroy(Page $page, Block $block)
    {
        $block->delete();
        if (request()->expectsJson()) {
            return response()->json([], 204);
        }
        return redirect(route('cms.page.edit', ['page' => $page]));
    }

    /**
     * @param $pageId
     * @return array
     */
    protected function retrieveData(): array
    {
        $data = [];
        $data['data'] = request()->all();
        $data['data']['meta']['page_id'] = request()->route('page')->id;
        $data['files'] = request()->file();

        return $data;
    }

    protected function validateBlockData($blockId = null)
    {
        $rules = [
            'meta.title' => 'required|unique:lg_cms_blocks,id,' . $blockId,
            'meta.type_id' => 'required'
        ];
        $validator = Validator::make(request()->all(),
            $rules
        );
        return $validator;
    }

    protected function handleUploadedFiles($block, $files)
    {
        $type = ElementType::whereTitle('file')->first();
        $fileData = [
            'store_id' => 1,
            'block_id' => $block->id,
            'type_id' => $type->id,
            'name' => 'todo'
        ];
        $path = $this->fileHelper->preparePath('block/' . $block->type->title);
        foreach ($files as $file) {
            $this->fileHelper->storeUploadedFile($file, $path);
            $fileData['value'] = substr($path, -33) . '/' . $file->getClientOriginalName();
            $fileEntities[] = ElementFile::create($fileData);
        }
        return $fileEntities;
    }

    /**
     * @param $validator
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectWithErrors($validator, $page)
    {

    }

}
