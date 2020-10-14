<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Site;


use App\Forms\Site\ContactForm;
use App\Http\Controllers\Controller;
use GeoSot\BaseAdmin\Helpers\Alert;
use Illuminate\Http\Response;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class HomeController extends Controller
{
    use FormBuilderTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //  $this->middleware('auth')->only('index');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        return view('baseAdmin::site.index');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function contactUs()
    {
        $form = $this->form(ContactForm::class);
        $comingFromUrl = url()->previous().'#'.$form->getFormOption('id');
        $form->redirectIfNotValid($comingFromUrl);
        Alert::info('Need to Handle message');


        return redirect($comingFromUrl);
    }
}
