<?php

namespace App\Http\Controllers\V1;

use App\Domain\Exceptions\InvalidUserTypeException;
use App\Domain\Exceptions\MaxWalletAmountExceededException;
use App\Domain\Exceptions\TransactionErrorException;
use App\Domain\Exceptions\WalletWithoutCreditException;
use App\Domain\Services\TransactionService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\PostRequest;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class TransactionController extends Controller
{
    /** @var TransactionService */
    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function post(PostRequest $request)
    {
        try {
            $transaction = $this->transactionService->makeTransaction($request->all());
        } catch (WalletWithoutCreditException | MaxWalletAmountExceededException $exception) {
            return Response::json(['message' => $exception->getMessage()], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        } catch (InvalidUserTypeException $exception) {
            return Response::json(['message' => $exception->getMessage()], HttpResponse::HTTP_UNAUTHORIZED);
        } catch (TransactionErrorException $exception) {
            return Response::json(['message' => $exception->getMessage()], HttpResponse::HTTP_SERVICE_UNAVAILABLE);
        }

        return Response::json([
            'message' => trans('messages.transaction.successfully'),
            'data'    => ['id' => $transaction->id]
        ], HttpResponse::HTTP_OK);
    }
}
