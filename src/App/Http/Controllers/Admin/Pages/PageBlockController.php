<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Pages;

use App\Models\Media\Medium;
use App\Models\Pages\PageBlock;
use GeoSot\BaseAdmin\App\Helpers\Http\Controllers\Filter;
use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PageBlockController extends BaseAdminController
{

    protected $_class = PageBlock::class;

    /**
     * Show the form for editing the specified resource.
     *
     * @param  PageBlock  $pageBlock
     *
     * @return Response
     */
    public function edit(PageBlock $pageBlock)
    {
        return $this->genericEdit($pageBlock);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  PageBlock  $pageBlock
     *
     * @return Response
     */
    public function update(Request $request, PageBlock $pageBlock)
    {
        return $this->genericUpdate($request, $pageBlock);
    }

    protected function listFields(): array //Can be omitted
    {
        return [
            'listable' => ['title', 'pageArea.slug', 'order', 'has_multiple_images', 'is_enabled', 'id'],
            'searchable' => ['title', 'is_enabled', 'id'],
        ];
    }

    protected function filters(): array
    {
        return [
            Filter::boolean('has_multiple_images'),
            Filter::selectMulti('pageArea.slug'),
        ];
    }

    protected function afterSave(Request &$request, $model)
    {
        /* @var PageBlock $model */
        $model->syncRequestMedia($request, false, Medium::REQUEST_FIELD_NAME__IMAGE);
    }

}
