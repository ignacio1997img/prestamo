<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use App\Models\People;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\CodeVerification;
use Illuminate\Support\Facades\Http;
use App\Models\LoanDay;

use function PHPUnit\Framework\returnSelf;

class TemplateController extends Controller
{
    public function index()
    {
        $people = People::all()->count();
        $loan = Loan::where('deleted_at', null)->get()->count();
        // return $people;
        return view('layout-template.master', compact('people', 'loan'));
    }

    public function searchLoan(Request $request)
    {
        // return 1;
        $loan = Loan::with(['people'])
            ->where('code', $request->code)->where('debt','!=', 0)->where('status','entregado')->where('deleted_at', null)->first();
        return view('layout-template.search', compact('loan'));
    }

    public function codeVerification(Request $request)
    {
        $aux = rand(10,99).''.rand(1,9).''.rand(100,999);
        codeVerification::where('loan_id', $request->loan_id)->where('type','solicitudCliente')->where('status',1)->update(['status'=>0]);
        CodeVerification::create(['type'=>'solicitudCliente', 'loan_id'=>$request->loan_id, 'cell_phone'=>$request->cell_phone, 'code'=>$aux]);
        Http::get('https://api.whatsapp.capresi.net/?number=591'.$request->cell_phone.'&message=CAPRESI%0A%0A*'.$aux.'* es tu codigo de verificación.%0A%0ANo lo compartas con nadie mas');

        return true;
    }

    public function verification($loan, $phone, $code)
    {
        
        $ok = codeVerification::where('loan_id', $loan)->where('code', $code)->where('type','solicitudCliente')->where('status',1)->first();
        $ok->update(['status'=>0]);

        if($ok)
        {
            $day = LoanDay::where('loan_id', $loan)->where('deleted_at', null)->get();
            $cadena = '';
            $i=1;
            $cant = count($day);
            $amountTotal =0;
            $amountDebt =0;
            foreach($day as $iten)
            {
                $cadena=$cadena.''.Carbon::parse($iten->date)->format('d/m/Y').'                  '.$iten->amount.'                '.str_pad(($iten->amount-$iten->debt), 2, "0", STR_PAD_LEFT).($i!=$cant?'%0A':'');
                
            }
            Http::get('https://api.whatsapp.capresi.net/?number=591'.$phone.'&message=
  *COMPROBANTE DE DEUDA PENDIENTE*

    
                *DETALLE DEL PRESTAMO*
*DIAS DE PAGO*      |   *DEUDAS*    |   *CUOTAS*
__________________________________________%0A'.
                    $cadena.'
_________________________________________
    Gracias🤝😊');
        
            return 1;
        }
        else
        {
            return 0;
        }
    }
}
