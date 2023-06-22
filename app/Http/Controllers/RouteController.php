<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanRoute;
use App\Models\LoanRouteOld;
use Illuminate\Http\Request;
use App\Models\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\People;
use App\Models\RouteCollector;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RouteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('routes.browse');
    }

    public function list($search = null){
        $user = Auth::user();

        $paginate = request('paginate') ?? 10;
        $data = Route::where(function($query) use ($search){
                    $query->OrWhereRaw($search ? "id = '$search'" : 1)
                    ->OrWhereRaw($search ? "name like '%$search%'" : 1)
                    ->OrWhereRaw($search ? "description like '%$search%'" : 1);
                    })
                    ->where('deleted_at', NULL)->orderBy('id', 'DESC')->paginate($paginate);
                    // $data = 1;
                    // dd($data->links());
        return view('routes.list', compact('data'));
    }


    public function indexCollector($route)
    {
        // return $route;
        $id = $route;
        $collector = User::whereRaw('role_id = 4 or role_id = 5')->get();
        return view('routes.collector.browse', compact('id', 'collector'));
    }

    public function listCollector($id, $search = null){
        $route = $id;
        $user = Auth::user();

        $paginate = request('paginate') ?? 10;

        $data = RouteCollector::with(['collector' => function($q) use($search)
                    {
                        $q->where(function($query) use ($search){
                            $query->OrWhereRaw($search ? "id = '$search'" : 1)
                            ->OrWhereRaw($search ? "name like '%$search%'" : 1);
                            });
                    }])
                    ->where('route_id', $route)->where('deleted_at', null)
                    ->orderBy('id', 'DESC')->paginate($paginate);
                    // $data = 1;
                    // dd($data->links());
        return view('routes.collector.list', compact('data'));
    }

    public function storeCollector(Request $request, $route)
    {
        // $id= $route;
        // return $id;
        DB::beginTransaction();
        try {

            $ok = RouteCollector::where('route_id', $route)->where('user_id', $request->user_id)->where('deleted_at', null)->first();
            if($ok)
            {
                return redirect()->route('routes.collector.index', ['route'=>$route])->with(['message' => 'El cobrador ya existe.', 'alert-type' => 'error']);
            }
            RouteCollector::create([
                'route_id'=>$route,
                'user_id'=>$request->user_id,
                'observation'=>$request->observation,
                'register_userId'=>Auth::user()->id
            ]);

            DB::commit();
            return redirect()->route('routes.collector.index', ['route'=>$route])->with(['message' => 'Cobrador asignado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('routes.collector.index', ['route'=>$route])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function inhabilitarCollector($route, $collector)
    {
        $id= $route;
        DB::beginTransaction();
        try {
            RouteCollector::where('id', $collector)
                ->update([
                    'status'=>0,
                ]);
            DB::commit();
            return redirect()->route('routes.collector.index', ['route'=>$id])->with(['message' => 'Inhabilitado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('routes.collector.index', ['route'=>$id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function habilitarCollector($route, $collector)
    {
        $id= $route;
        DB::beginTransaction();
        try {
            RouteCollector::where('id', $collector)
                ->update([
                    'status'=>1,
                ]);
            DB::commit();
            return redirect()->route('routes.collector.index', ['route'=>$id])->with(['message' => 'Habilitado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('routes.collector.index', ['route'=>$id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
    public function deleteCollector($route, $collector)
    {
        $id= $route;
        DB::beginTransaction();
        try {
            RouteCollector::where('id', $collector)
                ->update([
                    'deleted_at'=>Carbon::now(),
                    'deleted_userId' => Auth::user()->id,
                    'deleted_agentType' => $this->agent(Auth::user()->id)->role
                ]);
            DB::commit();
            return redirect()->route('routes.collector.index', ['route'=>$id])->with(['message' => 'Eliminado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('routes.collector.index', ['route'=>$id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }


    // ···························     PARA CAMBIOS DE RUTAS DE LOS PRESTAMOS DIARIO Y ESPECIALES  ·······························
    public function loanRouteOld($id)
    {
        $route = LoanRoute::with(['route'])->where('loan_id', $id)->orderBy('id', 'DESC')->get();

        $loan = Loan::where('id', $id)->first();

        $data = Route::where('deleted_at', null)->get();
      
        return view('loans.routeOld.browse', compact('loan', 'route', 'data'));
    }

    public function updateRouteLoan(Request $request, $loan)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $agent = $this->agent($user->id);

            LoanRoute::where('loan_id', $loan)->where('deleted_at', null)
                ->update(['status'=>0, 'deleted_at'=>Carbon::now(), 'deleteObservation'=>$request->deleteObservation, 'deleted_userId'=> Auth::user()->id, 'deleted_agentType'=>$agent->role]);
            

            LoanRoute::create([
                    'loan_id' => $loan,
    
                    'route_id' => $request->route_id,
    
                    'observation' => $request->observation,
    
                    'register_userId' => $agent->id,
                    'register_agentType' => $agent->role
                ]);
            DB::commit();
            return redirect()->route('loans.index')->with(['message' => 'Ruta Cambiada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return 0;
            return redirect()->route('loans.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);

        }
    }



    // PARA LA VISTA DE CAMBIOS DE RUTAS EN GENERAL 

    public function indexExchange()
    {
        $data = Route::where('deleted_at', null)->get();
        return view('routesExchange.browse', compact('data'));
    }

    public function listLoan(Request $request)
    {
        // dump($request);
        $route = Route::where('deleted_at', null)->where('id', '!=', $request->route_id)->get();

        $route_id = $request->route_id;
        $data = Loan::with(['loanRoute', 'people'])
            ->whereHas('loanRoute', function($query)use($route_id){
                $query->where('deleted_at', NULL)->where('status', 1)->where('route_id', $route_id);
            })
            ->where('deleted_at', NULL)->where('status', 'entregado')->where('debt', '!=', 0)->orderBy('date', 'DESC')->get();
        // dump($data);
        
        return view('routesExchange.result', compact('data', 'route'));
    }

    public function storeExchangeLoan(Request $request)
    {

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $agent = $this->agent($user->id);
            $loan = json_decode($request->loanss);

            // return $request;
            // return $loan[0];
            for($i=0; $i< count($loan); $i++)
            {
                LoanRoute::where('loan_id', $loan[$i])->where('deleted_at', null)
                ->update(['status'=>0, 'deleted_at'=>Carbon::now(), 'deleted_userId'=> Auth::user()->id, 'deleted_agentType'=>$agent->role]);

                LoanRoute::create([
                    'loan_id' => $loan[$i],

                    'route_id' => $request->route_id,


                    'register_userId' => $agent->id,
                    'register_agentType' => $agent->role
                ]);

            }

            DB::commit();
            return redirect()->route('routes-loan-exchange.index')->with(['message' => 'Rutas Cambiada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return 0;
            return redirect()->route('routes-loan-exchange.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);

        }
        
    }
}
