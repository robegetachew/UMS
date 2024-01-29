<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Spatie\Permission\Exceptions\UnauthorizedException;
class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'page not found.'
                ], 404);
            }
        });
        $this->renderable(function (MethodNotAllowedHttpException $e, Request $request) {
         
                return response()->json([
                    'message' => 'Method not allowed.'
                ], 404);
            
        });
        $this->renderable(function (BadMethodCallException $e, Request $request) {
         
            return response()->json([
                'message' => 'method error.'
            ], 404);
        });
        $this->renderable(function (UnauthorizedException $e, Request $request) {
        
            return response()->json([
                'message' => 'Unauthorized'
            ], 404);
        
    });
    }
    
}
