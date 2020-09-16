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
    public const TYPE_SWAL = 'swal';

    public const  SESSION_KEY = 'alertMessages';


    /**
     * @var Store
     */
    protected $session;


    /**
     * @var Store
     */
    protected $message;

    /**
     * @var string
     */
    protected $type = self::TYPE_INLINE;
    /**
     * @var string
     */
    private $uuid;

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
        $this->session->put($this->getSessionKey(), $this->message);
    }


    /**
     * Flash an information message.
     *
     * @param  string  $message
     * @param  string  $title
     * @return self
     */
    public function info(string $message, string $title = ''): self
    {
        $this->message($message, $title, Color::INFO);

        return $this;
    }

    /**
     * Flash a success message.
     *
     * @param  string  $message
     * @param  string  $title
     * @return self
     */
    public function success(string $message, string $title = ''): self
    {
        $this->message($message, $title, Color::SUCCESS);

        return $this;
    }

    /**
     * Flash an error message.
     *
     * @param  string  $message
     * @param  string  $title
     * @return self
     */
    public function error(string $message, string $title = ''): self
    {
        $this->message($message, $title, Color::ERROR);

        return $this;
    }

    /**
     * Flash a warning message.
     *
     * @param  string  $message  *
     * @param  string  $title
     * @return self
     */
    public function warning(string $message, string $title = ''): self
    {
        $this->message($message, $title, Color::WARNING);

        return $this;
    }


    /**
     * Flash a general message.
     *
     * @param  string  $message
     * @param  string  $title
     * @param  string  $level
     * @param  string  $type
     * @return self
     */
    public function message(string $message, string $title = '', string $level = Color::INFO, string $type = ''): self
    {
        $this->setType($type);

        $data = [
            'uuid' => $this->uuid,
            'msg' => $message,
            'class' => $level,
            'title' => $title,
            'type' => $this->type
        ];
        if ($this->type == self::TYPE_INLINE) {
            $data = array_merge($data, ['isDismissible' => true]);
        }
        $this->message = $data;

        $this->push();
        return $this;
    }

    /**
     * @return self
     */
    public function typeInline(): self
    {
        $this->setType(self::TYPE_INLINE);
        return $this;
    }

    /**
     * @return self
     */
    public function typeToast(): self
    {
        $this->setType(self::TYPE_TOAST);
        return $this;
    }

    /**
     * @return self
     */
    public function typeSwal(): self
    {
        $this->setType(self::TYPE_SWAL);
        return $this;
    }

    /**
     * @param  string  $type
     */
    private function setType(string $type = '')
    {
        if (in_array($type, [self::TYPE_INLINE, self::TYPE_SWAL, self::TYPE_TOAST])) {
            $this->type = $type;
        }
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


}


