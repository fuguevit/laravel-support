<?php

namespace Fuguevit\Support\Helpers;

class ResponseHelper
{
    /**
     * Return the success json result.
     *
     * @param null $data
     * @param null $message
     * @return mixed
     */
    public static function success($data = null, $message = null)
    {
        return response()->json([
            'status'        => 'success',
            'data'          => $data,
            'message'       => $message
        ]);
    }

    /**
     * Return the error json result.
     *
     * @param $error_code
     * @param null $message
     * @return Response
     */
    public static function error($error_code, $message = null)
    {
        return response()->json([
            'status'        => 'error',
            'data'          => null,
            'error_code'    => $error_code,
            'message'       => $message
        ]);
    }

    /**
     * Return request type not ajax error.
     *
     * @param null $message
     * @return Response
     */
    public static function notAjax($message = null)
    {
        return self::error(config('error-code.request_not_ajax'), $message);
    }

    /**
     * Return request format invalid.
     *
     * @param null $message
     * @return Response
     */
    public static function formatInvalid($message = null)
    {
        return self::error(config('error-code.invalid_format'), $message);
    }

    /**
     * Return request missing data.
     *
     * @param null $message
     * @return Response
     */
    public static function dataAbsence($message = null)
    {
        return self::error(config('error-code.data_absence'), $message);
    }
    
}