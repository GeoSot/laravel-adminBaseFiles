<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

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



        //CleanNotificationsTable::dispatch()
        // User::first()->notify(new TicketNewMessageCustomer(Ticket::first(), 'newTicketToCustomer'));
    }

    public function choosePage()
    {
        return view('admin.chooseBackOrFront');
    }
}

