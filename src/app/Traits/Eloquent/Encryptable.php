<?php
/**
 * Artified by GeoSv.
 * User: gsoti
 * Date: 5/10/2018
 * Time: 10:36 πμ
 */

namespace GeoSot\BaseAdmin\App\Traits\Eloquent;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

trait Encryptable
{
    protected $encryptable = [];


    /**
     * If the attribute is in the encryptable array
     * then decrypt it.
     *
     * @param  $key
     *
     * @return mixed
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if ($this->isEncryptable($key)) {
            $value = $this->decryptValue($value);
        }

        return $value;
    }


    private function isEncryptable($key)
    {
        return in_array($key, $this->encryptable);
    }

    /**
     * When need to make sure that we iterate through
     * all the keys.
     *
     * @return array
     */
    //    public function attributesToArray()
    //    {
    //        $attributes = parent::attributesToArray();
    //
    //        foreach ($this->encryptable as $key) {
    //            if (isset($attributes[$key])) {
    //                $attributes[$key] = $this->decrypt($attributes[$key]);
    //            }
    //        }
    //
    //        return $attributes;
    //    }
    //
    //    /**
    //     * Get an attribute array of all arrayable attributes.
    //     *
    //     * @return array
    //     */
    //    protected function getArrayableAttributes()
    //    {
    //        $attributes = parent::getArrayableAttributes();
    //
    //        foreach ($attributes as $key => $attribute) {
    //            if ($this->isEncryptable($key)) {
    //                $attributes[$key] = $this->decrypt($attribute);
    //            }
    //        }
    //
    //        return $attributes;
    //    }
    /**
     * @param $value
     *
     * @return string
     */
    protected function decryptValue($value)
    {
        try {
            $value = (empty($value) or is_null($value)) ? $value : Crypt::decrypt($value);
        } catch (DecryptException $e) {

        }

        return $value;
    }

    /**
     * If the attribute is in the encryptable array
     * then encrypt it.
     *
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
        if ($this->isEncryptable($key)) {
            $value = $this->encryptValue($value);
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * @param $value
     *
     * @return string
     */
    protected function encryptValue($value)
    {
        return (empty($value) or is_null($value)) ? $value : Crypt::encrypt($value);
    }
}
