<?php
/**
 * by GeoSv.
 * User: gsoti
 * Date: 16/7/2018
 * Time: 10:36 πμ
 */

namespace GeoSot\BaseAdmin\App\Traits\Eloquent;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;

trait HasFrontEndConfigs
{
    /**
     * @return array
     */
    public function getDefaultFrontEndConfigValues()
    {
        $shortClassName = class_basename($this);
        $namespace = ltrim(str_replace('App\\Models', '', (new ReflectionClass($this))->getNamespaceName()), '\\');
        $namespace_low = lcfirst($namespace);
        $singular = lcfirst($shortClassName);
        $plural = Str::plural($singular);
        $routePrefix = ($namespace_low == $plural or !$namespace_low) ? '' : $namespace_low.'.';
        $routeSuffix = Str::plural(lcfirst(Str::plural($shortClassName) == $namespace ? $shortClassName : str_replace(Str::singular($namespace), '', $shortClassName)));

        $base = [
            'langDir' => (($namespace_low) ? $namespace_low.'/' : '').$singular,
            'viewDir' => (($namespace_low) ? $namespace_low.'.' : '').$plural,
            'route'   => $routePrefix.$routeSuffix,
        ];

        return [
            'admin' => $base,
            'site'  => $base,
        ];
    }

    /**
     * Gets the model Configuration Strings.
     *
     * @param string      $side
     * @param string|null $arg
     *
     * @return bool|Collection|mixed
     */
    public function getFrontEndConfig(string $side = 'admin', string $arg = null)
    {
//        $frontEndConfigValues = $this->frontEndConfigValues ?? [];
//        foreach ($this->getDefaultFrontEndConfigValues() as $key => $item) {
//            $frontEndConfigValues[$key] = array_merge($this->getDefaultFrontEndConfigValues() [$key], Arr::get($frontEndConfigValues, $key, []));
//        }
        $frontEndConfigValues = array_replace_recursive($this->getDefaultFrontEndConfigValues(), $this->frontEndConfigValues ?? []);
        $collection = collect(array_merge(Arr::get($frontEndConfigValues, $side, []), ['model' => class_basename($this), 'fullModel' => get_class($this)]));

        return ($arg) ? $collection->get($arg) : $collection;
    }

    /**
     * @param string      $side
     * @param string|null $arg
     *
     * @return bool|Collection|mixed
     */
    public function getFrontEndConfigPrefixed(string $side = 'admin', string $arg = null)
    {
        $collection = $this->getFrontEndConfig($side)->map(function ($item, $key) use ($side) {
            if ($key == 'langDir') {
                return $side.'/'.$item;
            }
            if (in_array($key, ['viewDir', 'route'])) {
                return $side.'.'.$item;
            }

            return $item;
        });

        return ($arg) ? $collection->get($arg) : $collection;
    }

    /**
     * @param string $linkTitle
     * @param bool   $appendAsString
     * @param array  $options
     *
     * @return string
     */
    public function getDashBoardLink(string $linkTitle = 'id', bool $appendAsString = false, $options = [])
    {
        return $this->getLink($linkTitle, $appendAsString, 'admin', $options);
    }

    /**
     * @param string $linkTitle
     * @param bool   $appendAsString
     *
     * @return string
     */
    public function getSiteLink(string $linkTitle = 'id', bool $appendAsString = false)
    {
        return $this->getLink($linkTitle, $appendAsString, 'site');
    }

    /**
     * @param string $linkTitle
     * @param bool   $appendAsString
     * @param string $side
     * @param array  $options
     *
     * @return string
     */
    public function getLink(string $linkTitle = 'id', bool $appendAsString = false, $side = 'admin', $options = [])
    {
        $text = $this->{$linkTitle};

        if ($appendAsString or is_null($text)) {
            $text = $linkTitle;
        }
        $flatClasses = array_map(function ($key, $value) {
            return $key.'="'.$value.'"';
        }, array_keys($options), $options);

        $action = $this->exists ? 'edit' : 'create';

        return '<a  href="'.route($this->getFrontEndConfigPrefixed($side, 'route').".{$action}", $this).'" '.implode('   ', $flatClasses).'>'.$text.'</a>';
    }
}
