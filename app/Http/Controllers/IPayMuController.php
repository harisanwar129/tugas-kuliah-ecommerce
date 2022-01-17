<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\ErrorLog;
use App\Models\Transaction;
use App\Models\TransactionLog;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IPayMuController extends Controller
{
    public function notify(Request $request)
    {
        Log::info("ADA NOTIFY");
        Log::info(response()->json($request->all()));
        // return response()->json(['halo' => 'heee']);
        $transactionLog = new TransactionLog();
        $transactionLog->response = response()->json($request->all());
        $transactionLog->title = 'notify';
        $transactionLog->save();

        // Update transaction
        // Sanitize POST Array
        try {
            $trx_id = $request->trx_id ?? '';
            $status = $request->status ?? '';
            $status_code = $request->status_code ?? '';
            $sid = $request->sid ?? '';
            $via = $request->via ?? '';
            $channel = $request->channel ?? '';
            $transaction = Transaction::where('sid', $sid)->first();
            if ($transaction) {

                if ($status == 'berhasil') {
                    // jika status pembayaran dinyatakan berhasil maka set notifikasi bahwa transaksi ini telah melakukan payment yang berhasil
                    $transaction->notify_paid = 1;
                } else {
                    $transaction->notify_paid = 0;
                }

                if($trx_id) {
                    $transaction->trx_id = $trx_id ?? '';
                }
                if($via) {
                    $transaction->via = $via ?? '';
                }
                if($channel) {
                    $transaction->channel = $channel ?? '';
                }
                if($status_code) {
                    $transaction->status_code = $status_code ?? '';
                }
                if($status) {
                    $transaction->status = $status ?? '';
                }


                $transaction->save();
                // return ResponseFormatter::success($transaction, "Berhasil notify");
                return response()->json(["response" => "Berhasil notify"]);
            }
            return ResponseFormatter::error(null, "Terjadi error: SID tidak ditemukan");
        } catch (Exception $e) {
            ErrorLog::create([
                'exception_class' => get_class($e),
                'message' => $e->getMessage(),
                'stack_trace' => json_encode($e->getTrace()),
                'url' => url()->full(),
                'user_id' => 0,
            ]);

            return ResponseFormatter::error(NULL, "Terjadi error");
        }
    }

    public function cancel(Request $request)
    {
        $transactionLog = new TransactionLog();
        $transactionLog->response = response()->json($request->all());
        $transactionLog->title = 'cancel';
        $transactionLog->save();
        return view('ipaymu.cancel', $request->all());
    }

    public function return(Request $request)
    {
        $transactionLog = new TransactionLog();
        $transactionLog->response = response()->json($request->all());
        $transactionLog->title = 'return';
        $transactionLog->save();
        return view('ipaymu.return', $request->all());

    }

    public function check_transaction(Request $request)
    {
        try {
            $transaction = Transaction::find($request->transaction_id)->first();
            return ResponseFormatter::success($transaction, "Berhasil notify");
            if ($transaction) {
                $trx_id = $transaction->trx_id;
                $transaction->save();
                return ResponseFormatter::success($transaction, "Berhasil notify");
            }
            return ResponseFormatter::error(null, "Terjadi error: SID tidak ditemukan");
        } catch (Exception $e) {
            ErrorLog::create([
                'exception_class' => get_class($e),
                'message' => $e->getMessage(),
                'stack_trace' => json_encode($e->getTrace()),
                'url' => url()->full(),
                'user_id' => 0,
            ]);

            return ResponseFormatter::error($e->getMessage(), "Terjadi error");
        }
    }

}
