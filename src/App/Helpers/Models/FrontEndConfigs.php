<?php


namespace GeoSot\BaseAdmin\App\Helpers\Models;


use GeoSot\BaseAdmin\Helpers\Base;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class FrontEndConfigs
{
    public const ADMIN = 'admin';
    public const SITE = 'site';

    /**
     * @var Model
     */
    private $model;

    /**
     * @var array
     */
    private $configOverrides = [];

    public function __construct(Model $model, array $configOverrides = [])
    {
        $this->model = $model;
        $this->configOverrides = $configOverrides;
    }

    /**
     * @return array
     */
    private function getDefaultFrontEndConfigValues()
    {

        $shortClassName = class_basename($this->model->getMorphClass());
        $namespace = ltrim(str_replace('App\\Models', '', (new \ReflectionClass($this->model))->getNamespaceName()), '\\');
        $namespaceLow = lcfirst($namespace);
        $singular = lcfirst($shortClassName);
        $plural = Str::plural($singular);
        $routePrefix = ($namespaceLow == $plural or !$namespaceLow) ? '' : $namespaceLow . '.';
        $routeSuffix = Str::plural(lcfirst(Str::plural($shortClassName) == $namespace ? $shortClassName : str_replace(Str::singular($namespace), '', $shortClassName)));

        $base = [
            'langDir' => (($namespaceLow) ? $namespaceLow . '/' : '') . $singular,
            'viewDir' => (($namespaceLow) ? $namespaceLow . '.' : '') . $plural,
            'route' => $routePrefix . $routeSuffix,
        ];

        return [
            'admin' => $base,
            'site' => $base,
        ];

    }

    /**
     * Gets the model Configuration Strings
     *
     * @param string $side
     * @param string|null $arg
     *
     * @return bool|Collection|mixed
     */
    private function getFrontEndConfig(string $side = 'admin', string $arg = null)
    {
//        $frontEndConfigValues = $this->configOverrides ?? [];
//        foreach ($this->getDefaultFrontEndConfigValues() as $key => $item) {
//            $frontEndConfigValues[$key] = array_merge($this->getDefaultFrontEndConfigValues() [$key], Arr::get($frontEndConfigValues, $key, []));
//        }
        $frontEndConfigValues = array_replace_recursive($this->getDefaultFrontEndConfigValues(), $this->configOverrides ?? []);

        $collection = collect(array_merge(Arr::get($frontEndConfigValues, $side, []), ['model' => class_basename($this), 'fullModel' => get_class($this)]));

        return ($arg) ? $collection->get($arg) : $collection;
    }

    /**
     * @param string $side
     * @param string|null $arg
     *
     * @return bool|Collection|mixed
     */
    public function getValue(string $side = 'admin', string $arg = null)
    {
        $collection = $this->getFrontEndConfig($side)->map(function ($item, $key) use ($side) {
            if ($key == 'langDir') {
                return $side . '/' . $item;
            }
            if (in_array($key, ['viewDir', 'route'])) {
                return $side . '.' . $item;
            }
            return $item;

        });

        return ($arg) ? $collection->get($arg) : $collection;
    }

    /**
     * @param string $title
     * @param bool $appendAsString
     * @param array $options
     *
     * @return string
     */
    public function getAdminLink(string $title = 'id', bool $appendAsString = false, array $options = [])
    {
        return $this->makeLink($title, $appendAsString, self::ADMIN, $options);

    }

    /**
     * @param string $title
     * @param bool $appendAsString
     *
     * @return string
     */
    public function getLink(string $title = 'id', bool $appendAsString = false, array $options = [])
    {
        return $this->makeLink($title, $appendAsString, self::SITE, $options);
    }

    /**
     * if $appendAsString === true, $title will be used as sting to show, if $appendAsString === false, we will search for a property with the given key to use
     * @param string $title
     * @param bool $appendAsString
     * @param string $side
     * @param array $attrs
     *
     * @return string
     */
    private function makeLink(string $title = 'id', bool $appendAsString = false, $side = self::ADMIN, $attrs = [])
    {
        $text = $this->model->getAttribute($title)/*->{$title}*/
        ;

        if ($appendAsString || is_null($text)) {
            $text = $title;
        }
        $flatClasses = array_map(function ($key, $value) {
            return $key . '="' . $value . '"';
        }, array_keys($attrs), $attrs);

        $action = $this->model->exists ? 'edit' : 'create';
        return '<a  href="' . $this->getRoute($action, $side) . '" ' . implode('   ', $flatClasses) . '>' . $text . '</a>';
    }

    /**
     * @param string $side
     * @param string $action
     * @return string
     */
    public function getRoute(string $action, string $side = self::ADMIN): string
    {
        $options = in_array($action, ['edit', 'update']) ? $this->model : [];


        return route($this->getRouteDir($side) . ".{$action}", $options);
    }

    /**
     * @param string $side
     * @param string $text
     * @return string
     */
    public function trans(string $text, string $side = self::ADMIN): string
    {
        return trans(Base::addPackagePrefix($this->getLangDir($side) . $text));
    }


    /**
     * @param string $side
     * @return string
     */
    public function getRouteDir(string $side = self::ADMIN): string
    {
        return $this->getValue($side, 'route');
    }

    /**
     * @param string $side
     * @return string
     */
    public function getLangDir(string $side = self::ADMIN): string
    {
        return $this->getValue($side, 'langDir');
    }

    /**
     * @param string $side
     * @return string
     */
    public function getViewDir(string $side = self::ADMIN): string
    {
        return $this->getValue($side, 'viewDir');
    }

}
