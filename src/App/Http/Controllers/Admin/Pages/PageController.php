<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Pages;

use App\Models\Media\Medium;
use App\Models\Pages\Page;
use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PageController extends BaseAdminController
{
    protected $_class = Page::class;

    //OVERRIDES
    protected $allowedActionsOnEdit = ['save', 'saveAndClose', 'saveAndNew'];

    /**
     * Show the form for editing the specified resource.
     *
     * @param Page $page
     *
     * @return Response
     */
    public function edit(Page $page)
    {
        $page->load('pageAreas.blocks');

        return $this->genericEdit($page);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Page    $page
     *
     * @return Response
     */
    public function update(Request $request, Page $page)
    {
        return $this->genericUpdate($request, $page);
    }

    protected function listFields()//Can be omitted
    {
        $newFields = [
            'listable'   => ['title', 'slug', 'parentPage.title', 'childrenPages.title', 'enabled', 'id'],
            'searchable' => ['title', 'enabled', 'id'],
        ];

        return array_merge(parent::listFields(), $newFields);
    }

    protected function filters()
    {
        return [
            'parentPage.title' => ['type' => 'multiSelect'],
        ];
    }

    protected function afterSave(Request &$request, $model)
    {
        /* @var Page $model */
        $model->syncRequestMedia($request, true, Medium::REQUEST_FIELD_NAME__IMAGE);
    }
}
