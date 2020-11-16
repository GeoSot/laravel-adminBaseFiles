<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Site;


use App\Models\Pages\Page;

class GenericPageController extends BaseFrontController
{


    protected $_class = Page::class;


    //
    public function show(Page $page)
    {
        if (!$page->userCanSee()) {
            return abort(404);
        }


        $page->with([
            'pageAreas' => function ($q) {
                $q->enabled();
            }
        ], [
            'pageAreas.blocks' => function ($q) {
                $q->enabled()->active();
            }
        ]);

        return view('baseAdmin::site.blockLayouts.genericPage', ['record'=>$page]);
    }
}
