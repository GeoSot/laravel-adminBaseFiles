<?php
/**
 * Created by PhpStorm.
 * User: Sotis
 * Date: 10/3/2018
 * Time: 4:10 μμ
 */

namespace GeoSot\BaseAdmin\Helpers;


use Illuminate\Session\SessionManager;
use Illuminate\Session\Store;
use Illuminate\Support\Str;

/**
 * Class Alert.
 */
class Alert
{

    public const TYPE_INLINE = 'inline';
    public const TYPE_TOAST = 'toast';

    public const  SESSION_KEY = 'alertMessages';


    /**
     * @var Store
     */
    protected $session;


    /**
     * @var string
     */
    protected $type = self::TYPE_INLINE;

    /**
     * @var string
     */
    private $uuid;

    /**
     * @var array
     */
    private $data = [];

    /**
     * Create a new flash notifier instance.
     *
     * @param  SessionManager  $session
     */
    public function __construct(SessionManager $session)
    {
        $this->session = $session;
        $this->uuid = Str::uuid()->toString();
    }


    private function push()
    {
        $this->session->put($this->getSessionKey(), $this->data);
    }


    /**
     * Flash an information message.
     *
     * @param  string  $message
     * @param  string  $title
     * @return self
     */
    public static function info(string $message, string $title = ''): self
    {
        return app('alert')->message($message, $title, Color::info());
    }

    /**
     * Flash a success message.
     *
     * @param  string  $message
     * @param  string  $title
     * @return self
     */
    public static function success(string $message, string $title = ''): self
    {
        return app('alert')->message($message, $title, Color::success());
    }

    /**
     * Flash an error message.
     *
     * @param  string  $message
     * @param  string  $title
     * @return self
     */
    public static function error(string $message, string $title = ''): self
    {
        return app('alert')->message($message, $title, Color::error());
    }

    /**
     * Flash a warning message.
     *
     * @param  string  $message  *
     * @param  string  $title
     * @return self
     */
    public static function warning(string $message, string $title = ''): self
    {
        return app('alert')->message($message, $title, Color::warning());
    }


    /**
     * Flash a general message.
     *
     * @param  string  $message
     * @param  string  $title
     * @param  Color|null  $level
     * @param  string  $type
     * @return self
     */
    public function message(string $message, string $title = '', Color $level = null, string $type = ''): self
    {
        $level = $level ? $level->getType() : (Color::info())->getType();
        $this->setType($type);

        $data = [
            'uuid' => $this->uuid,
            'msg' => $message,
            'class' => $level,
            'title' => $title,
            'type' => $this->type
        ];
        $this->data = array_merge($this->data, $data);
        $this->dismissible($this->type == self::TYPE_INLINE);

        $this->push();
        return $this;
    }

    /**
     * @return void
     */
    public function typeInline(): void
    {
        $this->setType(self::TYPE_INLINE);
    }

    /**
     * @return void
     */
    public function typeToast(): void
    {
        $this->dismissible(false);
        $this->setType(self::TYPE_TOAST);
    }


    /**
     * @param  string  $type
     */
    private function setType(string $type = '')
    {
        if (in_array($type, [self::TYPE_INLINE, self::TYPE_TOAST])) {
            $this->type = $type;
        }
        $this->data['type'] = $this->type;

        $this->push();
        return $this;
    }


    /**
     * @return string
     */
    private function getSessionKey(): string
    {
        return self::SESSION_KEY.'.'.$this->uuid;
    }

    protected function dismissible(bool $dismissible)
    {
        $this->data['isDismissible'] = $dismissible;

        $this->push();
        return $this;
    }


}


