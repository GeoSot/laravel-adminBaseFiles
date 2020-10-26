<?php

declare(strict_types=1);

namespace GeoSot\BaseAdmin\Helpers;

class Color
{
    public const INFO = 'info';
    public const SUCCESS = 'success';
    public const WARNING = 'warning';
    public const DEFAULT = 'default';
    public const DANGER = 'danger';
    public const PRIMARY = 'primary';
    public const SECONDARY = 'secondary';
    public const LIGHT = 'light';
    public const DARK = 'dark';
    public const LINK = 'link';
    public const ERROR = self::DANGER;

    protected $type = '';

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public static function info()
    {
        return new self(self::INFO);
    }

    public static function success()
    {
        return new self(self::SUCCESS);
    }

    public static function warning()
    {
        return new self(self::WARNING);
    }

    public static function default()
    {
        return new self(self::DEFAULT);
    }

    public static function danger()
    {
        return new self(self::DANGER);
    }

    public static function primary()
    {
        return new self(self::PRIMARY);
    }

    public static function secondary()
    {
        return new self(self::SECONDARY);
    }

    public static function light()
    {
        return new self(self::LIGHT);
    }

    public static function dark()
    {
        return new self(self::INFO);
    }

    public static function link()
    {
        return new self(self::LINK);
    }

    public static function error()
    {
        return self::danger();
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
