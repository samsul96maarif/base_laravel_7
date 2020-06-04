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

trait BaseRequestAPITraits
{
    protected $url = 'https://sbudp-dev.posindonesia.co.id:9443/zetta/';
    protected $urlTransaction = 'http://103.123.39.238:7879/finsbu/h2h';
    protected $zetta_account_pooling = '0300000478';
    protected $pos_success_code = '00';
    protected $pos_terminal_type = '6022';
    protected $pos_client_id = 'yta-userdev';
    protected $pos_password_api = '';
    protected $pos_key_api = '';

    public function setHeader($authorization = false){
        $headers = [
            'Accept' => 'Application/Json',
        ];
        if ($authorization){
            $headers['Authorization'] = \Illuminate\Support\Facades\Auth::user()->generateToken();
            return $headers;
        }
        return $headers;
    }

    public function cekUrl($is_transaction = false){
        if ( !$is_transaction ){
            return $this->url;
        }
        return $this->urlTransaction;
    }

    public function generataSign($trx_type, $system_trace_audit, $account_no = '0000000000'){
//        Sign = MD5( client_id + trx_type + account_no + trx_date_time + system_trace_audit +
//            pos_terminal_type +
//            Password APIs + currentdate+ Key APIs )
        $trx_date_time = Carbon::now()->format('YmdHis');
        $current_date = $trx_date_time;
        return md5($this->pos_client_id.
            $trx_type.
            $account_no.
            $trx_date_time.
            $system_trace_audit.
            $this->pos_terminal_type.
            $this->pos_password_api.
            $current_date.
            $this->pos_key_api
        );
    }

    public function generateReqTransaction($action, $type){
        return [
            'action' => $action,
            'trx_type' => $type,
            'trx_code' => $this->generateTrxCode(),
        ];
    }

    public function generateTrxCode(){
        return Carbon::now()->format('YmdHis');
    }
}
