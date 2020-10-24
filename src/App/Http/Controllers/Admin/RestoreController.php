<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use GeoSot\BaseAdmin\Helpers\Alert;
use Illuminate\Http\Request;
use Venturecraft\Revisionable\Revision;

class RestoreController extends Controller
{
    public function restore(Request $request, Revision $revision)
    {
        /** @var BaseModel $modelToRestore */
        $modelToRestore = $revision->historyOf();;

        if (!$request->user()->hasPermission('admin.restore-'.lcfirst(class_basename($modelToRestore)))) {
            Alert::error(__('baseAdmin::admin/generic.messages.crud.restore.deny'), __('baseAdmin::admin/generic.messages.crud.restore.errorTitle'))->typeToast();
            return redirect()->back();
        }
        $modelToRestore->disableRevisionField($revision->fieldName());

        $modelToRestore->update([$revision->fieldName() => $revision->oldValue()]);
        $revision->delete();

        Alert::success(trans_choice('baseAdmin::admin/generic.messages.crud.restore.successMsg', 1),
            __('baseAdmin::admin/generic.messages.crud.restore.successTitle'))->typeToast();

        return redirect()->back();
    }


}

