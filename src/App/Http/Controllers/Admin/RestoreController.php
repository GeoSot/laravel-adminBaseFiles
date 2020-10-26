<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin;

use GeoSot\BaseAdmin\App\Models\BaseModel;
use GeoSot\BaseAdmin\Helpers\Alert;
use Illuminate\Http\Request;
use Venturecraft\Revisionable\Revision;
use Venturecraft\Revisionable\RevisionableTrait;

class RestoreController extends BaseAdminController
{
    public function restoreHistory(Request $request, Revision $revision)
    {
        /** @var BaseModel $modelToRestore */
        $modelToRestore = $revision->historyOf();;

        if (!$request->user()->hasPermission('admin.restore-'.lcfirst(class_basename($modelToRestore)))) {
            Alert::error(__('baseAdmin::admin/generic.messages.crud.restore.deny'), __('baseAdmin::admin/generic.messages.crud.restore.errorTitle'))->typeToast();
            return redirect()->back();
        }
        $modelToRestore->disableRevisionField($revision->fieldName());

        $modelToRestore->update([$revision->fieldName() => $revision->oldValue()]);
//        $revision->delete();

        return $this->checksAndNotificationsAfterSave($modelToRestore, $request, 'restore');

    }

    public function clearHistory(Request $request, Revision $revision)
    {
        /** @var BaseModel $modelToRestore */
        $modelToRestore = $revision->historyOf();
        if (!$request->user()->hasPermission('admin.restore-'.lcfirst(class_basename($modelToRestore)))) {
            Alert::error(__('baseAdmin::admin/generic.messages.crud.restore.deny'), __('baseAdmin::admin/generic.messages.crud.restore.errorTitle'))->typeToast();
            return redirect()->back();
        }
        /** @var RevisionableTrait $modelToRestore */
        $modelToRestore->revisionHistory()->delete();
        return $this->checksAndNotificationsAfterSave($modelToRestore, $request, 'restoreClear');


    }
}

