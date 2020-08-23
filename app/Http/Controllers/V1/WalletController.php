<?php

namespace App\Http\Controllers\V1;

use App\Domain\Exceptions\FailCreditUpdateException;
use App\Domain\Exceptions\MaxWalletAmountExceededException;
use App\Domain\Exceptions\WalletNotFoundException;
use App\Domain\Services\WalletService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\PutRequest;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class WalletController extends Controller
{
    /** @var WalletService */
    private $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function put(PutRequest $request)
    {
        try {
            $wallet = $this->walletService->addCreditToWallet($request->all());
        } catch (FailCreditUpdateException $exception) {
            dd($exception->getMessage());
            return Response::json(['message' => $exception->getMessage()], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        } catch (WalletNotFoundException | MaxWalletAmountExceededException  $exception) {
            return Response::json(['message' => $exception->getMessage()], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Response::json([
            'message' => trans('messages.wallet.deposit.successfully'),
            'data'    => ['balance' => number_format($wallet->amount, 2)]
        ], HttpResponse::HTTP_OK);
    }
}
