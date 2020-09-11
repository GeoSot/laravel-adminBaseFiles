<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use GeoSot\BaseAdmin\App\Jobs\CompressImage;
use GeoSot\BaseAdmin\App\Models\Media\MediumImage;


class DashboardController extends Controller
{
    public function index()
    {
        $this->testOnLocal();

        return view('baseAdmin::admin.index');
    }

    protected function testOnLocal()
    {
        if (!app()->environment('local')) {
            return;
        }


        //
        //        $oClient = new Client(config('imap.accounts.default'));
        //        $folder  = $oClient->getFolder(config('mail.tickets.mailFolders.inbox'));
        //       dump($folder->getMessages());
        //

        //CleanNotificationsTable::dispatch()
        //  GetNewEmailsFromServer::dispatch();
        // User::first()->notify(new TicketNewMessageCustomer(Ticket::first(), 'newTicketToCustomer'));
    }

    public function choosePage()
    {
        return view('admin.chooseBackOrFront');
    }
}

