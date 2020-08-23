<?php

namespace App\Http\Controllers\V1;

use App\Domain\Exceptions\FailUserInsertionException;
use App\Domain\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\PostRequest;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class UserController extends Controller
{
    /** @var UserService*/
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function post(PostRequest $request)
    {
        try {
            $user = $this->userService->addUser($request->all());
        } catch (FailUserInsertionException $exception) {
            return Response::json(['message' => $exception->getMessage()], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return Response::json([
            'message' => trans('messages.user.insert.successfully'),
            'data'    => ['id' => $user->id]
        ], HttpResponse::HTTP_OK);
    }
}
