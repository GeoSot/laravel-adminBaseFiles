<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Site;


use App\Http\Controllers\Controller;
use Exception;
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
        throw new Exception('Form Is not handled');
        /*  $form = $this->form(ContactForm::class);
          $comingFromUrl = url()->previous().'#'.$form->getFormOption('id');
          $form->redirectIfNotValid($comingFromUrl);


          return redirect($comingFromUrl);*/
    }
}
