<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponser 
{
    /**
     * Return success response
     *
     * @param string/array $data
     * @param int $code
     * @return Illuminate\Http\Response
     */
    public function successResponse($data, $code = Response::HTTP_OK)
    {
        return response()->json(['success' => true, 'data' => $data], $code);
    }

    /**
     * Build error response
     *
     * @param string/array $data
     * @param int $code
     * @return Illuminate\Http\Response
     */
    public function errorResponse($message, $code)
    {
        return response()->json(['success' => false, 'error' => ['message' => $message], 
                                'code' => $code], $code);
    }
}