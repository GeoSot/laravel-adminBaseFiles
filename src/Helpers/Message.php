<?php
/**
 * Created by PhpStorm.
 * User: Sotis
 * Date: 10/3/2018
 * Time: 4:10 μμ
 */

namespace GeoSot\BaseAdmin\Helpers;


/**
 * Class Alert.
 */
class Message
{
    /**
     * @var string
     */
    private $message = '';
    /**
     * @var string
     */
    private $title = '';
    /**
     * @var \GeoSot\BaseAdmin\Helpers\Color|null
     */
    private $level;


    /**
     */
    public function __construct(string $message, string $title = '', Color $level = null)
    {
        $this->message = $message;
        $this->title = $title;
        $this->level = $level ? $level->getType() : (Color::info())->getType();;
    }


    /**
     * Flash a general message.
     *
     * @param string $message
     * @param string $title
     * @param Color|null $level
     * @return self
     */
    public function make(string $message, string $title = '', Color $level = null): self
    {
        return new self($message, $title, $level);
    }

    /**
     * Flash an information message.
     *
     * @param string $message
     * @param string $title
     * @return self
     */
    public static function info(string $message, string $title = ''): self
    {
        return new self($message, $title, Color::info());
    }

    /**
     * Flash a success message.
     *
     * @param string $message
     * @param string $title
     * @return self
     */
    public static function success(string $message, string $title = ''): self
    {
        return new self($message, $title, Color::success());
    }

    /**
     * Flash an error message.
     *
     * @param string $message
     * @param string $title
     * @return self
     */
    public static function error(string $message, string $title = ''): self
    {
        return new self($message, $title, Color::error());
    }

    /**
     * Flash a warning message.
     *
     * @param string $message *
     * @param string $title
     * @return self
     */
    public static function warning(string $message, string $title = ''): self
    {
        return new self($message, $title, Color::warning());
    }


    public function toAlert(): Alert
    {
        return app('alert')->message($this->message, $this->title, $this->level);
    }


    public function taData(): array
    {
        return [
            'msg' => $this->message,
            'level' => $this->level,
            'title' => $this->title,
        ];
    }


}


