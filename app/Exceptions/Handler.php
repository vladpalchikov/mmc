<?php

namespace MMC\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Auth;
use MMC\Notifications\ErrorToSlack;
use Notification;
use MMC\Models\User;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof \Ultraware\Roles\Exceptions\RoleDeniedException) {
            return redirect('/');
        }

        if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return redirect('/');
        }

        if (strpos($e->getMessage(), 'hasRole()')  !== false) {
            return redirect('/login');
        }

        if ($request->path() == '/' && !Auth::check()) {
            return parent::render($request, $e);
        }

        if (env('ERROR_NOTIFICATION') == 'production') {
            if (Auth::check()) {
                $data['user'] = Auth::user()->name;
                $data['role'] = Auth::user()->getRoles()->first()->name;
            } else {
                $data['user'] = 'Не залогиненный пользователь';
                $data['role'] = '';
            }
            $data['url'] = $request->path();
            $data['error'] = $e->getMessage().' on line '.$e->getLine().' in file '.$e->getFile();

            Notification::send(User::find(34), new ErrorToSlack($data));

            return response()->view('errors.500', [$request, $e, 'data' => $data], 500);
        }

        return parent::render($request, $e);
    }
}
