<?php

namespace GeoSot\BaseAdmin\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MinifyHtml
{
    protected $enableMinification = true;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        return $this->enableMinification ? $this->minifyHtml($response) : $response;
    }

    /**
     * @param $response
     *
     * @return Response
     */
    protected function minifyHtml($response)
    {
        if ($this->isResponseObject($response) and $this->isHtmlResponse($response)) {
            $replace = $this->getReplaceStrings();

            $response->setContent(preg_replace(array_keys($replace), array_values($replace), $response->getContent()));
        }

        return $response;
    }

    protected function isResponseObject($response)
    {
        return is_object($response) && $response instanceof Response;
    }

    protected function isHtmlResponse(Response $response)
    {
        $type = $response->headers->get('Content-Type');

        return strtolower(strtok($type, ';')) === 'text/html';
    }

    /**
     * @return array
     */
    protected function getReplaceStrings(): array
    {
        return [
            //                        '/(\r?\n)/'                                           => '',   // Collapse new lines
            //   '/(\s)+/s'=> '\\1',         // shorten multiple whitespace sequences
            '/\>[^\S ]+/s'              => '>', // strip whitespaces after tags, except space
            '/<!--[^\[](.*?)[^\]]-->/s' => '',
            '/[^\S ]+\</s'              => '<', // strip whitespaces before tags, except space
            "/\n([\S])/"                => ' $1',
            '/<!--(.|\s)*?-->/'         => '', // Remove HTML comments
            //            '/<!--[^]><!\[](.*?)[^\]]-->/s'                       => '',// Remove HTML comments
            '/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s' => '',  // Remove HTML comments except IE conditions
            "/\r/"                                                => ' ',
            '/(?<!\S)\/\/\s*[^\r\n]*/'                            => '', // Remove comments in the form /* */
            '/([\t ])+/s'                                         => ' ',
            '/^([\t ])+/m'                                        => '',
            '/([\t ])+$/m'                                        => '',
            '~//[a-zA-Z0-9 ]+$~m'                                 => '',
            '/[\r\n]+([\t ]?[\r\n]+)+/s'                          => "\n",
            '/\>[\r\n\t ]+\</s'                                   => '><',
            '/}[\r\n\t ]+/s'                                      => '}',
            '/}[\r\n\t ]+,[\r\n\t ]+/s'                           => '},',
            '/\)[\r\n\t ]?{[\r\n\t ]+/s'                          => '){',
            '/,[\r\n\t ]?{[\r\n\t ]+/s'                           => ',{',
            '/\),[\r\n\t ]+/s'                                    => '),',
            //            '~([\r\n\t ])?([a-zA-Z0-9]+)=\"([a-zA-Z0-9_\\-]+)\"([\r\n\t ])?~s' => '$1$2=$3$4',
        ];
    }
}
