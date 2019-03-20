<?php
/**
 * Artified by GeoSv.
 * User: gsoti
 * Date: 16/7/2018
 * Time: 10:36 πμ
 */

namespace GeoSot\BaseAdmin\App\Traits\Eloquent;


use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait HasFrontEndConfigs
{
    /**
     * @return array
     * @throws \ReflectionException
     */
    public function getDefaultFrontEndConfigValues()
    {
        $shortClassName = class_basename($this);
        $namespace      = str_replace('App\\Models\\', '', (new \ReflectionClass($this))->getNamespaceName());
        $namespace_low  = lcfirst($namespace);
        $singular       = lcfirst($shortClassName);
        $plural         = Str::plural($singular);
        $routePrefix    = ($namespace_low == $plural) ? '' : $namespace_low . '.';
        $routeSuffix    = Str::plural(lcfirst(Str::plural($shortClassName) == $namespace ? $shortClassName : str_replace(Str::singular($namespace), '', $shortClassName)));

        return [
            'admin' => [
                'langDir' => $namespace_low . '/' . $singular,
                'viewDir' => $namespace_low . '.' . $plural,
                'route'   => $routePrefix . $routeSuffix,
            ],
            'site'  => [
                'langDir' => $namespace_low . '/' . $singular,
                'viewDir' => $namespace_low . '.' . $plural,
                'route'   => $routePrefix . $routeSuffix,
            ],
        ];

    }

    /**
     * Gets the model Configuration Strings
     *
     * @param string      $side
     * @param string|null $arg
     *
     * @return bool|\Illuminate\Support\Collection|mixed
     * @throws \ReflectionException
     */
    public function getFrontEndConfig(string $side = 'admin', string $arg = null)
    {
        if (!in_array($side, ['admin', 'site'])) {
            return false;
        }
        $frontEndConfigValues = (property_exists($this, 'frontEndConfigValues') and isset($this->frontEndConfigValues)) ? $this->frontEndConfigValues : [];
        foreach ($this->getDefaultFrontEndConfigValues() as $key => $item) {
            $frontEndConfigValues[$key] = array_merge($this->getDefaultFrontEndConfigValues() [$key], Arr::get($frontEndConfigValues, $key, []));
        }
        $collection = collect(array_merge($frontEndConfigValues[$side], ['model' => class_basename($this), 'fullModel' => get_class($this)]));

        return ($arg) ? $collection->get($arg) : $collection;
    }

    /**
     * @param string      $side
     * @param string|null $arg
     *
     * @return bool|\Illuminate\Support\Collection|mixed
     * @throws \ReflectionException
     */
    public function getFrontEndConfigPrefixed(string $side = 'admin', string $arg = null)
    {
        if (!in_array($side, ['admin', 'site'])) {
            return false;
        }

        $collection = $this->getFrontEndConfig($side)->map(function ($item, $key) use ($side) {

            switch ($key) {
                case 'langDir':
                    return $side . '/' . $item;
                    break;
                case 'viewDir':
                    return $side . '.' . $item;
                    break;
                case 'route':
                    return $side . '.' . $item;
                    break;
                default:
                    return $item;
            }
        });

        return ($arg) ? $collection->get($arg) : $collection;
    }

    /**
     * @param string $linkTitle
     * @param bool   $appendAsString
     * @param array  $options
     *
     * @return string
     * @throws \ReflectionException
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
     * @throws \ReflectionException
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
     * @throws \ReflectionException
     */
    public function getLink(string $linkTitle = 'id', bool $appendAsString = false, $side = 'admin', $options = [])
    {
        $text = $this->{$linkTitle};

        if ($appendAsString or is_null($text)) {
            $text = $linkTitle;
        }
        $flatClasses = array_map(function ($key, $value) {
            return $key . '="' . $value . '"';
        }, array_keys($options), $options);

        return '<a  href="' . route($this->getFrontEndConfigPrefixed($side, 'route') . '.edit', $this) . '" ' . implode('   ', $flatClasses) . '>' . $text . '</a>';
    }
}
