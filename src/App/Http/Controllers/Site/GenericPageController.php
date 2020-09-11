<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Site;


use App\Http\Controllers\Controller;
use App\Models\Pages\Page;

class GenericPageController extends Controller
{
    //
    public function show(Page $page)
    {
        $page->load('pageAreas.blocks');
        return view('site._includes.blockLayouts.genericPage', compact('page'));
    }
}
