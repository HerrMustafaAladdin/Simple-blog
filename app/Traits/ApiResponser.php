<?php

namespace App\Traits;


trait ApiResponser
{
    protected function successResponce($data, $message = null, $code)
    {
        return response()->json([
            'Status'    =>  'Successful',
            'Message'   =>  $message == null ? "The data was received successfully." : $message,
            'Data'      =>  $data
        ], $code);
    }

    protected function errorResponce($code, $message)
    {
        return response()->json([
            'status'    =>  'Wrong',
            'message'   =>  $message == null ? "There is a problem in receiving information." : $message,
            'data'      =>  NULL
        ], $code);
    }
}
