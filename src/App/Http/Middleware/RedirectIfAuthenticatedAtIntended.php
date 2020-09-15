<?php

namespace GeoSot\BaseAdmin\App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class RedirectIfAuthenticatedAtIntended
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        if (Auth::guard($guard)->check()) {

            //LaravelLocalization::setLocale(\auth()->user()->preferredLocale());
            $defaultPath = $this->getDefaultUserPath();

            return (in_array(session()->get('url.intended', '/'), $this->getAvailableHomeRoutes())) ? redirect()->to($defaultPath) : redirect()->intended($defaultPath);

        }

        return $next($request);
    }


    /**
     * @return array
     */
    protected function getAvailableHomeRoutes()
    {
        return array_map(function ($i) {
            return config('app.url') . '/' . $i;
        }, array_merge(LaravelLocalization::getSupportedLanguagesKeys(), ['']));
    }

    /**
     * @return string
     */
    protected function getDefaultUserPath(): string
    {
        $defaultPath = route('home');
        if (Auth::user()->can('admin.*')) {
            $defaultPath = route('admin.dashboard');
        }

        return $defaultPath;
    }
}
