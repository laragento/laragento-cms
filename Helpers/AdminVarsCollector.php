<?php


namespace Laragento\Cms\Helpers;


class AdminVarsCollector
{
    protected $pageRepository;

    public function __construct()
    {

        $this->pageRepository = app()->make('Laragento\Cms\Repositories\PageRepositoryInterface');
    }

    /**
     * @return array
     */
    public function collect()
    {
        $pages = $this->pageRepository->all();

        return [
            'pages' => $pages
        ];
    }
}