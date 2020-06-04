<?php
/**
 * @author Samsul Ma'arif  <samsulmaarif828@gmail.com
 */

namespace App\Traits;

use App\Exceptions\AppException;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Auth;

trait BaseResponseTraits
{
    protected $pos_client_id = 'yta-userdev';

    public function success($req)
    {
        $data = [
            'client_id' => $this->pos_client_id,
            "trx_type" => $req['trx_type'],
            "account_no" => $req['account_no'],
            "trx_date_time" => $req['trx_date_time'],
            "system_trace_audit" => $req['system_trace_audit'],
            "dest_customer_name" => $req['dest_account_no'],
            "pos_terminal_type" => $req['pos_terminal_type'],
            "dest_account_no" => $req['dest_account_no'],
            "amount" => $req['amount'],
            "description" => $req['description'],
            "response_code" => "000",
            "response_msg" => "success",
            "zetta_response_code" => '00',
            "zetta_response_msg" => "success",
        ];

        if($req['trx_type'] == 'CAB_TRF'){
            $data['account_balance'] = $req['account_balance'];
            $data['response_code'] = $req['response_code'];
            $data['response_msg'] = $req['response_msg'];
        }

        return $data;
    }

    public function failed($req, $error)
    {
        $data = [
            'client_id' => $this->pos_client_id,
            "trx_type" => $req['trx_type'],
            "account_no" => $req['account_no'],
            "trx_date_time" => $req['trx_date_time'],
            "system_trace_audit" => $req['system_trace_audit'],
            "dest_customer_name" => $req['dest_account_no'],
            "pos_terminal_type" => $req['pos_terminal_type'],
            "dest_account_no" => $req['dest_account_no'],
            "amount" => $req['amount'],
            "description" => $req['description'],
            "zetta_response_code" => '400',
            "zetta_response_msg" => $error,
            "response_code" => "400",
            "response_msg" => $error,
        ];

        if($req['trx_type'] == 'CAB_TRF'){
            $data['account_balance'] = $req['account_balance'];
            $data['response_code'] = $req['response_code'];
            $data['response_msg'] = $req['response_msg'];
        }

        return $data;
    }

    public function internalRes($code = 200, $data = null, $message = null){
        if($code == 200 || $code == 201){
            return response()->json([
                'code' => $code,
                'message' => is_null($message) ? '' : $message,
                'data' => is_null($data) ? '' : $data
            ], $code);
        }
        return response()->json(['error' => $data], $code);
    }
}
