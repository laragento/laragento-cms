<?php

namespace Laragento\Cms\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laragento\Admin\Helpers\AuthHelper;
use Laragento\Cms\Models\Block;
use Laragento\Cms\Models\BlockType;
use Laragento\Cms\Models\Element\Element;
use Laragento\Cms\Models\Element\ElementType;
use Laragento\Cms\Models\Page;
use Laragento\Cms\Repositories\BlockRepository;
use Laragento\Cms\Repositories\BlockTypeRepository;
use Laragento\Cms\Repositories\PageRepository;

class BlockTypeController extends Controller
{

    protected $auth;
    protected $blockTypes;
    protected $blockTypeRepository;

    public function __construct(BlockTypeRepository $blockTypeRepository, AuthHelper $authHelper)
    {
        $this->blockTypeRepository = $blockTypeRepository;
        $this->blockTypes = $this->blockTypeRepository->all();
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

        if (request()->expectsJson()) {
            return response()->json($this->blockTypes, 200);
        }
        return view('cms::blocktype.index', [
            'blocktypes' => $this->blockTypes
        ]);
    }

    public function create()
    {
        if (!$this->auth->isRoot()) {
            abort(404);
        }

        return view('cms::blocktype.new', [

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
        $validator = $this->validateBlockTypeData();
        if ($validator->fails()) {
            $validator->getMessageBag()->add('field-error', 'Bitte prüfen Sie die Eingabefelder');
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $blockTypeData = request()->all();
            $blockType = $this->blockTypeRepository->store($blockTypeData);

            if (request()->expectsJson()) {
                return response()->json($blockType, 201);
            }
            session()->flash('status', 'Block-Typ erfolgreich erstellt');
            return redirect()->route('cms.blocktype.edit', ['blocktype' => $blockType->id]);
        }
    }

    public function edit(BlockType $blockType)
    {
        if (request()->expectsJson()) {
            return response()->json($blockType, 200);
        }
        return view('cms::blocktype.edit', [
            'blocktype' => $blockType,
            'elementtypes' => ElementType::all()
        ]);
    }

    /**
     * @inheritDoc
     */
    public function update(BlockType $blockType)
    {
        $validator = $this->validateBlockTypeData();
        if ($validator->fails()) {
            $validator->getMessageBag()->add('field-error', 'Bitte prüfen Sie die Eingabefelder');
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $newData = request()->all();
            $blockType = $this->blockTypeRepository->store($newData, $blockType->id);
            if (request()->expectsJson()) {
                return response()->json($blockType, 200);
            }
            session()->flash('status', 'Block Typ erfolgreich geändert');
            return redirect()->back();
        }
    }

    /**
     * @inheritDoc
     */
    public function destroy(BlockType $blockType)
    {
        if (!$this->auth->isRoot()) {
            abort(404);
        }
        if (count($blockType->blocks) > 0) {
            abort(400, 'blocks_present');
        }
        $this->blockTypeRepository->destroy($blockType->id);
        if (request()->expectsJson()) {
            return response()->json([], 204);
        }
        return redirect()->back();
    }

    public function addElement(BlockType $blockType)
    {
        $data = request()->all();
        $blockType->elements()->save(new Element([
            'title' => $data['title'],
            'element_type_id' => $data['element_type_id'],
            'sort_nr' => 1,
        ]));
        if (request()->expectsJson()) {
            return response()->json($blockType, 201);
        }
        return redirect()->back();
    }

    public function updateElement(Element $element)
    {
        $data = request()->all();
        $element->update($data);
        if (request()->expectsJson()) {
            return response()->json($element, 200);
        }
        return redirect()->back();
    }

    /**
     * @param Element $element
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function removeElement(Element $element)
    {
        $element->delete();
        if (request()->expectsJson()) {
            return response()->json([], 204);
        }
        return redirect()->back();
    }

    protected function validateBlockTypeData($blockTypeId = null)
    {

        $rules = [
            'title' => 'required|unique:lg_cms_block_types,id,' . $blockTypeId
        ];
        $validator = Validator::make(request()->all(),
            $rules
        );

        return $validator;
    }

}
