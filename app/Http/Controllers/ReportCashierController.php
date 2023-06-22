<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Route;
use App\Models\People;
use App\Models\Loan;
use App\Models\LoanDay;
use App\Models\RouteCollector;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Cashier;


class ReportCashierController extends Controller
{
    
    //:::::::::::: PARA RECAUDACION DIARIA DE LOS CAJEROS Y COBRADORES EN MOTOS::::::::    
    public function loanCollection()
    {        
        $route = Route::where('status', 1)->where('deleted_at', null)->get();
        $query_filter = 'id='.Auth::user()->id;
        if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('gerente') || auth()->user()->hasRole('administrador'))
        {
            $query_filter=1;
        }
        $user = User::whereRaw($query_filter)->get();
        
        
        return view('report.cashier.dailyCollection.report', compact('route', 'user'));
    }

    public function loanCollectionList(Request $request)
    {

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
                    ->whereDate('lda.created_at', date('Y-m-d', strtotime($request->date)))
                    ->where('lda.agent_id', $request->agent_id)
                    // ->whereRaw($query_filter)
                    ->select('p.first_name', 'u.name', 'p.last_name1', 'last_name2', 'p.ci', 'ld.date as dateDay', 'u.name',
                            'l.id as loan', 'l.code', 'l.amountTotal', 'lda.id as loanDayAgent_id', DB::raw('SUM(lda.amount)as amount'),
                            'lda.created_at as loanDayAgent_fecha', 't.transaction')
                    ->groupBy('loan', 'transaction')
                    ->orderBy('lda.created_at', 'ASC')
                    ->get();
        $amount = $data->SUM('amount');
        $agent = User::where('id', $request->agent_id)->first()->name;
        $ci = User::where('id', $request->agent_id)->first()->ci;

        $cashier = Cashier::where('user_id', Auth::user()->id)
        ->where('status', '!=', 'cerrada')
        ->where('deleted_at', NULL)->count();
        // return $cashier;
        // dump($ci);


     
        if($request->print){
            $date = $request->date;
            return view('report.cashier.dailyCollection.print', compact('data', 'date', 'agent', 'amount', 'ci'));
        }else{
            return view('report.cashier.dailyCollection.list', compact('data', 'cashier'));
        }
        
    }


    // para obtener los prestamos entregados del dia o una fecha en especifica
    public function loanDelivered()
    {   
        // $user = User::where('id', Auth::user()->id)->get();
        $query_filter = 'id='.Auth::user()->id;
        if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('gerente') || auth()->user()->hasRole('administrador'))
        {
            $query_filter=1;
        }
        $user = User::whereRaw($query_filter)->get();
        
        return view('report.cashier.loanDelivered.report', compact('user'));
    }


    public function loanDeliveredList(Request $request)
    {
        // dump($request);
        $data = Loan::with(['people', 'agentDelivered'])->where('status', 'entregado')
            ->where('delivered_userId', $request->agent_id)
            ->whereDate('dateDelivered', date('Y-m-d', strtotime($request->date)))
            ->get();

        $amount = $data->SUM('amountLoan');
        $agent = User::where('id', $request->agent_id)->first()->name;
        $ci = User::where('id', $request->agent_id)->first()->ci;
        // dump($data);
        $cashier = Cashier::where('user_id', Auth::user()->id)
        ->where('status', '!=', 'cerrada')
        ->where('deleted_at', NULL)->count();

        if($request->print){
            $date = $request->date;
            return view('report.cashier.loanDelivered.print', compact('data', 'date', 'amount', 'agent'));
        }else{
            return view('report.cashier.loanDelivered.list', compact('data', 'cashier'));
        }        
    }


    // PARA GENERAR LAS LISTA DE COBROS POR RUTAS DE ACUERDO A LOS COBRADORES AGENTES

    public function dailyList()
    {
        $route = Route::where('status', 1)->where('deleted_at', null)->get();
        // return $route;
        if(Auth::user()->hasRole('cobrador') || Auth::user()->hasRole('cajeros'))
        {
            $aux = RouteCollector::where('status',1)->where('deleted_at', null)->where('user_id', Auth::user()->id)->first();
            
            $route = Route::where('status', 1)->where('id', $aux?$aux->route_id:0)->where('deleted_at', null)->get();
        }
        // return 1;
        return view('report.cashier.dailyListCobro.report', compact('route'));
    }
    public function dailyListList(Request $request)
    {
        if($request->route_id  == 'todo')
        {
            $query_filter = 1;
            $message = 'Todas Las Rutas';
        }
        else
        {
            $query_filter = 'lr.route_id = '.$request->route_id;
            $message = Route::where('id', $request->route_id)->where('deleted_at', null)->select('name')->first()->name;
        }

        $data = DB::table('loan_routes as lr')
            ->join('loans as l', 'l.id', 'lr.loan_id')
            ->join('people as p', 'p.id', 'l.people_id')
            ->join('routes as r', 'r.id', 'lr.route_id')



            ->where('l.deleted_at', null)
            ->where('lr.deleted_at', null)

            ->where('l.debt', '!=', 0)
            ->where('l.status', 'entregado')

            ->where('r.status', 1)
            ->where('r.deleted_at', null)
            ->whereRaw($query_filter)

            ->select('p.first_name', 'p.last_name1', 'last_name2', 'p.ci', 'l.code', 'l.dateDelivered', 'p.cell_phone', 'p.street', 'p.home', 'p.zone',
                'l.day', 'l.amountTotal', 'l.amountLoan', 'l.amountPorcentage', 'l.date', 'l.id as loan_id', 'r.name as ruta'
            )
            ->get();
        $date = date('Y-m-d');
        // dump($date);
            
        if($request->print){
            return view('report.cashier.dailyListCobro.print', compact('data', 'message', 'date'));
        }else{
            return view('report.cashier.dailyListCobro.list', compact('data', 'date'));
        }
    }
}
