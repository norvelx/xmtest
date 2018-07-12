<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    public function __construct($status)
    {
        $this->_status = $status;
    }
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        if ($request->ajax()) {
            return response()->json(['error' => 'Server error'], $this->_status);
        }
        return response()->view('errors.api');
    }
}