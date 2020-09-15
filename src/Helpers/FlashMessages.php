<?php
/**
 * Created by PhpStorm.
 * User: Sotis
 * Date: 10/3/2018
 * Time: 4:10 μμ
 */


/**
 * Display the specified resource.
 *
 * @param  string  $alertClass  ='info'
 * @param  string  $msg
 * @param  string  $title
 * @param  string  $type  ='inline'
 * @param  bool  $dismissible
 *
 */
if (!function_exists('flashMessage')) {
    function flashMessage(string $msg, string $title = null, string $alertClass = 'info', string $type = 'inline', bool $dismissible = false)
    {
        $array = [
            'msg' => $msg,
            'class' => $alertClass,
            'title' => $title,
            'type' => $type
        ];
        if ($type == 'inline') {
            $array = array_merge($array, ['isDismissible' => $dismissible]);
        }
        session()->push('alertMessages', $array);

    }
}
if (!function_exists('flashToastr')) {
    function flashToastr(string $msg, string $title = null, string $alertClass = 'info')
    {
        flashMessage($msg, $title, $alertClass, 'toastr');
    }
}
if (!function_exists('flashSwal')) {
    function flashSwal(string $msg, string $title = null, string $alertClass = 'info')
    {
        flashMessage($msg, $title, $alertClass, 'swal');
    }
}
