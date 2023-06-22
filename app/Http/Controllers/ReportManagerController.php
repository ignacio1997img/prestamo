<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\People;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\LoanDay;
use App\Models\User;

class ReportManagerController extends Controller
{

    //$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$               PARA LA RECOLECCION DIARIA POR RANGO DE FECHA                   $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
    public function dailyCollection()
    {
        // return 1;
        $user = User::where('role_id', '!=', 1)->where('role_id', '!=', 2)->where('role_id', '!=', 3)->orderBy('name', 'ASC')->get();
        return view('report.manager.dailyCollection.report', compact('user'));
    }

    // VIEW LIST
    public function dailyCollectionList(Request $request)
    {
        $query_filter = 'lda.agent_id = '. $request->agent_id;
        if ($request->agent_id=='todo') {
            $query_filter = 1;
        }


        // $article = Article::whereRaw($query_filter)->get();
        $data = DB::table('loan_day_agents as lda')
                    ->join('loan_days as ld', 'ld.id', 'lda.loanDay_id')
                    ->join('loans as l', 'l.id', 'ld.loan_id')
                    ->join('people as p', 'p.id', 'l.people_id')
                    ->join('users as u', 'u.id', 'lda.agent_id')
                    ->join('transactions as t', 't.id', 'lda.transaction_id')

                    // ->where('l.deleted_at', null)
                    // ->where('ld.deleted_at', null)
                    ->where('lda.deleted_at', null)
                    ->whereDate('lda.created_at', '>=', date('Y-m-d', strtotime($request->start)))
                    ->whereDate('lda.created_at', '<=', date('Y-m-d', strtotime($request->finish)))
                    // ->where('lda.agent_id', $request->agent_id)
                    ->whereRaw($query_filter)
                    ->select('l.deleted_at','p.first_name', 'p.last_name1', 'last_name2', 'p.ci', 'ld.date as dateDay', 'u.name', 'l.id as loan', 'l.code', 'l.amountTotal', 'lda.id as loanDayAgent_id',
                                DB::raw('SUM(lda.amount)as amount'), 't.transaction',
                            'lda.created_at as loanDayAgent_fecha')
                    ->groupBy('loan', 'transaction')
                    ->orderBy('lda.created_at', 'ASC')
                    ->get();
        // return $data->id;    
        $amountTotal = $data->SUM('amount');
        // dump($amountTotal);
        if($request->print){
            $start = $request->start;
            $finish = $request->finish;
            return view('report.manager.dailyCollection.print', compact('data', 'start', 'finish', 'amountTotal'));
        }else{
            return view('report.manager.dailyCollection.list', compact('data'));
        }
    }


    //$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$               PARA TODOS LOS PRESTAMOS TOTAL SUMADOS                   $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
    public function loanAll()
    {
        return view('report.manager.loanAll.report');
    }
    public function loanAllList(Request $request)
    {
        // dump($request);
        $query_filter = 1;;
        if ($request->type == 'enpago') {
            $query_filter = 'l.debt > 0 ';
        }
        if ($request->type == 'pagado') {
            $query_filter = 'l.debt = 0 ';
        }


        // $article = Article::whereRaw($query_filter)->get();
        $data = DB::table('loans as l')
                    ->join('people as p', 'p.id', 'l.people_id')
                    ->join('users as u', 'u.id', 'l.delivered_userId')

                    ->where('l.deleted_at', null)
                    ->where('l.status', 'entregado')
                    ->whereDate('l.dateDelivered', '>=', date('Y-m-d', strtotime($request->start)))
                    ->whereDate('l.dateDelivered', '<=', date('Y-m-d', strtotime($request->finish)))
                    ->whereRaw($query_filter)
                    ->select('p.first_name', 'l.date as dateDelivered', 'p.last_name1', 'last_name2', 'p.ci', 'u.name', 'l.code', 'l.day', 'l.amountTotal', 'l.amountLoan', 'l.debt', 'l.porcentage', 'l.amountPorcentage')
                    // ->orderBy('l.dateDelivered', 'ASC')
                    ->orderBY('dateDelivered', 'ASC')
                    ->get();
        // return $data->id;    

        // dump($amountTotal);
        $ok = $request->type;
        if($request->print){
            $start = $request->start;
            $finish = $request->finish;
            return view('report.manager.loanAll.print', compact('data', 'start', 'finish', 'ok'));
        }else{
            return view('report.manager.loanAll.list', compact('data'));
        }
    }
    
}
