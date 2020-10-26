<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Pages;

use App\Models\Media\Medium;
use App\Models\Pages\PageArea;
use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PageAreaController extends BaseAdminController
{
    protected $_class = PageArea::class;

    /**
     * Show the form for editing the specified resource.
     *
     * @param PageArea $pageArea
     *
     * @return Response
     */
    public function edit(PageArea $pageArea)
    {
        return $this->genericEdit($pageArea);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request  $request
     * @param PageArea $pageArea
     *
     * @return Response
     */
    public function update(Request $request, PageArea $pageArea)
    {
        return $this->genericUpdate($request, $pageArea);
    }

    protected function listFields()//Can be omitted
    {
        $newFields = [
            'listable'   => ['title', 'page.slug', 'order', 'blocks.slug', 'enabled', 'id'],
            'searchable' => ['title', 'enabled', 'id'],
        ];

        return array_merge(parent::listFields(), $newFields);
    }

    protected function filters()
    {
        return [
            'page.slug' => ['type' => 'multiSelect'],
        ];
    }

    protected function afterSave(Request &$request, $model)
    {
        /* @var PageArea $model */
        $model->syncRequestMedia($request, true, Medium::REQUEST_FIELD_NAME__IMAGE);
    }
}
