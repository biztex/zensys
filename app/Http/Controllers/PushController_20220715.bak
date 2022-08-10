<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use tgMdk\TGMDK_Exception;
use tgMdk\TGMDK_MerchantUtility;

class PushController extends Controller
{

    public function mpi(Request $request)
    {
        $body = $request->getContent();
        Log::debug("body:" . $body);

        $hmac = $request->header('content-hmac');
        Log::debug("content-hmac:" . $hmac);

        try {
            if (TGMDK_MerchantUtility::checkMessage($body, $hmac)) {
                Log::debug('入金通知データの検証に成功しました。');
                return response("OK", 200);

            } else {
                Log::debug('入金通知データの検証に失敗しました。');

            }

        } catch (TGMDK_Exception $e) {
            Log::error('入金通知データの検証中に例外が発生しました。');

        }

        return response("NG", 500);
    }

}
