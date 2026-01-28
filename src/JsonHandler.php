<?php

declare(strict_types=1);

namespace SMartins\Exceptions;

use Illuminate\Http\JsonResponse;
use SMartins\Exceptions\Handlers\Handler;
use Throwable;

trait JsonHandler
{
    /**
     * Handle the json response. Check if exception is treated. If true call
     * the specific handler. If false set the default response to be returned.
     */
    public function jsonResponse(Throwable $exception): JsonResponse
    {
        $handler = new Handler($exception);

        if (property_exists($this, 'exceptionHandlers')) {
            $handler->setExceptionHandlers($this->exceptionHandlers);
        }

        return $handler->handleException()->json();
    }
}
