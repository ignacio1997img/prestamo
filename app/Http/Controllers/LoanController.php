<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\People;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Loan;
use App\Models\LoanDay;
use App\Models\LoanRoute;
use App\Models\LoanRequirement;
use App\Models\User;
use Psy\CodeCleaner\ReturnTypePass;
use Psy\TabCompletion\Matcher\FunctionsMatcher;
use TCG\Voyager\Models\Role;
use App\Models\Route;
use Illuminate\Support\Facades\Http;
use App\Models\Transaction;

use App\Http\Controllers\FileController;
use App\Models\LoanDayAgent;
use Illuminate\Support\Composer;
use PhpParser\Node\Stmt\TryCatch;
use App\Models\Cashier;
use App\Models\CashierMovement;
use PhpParser\Node\Stmt\Return_;
use PHPUnit\Framework\MockObject\Stub\ReturnReference;
use ReturnTypeWillChange;

use function PHPSTORM_META\type;
use function PHPUnit\Framework\returnSelf;

class LoanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {   
        $collector = User::with(['role' => function($q)
            {
                $q->where('name','cobrador');
            }])->get();

        $user_id = Auth::user()->id;

        $cashier = Cashier::with(['movements' => function($q){
                $q->where('deleted_at', NULL);
            }])
            ->where('user_id', Auth::user()->id)
            ->where('status', 'abierta')
            ->where('deleted_at', NULL)->first();

        $balance = 0;
        if($cashier)
        {
            $cashier_id = $cashier->id;
            $balance = $cashier->movements->where('type', 'ingreso')->where('deleted_at', NULL)->sum('balance');
        }
        else
        {
            $cashier_id = 0;
        }


        // return $balance;
        return view('loans.browse', compact('collector', 'cashier', 'cashier_id', 'balance'));
    }

    public function list($cashier_id, $type, $search = null){
        $user = Auth::user();
        $paginate = request('paginate') ?? 10;
        switch($type)
        {
            case 'pendiente':
                $data = Loan::with(['loanDay', 'loanRoute', 'loanRequirement', 'people'])
                    ->where(function($query) use ($search){
                        if($search){
                            $query->OrwhereHas('people', function($query) use($search){
                                $query->whereRaw("(ci like '%$search%' or first_name like '%$search%' or last_name1 like '%$search%' or last_name2 like '%$search%' or CONCAT(first_name, ' ', last_name1, ' ', last_name2) like '%$search%')");
                            })
                            ->OrWhereRaw($search ? "typeLoan like '%$search%'" : 1)
                            ->OrWhereRaw($search ? "code like '%$search%'" : 1);
                        }
                    })
                    ->where('deleted_at', NULL)->where('status', 'pendiente')->orderBy('date', 'DESC')->paginate($paginate);
                return view('loans.list', compact('data', 'cashier_id'));
                break;
            case 'entregado':
                    $data = Loan::with(['loanDay', 'loanRoute', 'loanRequirement', 'people'])
                        ->where(function($query) use ($search){
                            if($search){
                                $query->OrwhereHas('people', function($query) use($search){
                                    $query->whereRaw("(ci like '%$search%' or first_name like '%$search%' or last_name1 like '%$search%' or last_name2 like '%$search%' or CONCAT(first_name, ' ', last_name1, ' ', last_name2) like '%$search%')");
                                })
                                ->OrWhereRaw($search ? "typeLoan like '%$search%'" : 1)
                                ->OrWhereRaw($search ? "code like '%$search%'" : 1);
                            }
                        })
                        ->where('deleted_at', NULL)->where('status', 'entregado')->where('debt', '!=', 0)->orderBy('date', 'DESC')->paginate($paginate);
                    return view('loans.list', compact('data', 'cashier_id'));
                    break;
            case 'verificado':
                $data = Loan::with(['loanDay', 'loanRoute', 'loanRequirement', 'people'])
                    ->where(function($query) use ($search){
                        if($search){
                            $query->OrwhereHas('people', function($query) use($search){
                                $query->whereRaw("(ci like '%$search%' or first_name like '%$search%' or last_name1 like '%$search%' or last_name2 like '%$search%' or CONCAT(first_name, ' ', last_name1, ' ', last_name2) like '%$search%')");
                            })
                            ->OrWhereRaw($search ? "typeLoan like '%$search%'" : 1)
                            ->OrWhereRaw($search ? "code like '%$search%'" : 1);
                        }
                    })
                    ->where('deleted_at', NULL)->where('status', 'verificado')->orderBy('date', 'DESC')->paginate($paginate);
                return view('loans.list', compact('data', 'cashier_id'));
                break;
            case 'aprobado':
                    $data = Loan::with(['loanDay', 'loanRoute', 'loanRequirement', 'people'])
                        ->where(function($query) use ($search){
                            if($search){
                                $query->OrwhereHas('people', function($query) use($search){
                                    $query->whereRaw("(ci like '%$search%' or first_name like '%$search%' or last_name1 like '%$search%' or last_name2 like '%$search%' or CONCAT(first_name, ' ', last_name1, ' ', last_name2) like '%$search%')");
                                })
                                ->OrWhereRaw($search ? "typeLoan like '%$search%'" : 1)
                                ->OrWhereRaw($search ? "code like '%$search%'" : 1);
                            }
                        })
                        ->where('deleted_at', NULL)->where('status', 'aprobado')->orderBy('date', 'DESC')->paginate($paginate);
                    return view('loans.list', compact('data', 'cashier_id'));
                    break;
            case 'pagado':
                $data = Loan::with(['loanDay', 'loanRoute', 'loanRequirement', 'people'])
                    ->where(function($query) use ($search){
                        if($search){
                            $query->OrwhereHas('people', function($query) use($search){
                                $query->whereRaw("(ci like '%$search%' or first_name like '%$search%' or last_name1 like '%$search%' or last_name2 like '%$search%' or CONCAT(first_name, ' ', last_name1, ' ', last_name2) like '%$search%')");
                            })
                            ->OrWhereRaw($search ? "typeLoan like '%$search%'" : 1)
                            ->OrWhereRaw($search ? "code like '%$search%'" : 1);
                        }
                    })
                    ->where('deleted_at', NULL)->where('debt', 0)->orderBy('date', 'DESC')->paginate($paginate);
                return view('loans.list', compact('data', 'cashier_id'));
                break;
            case 'rechazado':
                    $data = Loan::with(['loanDay', 'loanRoute', 'loanRequirement', 'people'])
                        ->where(function($query) use ($search){
                            if($search){
                                $query->OrwhereHas('people', function($query) use($search){
                                    $query->whereRaw("(ci like '%$search%' or first_name like '%$search%' or last_name1 like '%$search%' or last_name2 like '%$search%' or CONCAT(first_name, ' ', last_name1, ' ', last_name2) like '%$search%')");
                                })
                                ->OrWhereRaw($search ? "typeLoan like '%$search%'" : 1)
                                ->OrWhereRaw($search ? "code like '%$search%'" : 1);
                            }
                        })
                        ->where('deleted_at', NULL)->where('status', 'rechazado')->orderBy('date', 'DESC')->paginate($paginate);
                    return view('loans.list', compact('data', 'cashier_id'));
                    break;
            case 'todo':
                    $data = Loan::with(['loanDay', 'loanRoute', 'loanRequirement', 'people'])
                        ->where(function($query) use ($search){
                            if($search){
                                $query->OrwhereHas('people', function($query) use($search){
                                    $query->whereRaw("(ci like '%$search%' or first_name like '%$search%' or last_name1 like '%$search%' or last_name2 like '%$search%' or CONCAT(first_name, ' ', last_name1, ' ', last_name2) like '%$search%')");
                                })
                                ->OrWhereRaw($search ? "typeLoan like '%$search%'" : 1)
                                ->OrWhereRaw($search ? "code like '%$search%'" : 1);
                            }
                        })
                        ->where('deleted_at', NULL)->orderBy('date', 'DESC')->paginate($paginate);
                    return view('loans.list', compact('data', 'cashier_id'));
                    break;

        }

      

        

        // return view('loans.list', compact('data', 'cashier_id'));
    }

    public function create()
    {
        // return 1;
        $cashier = Cashier::with(['movements' => function($q){
            $q->where('deleted_at', NULL);
        }])
        ->where('user_id', Auth::user()->id)
        ->where('status', 'abierta')
        ->where('deleted_at', NULL)->first();
        
        $routes = Route::where('deleted_at', null)->where('status', 1)->orderBy('name')->get();

        return view('loans.add', compact( 'routes', 'cashier'));
    }
    public function ajaxNotPeople($id)
    {
        // return People::where('id', '!=', $id)->where('deleted_at', null)->where('status',1)->where('token','!=', null)->get();

    }

    public function createDaily($id)
    {
        // $loan_id = $id;
        $requirement = LoanRequirement::where('loan_id', $id)->first();

        $ok = LoanRequirement::where('loan_id', $id)
            ->where('ci','!=', null)
            ->where('luz','!=', null)
            ->where('croquis','!=', null)
            ->where('business','!=', null)
            ->select('*')
            ->first();
        // return $ok;
        // return $requirement;
        return view('requirement.daily.add', compact('requirement', 'ok'));        
    }

    public function storeRequirement(Request $request, $loan)
    {
        // return $request;
        DB::beginTransaction();
        try {
            $imageObj = new FileController;
            $ok = LoanRequirement::where('loan_id', $loan)->first();
            $file = $request->file('ci');
            // dd($request);
            if($file)
            {                 
                if($file->getClientOriginalExtension()=='pdf')
                {
                    $ci = $imageObj->file($file, $loan, "Loan/requirement/daily/ci");
                    // return 0;
                }
                else
                {
                    $ci = $imageObj->image($file, $loan, "Loan/requirement/daily/ci");
                    // return 1;
                }                
                $ok->update(['ci' => $ci]);
            }

            $file = $request->file('luz');
            if($file)
            {               
                if($file->getClientOriginalExtension()=='pdf')
                {
                    $luz = $imageObj->file($file, $loan, "Loan/requirement/daily/luz");                    
                }
                else
                {
                    $luz = $imageObj->image($file, $loan, "Loan/requirement/daily/luz");
                }
                $ok->update(['luz' => $luz]);
            }

            $file = $request->file('croquis');
            if($file)
            {         
                if($file->getClientOriginalExtension()=='pdf')
                {
                    $croquis = $imageObj->file($file, $loan, "Loan/requirement/daily/croquis");
                }
                else
                {
                    $croquis = $imageObj->image($file, $loan, "Loan/requirement/daily/croquis");
                }

                $ok->update(['croquis' => $croquis]);
            }

            $file = $request->file('business');
            if($file)
            {                        
                if($file->getClientOriginalExtension()=='pdf')
                {
                    $business = $imageObj->file($file, $loan, "Loan/requirement/daily/business");
                }
                else
                {
                    $business = $imageObj->image($file, $loan, "Loan/requirement/daily/business");
                }

                $ok->update(['business' => $business]);
            }

            if($request->lat)
            {
                $ok->update(['latitude' => $request->lat]);
            }
            if($request->lng)
            {
                $ok->update(['longitude' => $request->lng]);
            }

            DB::commit();
            return redirect()->route('loans-requirement-daily.create', ['loan'=>$loan])->with(['message' => 'Requisitos registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
            return redirect()->route('loans-requirement-daily.create', ['loan'=>$loan])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
        
    }

    public function deleteRequirement($loan, $col)
    {
        DB::beginTransaction();
        try 
        {
            $requirement = LoanRequirement::where('loan_id', $loan)->first();

            if($col==0)
            {
                $requirement->update(['ci'=>null]);
            }
            if($col==1)
            {
                $requirement->update(['luz'=>null]);
            }
            if($col==2)
            {
                $requirement->update(['croquis'=>null]);
            }
            if($col==3)
            {
                $requirement->update(['business'=>null]);
            }
            DB::commit();
            return redirect()->route('loans-requirement-daily.create', ['loan'=>$loan])->with(['message' => 'Requisitos eliminado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('loans-requirement-daily.create', ['loan'=>$loan])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function successRequirement($loan)
    {
        // return 'aprobado';

        DB::beginTransaction();
        try {
            Loan::where('id', $loan)->update(['inspector_userId'=>Auth::user()->id, 'inspector_agentType' => $this->agent(Auth::user()->id)->role, 'status'=>'verificado']);
            LoanRequirement::where('loan_id',$loan)
                ->update(['status'=>'1',
                        'success_userId' => Auth::user()->id,
                        'success_agentType' => $this->agent(Auth::user()->id)->role
                        ]);

            
            DB::commit();
            return redirect()->route('loans-requirement-daily.create', ['loan'=>$loan])->with(['message' => 'Requisitos aprobado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('loans-requirement-daily.create', ['loan'=>$loan])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function store(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        $user = Auth::user();
        $agent = $this->agent($user->id);

        try {
            $loan = Loan::create([
                        'people_id' => $request->people_id,
                        'cashierRegister_id' => $request->cashier_id,
                        'guarantor_id' => $request->guarantor_id?$request->guarantor_id:null,
                        'date' => $request->date,
                        'day' => $request->day,
                        'observation' => $request->observation,

                        'typeLoan' => $request->optradio,

                        'porcentage' => $request->porcentage,
                        'amountLoan' =>  $request->amountLoan,
                        'amountPorcentage' => $request->amountPorcentage,
                        
                        'debt' => $request->amountTotal,
                        'amountTotal' => $request->amountTotal,

                        'register_userId' => $agent->id,
                        'register_agentType' => $agent->role,
                        'status' => 'pendiente'
            ]);

            

            $loan->update(['code'=>'CP-'.str_pad($loan->id, 5, "0", STR_PAD_LEFT)]);
            LoanRoute::create([
                'loan_id' => $loan->id,

                'route_id' => $request->route_id,

                'observation' => 'Primer ruta',

                'register_userId' => $agent->id,
                'register_agentType' => $agent->role
            ]);

            LoanRequirement::create([
                'loan_id' => $loan->id,

                'register_userId' => $agent->id,
                'register_agentType' => $agent->role
            ]);

          

            //************************************************************************************* */
            //                  AUXILIAR
            // Loan::where('id', $loan->id)->update(['inspector_userId'=>Auth::user()->id, 'inspector_agentType' => $this->agent(Auth::user()->id)->role, 'status'=>'verificado']);
            // LoanRequirement::where('loan_id',$loan->id)
            // ->update(['status'=>'1',
            //         'success_userId' => Auth::user()->id,
            //         'success_agentType' => $this->agent(Auth::user()->id)->role
            //         ]);


            // Loan::where('id', $loan->id)->update([
            //     'status' => 'aprobado',
            //     'success_userId' => Auth::user()->id,
            //     'success_agentType' => $this->agent(Auth::user()->id)->role
            // ]);

            //************************************************************************************* */






            DB::commit();
            return redirect()->route('loans.index')->with(['message' => 'Prestamos registrado exitosamente.', 'alert-type' => 'success']);

        } catch (\Throwable $th) {
            DB::rollBack();
            // return 0;
            return redirect()->route('loans.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function show($id)
    {
        // $loan = Loan::with(['loanDay', 'loanAgent'])
        //     ->where('deleted_at', null)->get();
        
        // return $loan;

        
        return 1;
        return view('loans.read');
    }

    public function printCalendar($id)
    {
        $loan = Loan::with(['people', 'loanDay'])
            ->where('deleted_at', null)->where('id', $id)->first();
        // return $loan;

        // Para imprimir el calendario Nuevo
        $id = $id;
        $loan = Loan::with(['loanDay', 'loanRoute', 'loanRequirement', 'people', 'guarantor'])
            ->where('deleted_at', null)->where('id',$id)->first();
        // $loanDay = LoanDay::where('loan_id', $loan->id)->get();

        $loanday = LoanDay::where('loan_id', $id)->where('deleted_at', null)->orderBy('number', 'ASC')->get();

        $cantMes = DB::table('loan_days')
                    ->where('loan_id', $id)
                    ->select(DB::raw('DATE_FORMAT(date, "%Y-%m") as meses'), DB::raw('DATE_FORMAT(date, "%m") as mes'), DB::raw('DATE_FORMAT(date, "%Y") as ano'))
                    ->orderBy('number', 'ASC')
                    ->groupBy('meses')
                    ->get();
        
        // return $cantMes;
        // return $loanday;
        
        $route = LoanRoute::with(['route'])->where('loan_id', $id)->where('status', 1)->where('deleted_at', null)->first();

        // return $id;
        $register = Auth::user();
        // return $register->role->name;
        $date = date('Y-m-d');






        return view('loans.print-calendar', compact('loan', 'route', 'loanday', 'register', 'date', 'cantMes'));
    }

    public function destroy($id)
    {
        try {
            Loan::where('id', $id)->update([
                'deleted_at' => Carbon::now(),
                'deleted_userId' => Auth::user()->id,
                'deleted_agentType' => $this->agent(Auth::user()->id)->role
            ]);

            LoanDay::where('loan_id', $id)->update([
                'deleted_at' => Carbon::now(),
                'deleted_userId' => Auth::user()->id,
                'deleted_agentType' => $this->agent(Auth::user()->id)->role
            ]);

            LoanRoute::where('loan_id', $id)->update([
                'deleted_at' => Carbon::now(),
                'deleted_userId' => Auth::user()->id,
                'deleted_agentType' => $this->agent(Auth::user()->id)->role
            ]);
            
            LoanRequirement::where('loan_id', $id)->update([
                'deleted_at' => Carbon::now(),
                'deleted_userId' => Auth::user()->id,
                'deleted_agentType' => $this->agent(Auth::user()->id)->role
            ]);

            return redirect()->route('loans.index')->with(['message' => 'Anulado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            return redirect()->route('loans.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function rechazar($id)
    {
        // return $id;
        try {
            Loan::where('id', $id)->update([
                'status' => 'rechazado',
            ]);

            LoanDay::where('loan_id', $id)->update([
                'status' => 0
            ]);

            LoanRoute::where('loan_id', $id)->update([
                'status' => 0
            ]);
            
            LoanRequirement::where('loan_id', $id)->update([
                'status' => 0
            ]);

            return redirect()->route('loans.index')->with(['message' => 'Rechazado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            return redirect()->route('loans.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function updateAgent(Request $request, $loan)
    {
        // return $request;
        DB::beginTransaction();
        try {

            // $agent = LoanAgent::where('loan_id', $loan)
            //             ->where('deleted_at', null)
            //             ->first();
            // $agent->update([
            //     'status' => 0
            // ]);

            // LoanAgent::create([
            //     'loan_id' => $loan,

            //     'agent_id' => $request->loan_id,
            //     'agentType' => $this->agent($request->loan_id)->role,

            //     'observation' => $request->observation,

            //     'register_userId' => Auth::user()->id,
            //     'register_agentType' => $this->agent(Auth::user()->id)->role
            // ]);

            DB::commit();
            return redirect()->route('loans.index')->with(['message' => 'Cabrador Cambiado.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('loans.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }    

    //Para aprobar un prestamo el gerente
    public function successLoan($loan)
    {
        // return 1;
        DB::beginTransaction();
        try {
            $ok = Loan::with(['people'])
                ->where('id', $loan)->first();
            Http::get('https://api.whatsapp.capresi.net/?number=591'.$ok->people->cell_phone.'&message=Hola *'.$ok->people->first_name.' '.$ok->people->last_name1.' '.$ok->people->last_name2.'*.%0A%0A*SU SOLICITUD DE PRESTAMO HA SIDO APROBADA EXITOSAMENTE*%0A%0APase por favor por las oficinas para entregarle su solicitud de prestamos%0A%0AGracias🤝');
            
            // return $loan;
            Loan::where('id', $loan)->update([
                'status' => 'aprobado',
                'success_userId' => Auth::user()->id,
                'success_agentType' => $this->agent(Auth::user()->id)->role
            ]);
        


            DB::commit();
            return redirect()->route('loans.index')->with(['message' => 'Prestamo aprobado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('loans.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    // funcion para entregar dinero al beneficiario
    public function moneyDeliver(Request $request, $loan)
    {
        // return $loan;
        DB::beginTransaction();
        try {
            $loan = Loan::where('id', $loan)->first();

            if($loan->status== 'entregado')
            {
                return redirect()->route('loans.index')->with(['message' => 'El Prestamo ya fue entregado', 'alert-type' => 'error']);
            }
            // return $loan;
            $loan->update(['cashier_id'=>$request->cashier_id,'delivered_userId'=>Auth::user()->id, 'delivered_agentType' => $this->agent(Auth::user()->id)->role, 'status'=>'entregado', 'delivered'=>'Si', 'dateDelivered'=>Carbon::now()]);

            $movement = CashierMovement::where('cashier_id', $request->cashier_id)->where('deleted_at', null)->get();
            $countM = $movement->count();

            $amountLoan = $loan->amountLoan;

            foreach($movement as $item)
            {
                if($item->balance > 0 && $amountLoan > 0)
                {
                    if($item->balance >= $amountLoan)
                    {
                        $item->decrement('balance', $amountLoan);
                        $amountLoan = 0;
                    }
                    else
                    {
                        $amountLoan = $amountLoan - $item->balance;
                        $item->decrement('balance', $item->balance);
                    }
                }
            }       
            // $movement = CashierMovement::where('cashier_id', $request->cashier_id)->where('deleted_at', null)->first();
            // $movement->decrement('balance', $loan->amountLoan);

            $user = Auth::user();
            $agent = $this->agent($user->id);


            $date = date("d-m-Y",strtotime(date('y-m-d h:i:s')."+ 1 days"));
            $date = Carbon::parse($request->fechass);
            $date = date("Y-m-d", strtotime($date));
            $date = date("d-m-Y",strtotime($date."+ 1 days"));

            // return $loan;
         
            if($loan->typeLoan == 'diario')
            {
                // return 2;
                for($i=1;$i<=$loan->day; $i++)
                {
                    $fecha = Carbon::parse($date);
                    $fecha = $fecha->format("l");
                    // return $fecha;
                    if($fecha == 'Sunday' )
                    {
                        $date = date("Y-m-d", strtotime($date));
                        $date = date("d-m-Y",strtotime($date."+ 1 days"));
                    }
                    $date = date("Y-m-d", strtotime($date));
                    LoanDay::create([
                        'loan_id' => $loan->id,
                        'number' => $i,
                        'debt' => $loan->amountTotal/$loan->day,
                        'amount' => $loan->amountTotal/$loan->day,

                        'register_userId' => $agent->id,
                        'register_agentType' => $agent->role,

                        'date' => $date
                    ]);
                    $date = date("d-m-Y",strtotime($date."+ 1 days"));

                }
            }
            else
            {
                $loanDay = $loan->day;
                $amount = $loan->amountTotal;
                $amountDay = $amount/$loanDay;

                $aux = intval($amountDay);

                $dayT = $aux*($loanDay);
                // return $aux;


                $firstAux = $amount - $dayT;
                // return $first;
                $first = $aux + $firstAux;
                // return $first;
                // return $dayT+$firstAux;

                if($amount != ($dayT+$firstAux))
                {
                    DB::rollBack();
                    return redirect()->route('loans.index')->with(['message' => 'Ocurrió un error en la distribucion.', 'alert-type' => 'error']);
                }


                for($i=1;$i<=$loanDay; $i++)
                {
                    $fecha = Carbon::parse($date);
                    $fecha = $fecha->format("l");
                    // return $fecha;
                    if($fecha == 'Sunday' )
                    {
                        $date = date("Y-m-d", strtotime($date));
                        $date = date("d-m-Y",strtotime($date."+ 1 days"));
                    }
                    $date = date("Y-m-d", strtotime($date));
                    if($i==1)
                    {
                        $debA = $first;
                    }
                    else
                    {
                        $debA = $aux;
                    }

                    LoanDay::create([
                        'loan_id' => $loan->id,
                        'number' => $i,
                        'debt' => $debA,
                        'amount' => $debA,

                        'register_userId' => $agent->id,
                        'register_agentType' => $agent->role,

                        'date' => $date
                    ]);
                    $date = date("d-m-Y",strtotime($date."+ 1 days"));

                }
            }


            DB::commit();
            return redirect()->route('loans.index')->with(['message' => 'Dinero entregado exitosamente.', 'alert-type' => 'success', 'loan_id' => $loan->id,]);
        } catch (\Throwable $th) {
            DB::rollBack();
            // return 0;
            return redirect()->route('loans.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }

    }

    // Funcion  para imprimir el comprobante de pago al momento que se le entrega el prestamo al cliente o beneficiario
    public function printLoanComprobante($loan_id)
    {
        $loan = Loan::with(['people', 'agentDelivered', 'loanDay'])
        ->where('id', $loan_id)->first();
        return view('loansPrint.print-LoanDelivered', compact('loan')) ;
    }

    public function printContracDaily($loan)
    {
        // return 1;
        $loan = Loan::where('id', $loan)->first();
        return view('loans.print.loanDaily', compact('loan'));
    }

    // para ver el prestamos y poder abonar o pagar el dinero
    public function dailyMoney($loan, $cashier_id)
    {
        $cashier = Cashier::with(['movements' => function($q){
            $q->where('deleted_at', NULL);
        }])
        ->where('user_id', Auth::user()->id)
        ->where('status', 'abierta')
        ->where('deleted_at', NULL)->first();

        // return $cashier;
        $id = $loan;
        $loan = Loan::with(['loanDay', 'loanRoute', 'loanRequirement', 'people', 'guarantor'])
            ->where('deleted_at', null)->where('id',$id)->first();
        // $loanDay = LoanDay::where('loan_id', $loan->id)->get();

        $loanday = LoanDay::where('loan_id', $id)->where('deleted_at', null)->orderBy('number', 'ASC')->get();

        $cantMes = DB::table('loan_days')
                    ->where('loan_id', $id)
                    ->select(DB::raw('DATE_FORMAT(date, "%Y-%m") as meses'), DB::raw('DATE_FORMAT(date, "%m") as mes'), DB::raw('DATE_FORMAT(date, "%Y") as ano'))
                    ->orderBy('number', 'ASC')
                    ->groupBy('meses')
                    ->get();
        
        // return $cantMes;
        // return $loanday;
        
        $route = LoanRoute::with(['route'])->where('loan_id', $id)->where('status', 1)->where('deleted_at', null)->first();

        // return $id;
        $register = Auth::user();
        // return $register->role->name;
        $date = date('Y-m-d');
        // return $loanday;

        return view('loans.add-dailyMoney', compact('loan', 'route', 'loanday', 'register', 'date', 'cashier_id', 'cantMes', 'cashier'));
    }
// funcion para guardar el dinero diario en ncada prestamos
    public function dailyMoneyStore(Request $request)
    {
        $request->merge(['amount'=>floatval($request->amount)]);
        // return $request;
        if(!$request->amount || $request->amount ==0)
        {
            return redirect()->route('loans-daily.money', ['loan' => $request->loan_id, 'cashier_id'=>$request->cashier_id])->with(['message' => 'Error en el monto ingresado.', 'alert-type' => 'error']);
        }
        $code = Transaction::all()->max('id');
        $code = $code?$code:0;
        // return $code;

        $loan =Loan::where('id', $request->loan_id)->first();
        if($request->amount > $loan->debt)
        {
            return redirect()->route('loans-daily.money', ['loan' => $request->loan_id, 'cashier_id'=>$request->cashier_id])->with(['message' => 'Monto Incorrecto.', 'alert-type' => 'error']);
        }
        DB::beginTransaction();
        try {
            $transaction = Transaction::create(['transaction'=>$code+1]);
            $loan->update(['transaction_id'=>$transaction->transaction]);
            $amount = $request->amount;
            // return $amount;
            
            $ok = LoanDay::where('loan_id', $request->loan_id)->where('date', $request->date)->where('debt', '>', 0)->first();
            // return $request;
            // para la fecha actual del pago si existe numero en el calendario
            if($ok)
            {
                // return "fecha actual";
                $debt = $ok->debt;
                if($amount > $debt)
                {
                    $amount = $amount-$debt;
                }
                else
                {                    
                    $debt = $amount;
                    $amount = 0;
                }
                LoanDay::where('id', $ok->id)->decrement('debt', $debt);

                LoanDayAgent::create([
                    'loanDay_id' => $ok->id,
                    'cashier_id' => $request->cashier_id,
                    'transaction_id'=>$transaction->id,
                    'amount' => $debt,
                    'agent_id' => $request->agent_id,
                    'agentType' => $this->agent($request->agent_id)->role
                ]);
                Loan::where('id', $request->loan_id)->decrement('debt', $debt);
                CashierMovement::where('cashier_id', $request->cashier_id)->where('deleted_at', null)->first()->increment('balance', $debt);

            }
            // return $amount;
            if($amount>0)
            {
                // return $amount;
                $day = LoanDay::where('loan_id', $request->loan_id)->where('debt', '>', 0)->orderBy('number', 'ASC')->get();
                // return $day;
                foreach($day as $item)
                {
                    $debt = $item->debt;
                    if($amount > $debt)
                    {
                        $amount = $amount-$debt;
                    }
                    else
                    {                    
                        $debt = $amount;
                        $amount = 0;
                    }
                // return $amount;


                    LoanDay::where('id', $item->id)->decrement('debt', $debt);

                    LoanDayAgent::create([
                        'loanDay_id' => $item->id,
                        'cashier_id' => $request->cashier_id,
                        'transaction_id'=>$transaction->id,
                        'amount' => $debt,
                        'agent_id' => $request->agent_id,
                        'agentType' => $this->agent($request->agent_id)->role
                    ]);
                    CashierMovement::where('cashier_id', $request->cashier_id)->where('deleted_at', null)->first()->increment('balance', $debt);

                    Loan::where('id', $request->loan_id)->decrement('debt', $debt);
                    if($amount<=0)
                    {
                        break;
                    }

                }
            }
            
            $loanDayAgent = DB::table('loan_days as ld')
                ->join('loan_day_agents as la', 'la.loanDay_id', 'ld.id')
                ->join('users as u', 'u.id', 'la.agent_id')
                ->join('transactions as t', 't.id', 'la.transaction_id')
                ->where('ld.loan_id', $loan->id)
                ->where('t.id', $transaction->id)
                ->select('ld.id as loanDay', 'ld.date', 'la.amount', 'u.name', 'la.agentType', 'la.id as loanAgent', 'ld.late')
                ->get();
            
            $cadena = '';
            
            $cant = count($loanDayAgent);
            $i=1;
            foreach($loanDayAgent as $item)
            {
                $cadena=$cadena.($item->late==1?' SI':' NO').'            '.Carbon::parse($item->date)->format('d/m/Y').'         '.$item->amount.($i!=$cant?'%0A':'');
                $i++;
            }
            Http::get('https://api.whatsapp.capresi.net/?number=591'.$loan->people->cell_phone.'&message=
        *COMPROBANTE DE PAGO*

CODIGO: '.$loan->code.'
FECHA: '.Carbon::parse($transaction->created_at)->format('d/m/Y H:i:s').'
BENEFICIARIO: '.$loan->people->last_name1.' '.$loan->people->last_name2.' '.$loan->people->first_name.'
CI: '.$loan->people->ci.'

              *DETALLE DEL PAGO*
*ATRASO* | *DIAS PAGADO* | *TOTAL*
___________________________________%0A'.
    $cadena.'
___________________________________
TOTAL (BS)                           | '.number_format($request->amount,2).'
            
                *ATENDIDO POR*
'.strtoupper($loanDayAgent[0]->agentType).':        '.strtoupper($loanDayAgent[0]->name).'
COD TRANS:      '.$transaction->transaction.'

            
LOANSAPP V1');

            // return 1;
            DB::commit();
            return redirect()->route('loans-daily.money', ['loan' => $request->loan_id, 'cashier_id'=>$request->cashier_id])->with(['message' => 'Prestamo aprobado exitosamente.', 'alert-type' => 'success', 'loan_id' => $loan->id, 'transaction_id'=>$transaction->id]);
        } catch (\Throwable $th) {
            DB::rollBack();
            // return 0;
            return redirect()->route('loans-daily.money', ['loan' => $request->loan_id, 'cashier_id'=>$request->cashier_id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }        
    }

    public function printDailyMoney($loan_id, $transaction_id)
    {
        $transaction_id =$transaction_id;
        $loan = Loan::where('id', $loan_id)->first();

        $loanDayAgent = DB::table('loan_days as ld')
            ->join('loan_day_agents as la', 'la.loanDay_id', 'ld.id')
            ->join('users as u', 'u.id', 'la.agent_id')
            ->join('transactions as t', 't.id', 'la.transaction_id')
            ->where('ld.loan_id', $loan_id)
            ->where('t.id', $transaction_id)
            ->select('ld.id as loanDay', 'ld.date', 'la.amount', 'u.name', 'la.agentType', 'la.id as loanAgent', 'ld.late')
            ->get();

        // return  $loanDayAgent;
        
        $transaction = Transaction::find($transaction_id);
        // return $transaction;

        
        return view('loansPrint.print-dailyMoneyCash', compact('loan', 'transaction', 'loanDayAgent'));
    }













    //Para la aeliminacion de prestamos completo con caja cerrada pero siempre y cuando el gerente o administrador tenga una caja abonada o abierta y
    // y con dinero disponible 
    public function destroyLoan(Request $request, $loan)
    {
        // return 1;
        // return $request;
        DB::beginTransaction();
        try {
            //obtenemos la caja abierta 
            $cashier = Cashier::with(['movements' => function($q){
                    $q->where('deleted_at', NULL);
                }])
                ->where('user_id', Auth::user()->id)
                ->where('status', 'abierta')
                ->where('deleted_at', NULL)->first();

            if(!$cashier)
            {
                return redirect()->route('loans.index')->with(['message' => 'Error, La caja no se encuentra abierta.', 'alert-type' => 'error']);
            }
            // $loan = Loan::where('id', $loan)->where('deleted_at', null)->first();
            // return $cashier;


            $loan = Loan::with(['loanDay'])
                ->where('id', $loan)
                ->where('deleted_at', null)->first();

            // return $loan;
            if(!$loan)
            {
                return redirect()->route('cashiers.show', ['cashier'=>$request->cashier_id])->with(['message' => 'El prestamo ya se encuentra eliminado.', 'alert-type' => 'warning']);
            }

            //lo que debe en total del prestamo
            $amountDay = $loan->loanDay->SUM('debt');

            // return $amountDay;
            // return $cashier;

            //movimientos de caja
            $movement = CashierMovement::where('cashier_id', $cashier->id)->where('deleted_at', null)->get();
            // return $movement;
          
            // return $request;
            // agrego el monto que se le presto a la caja
            $movement->first()->increment('balance', $loan->amountLoan);  
            // return $movement;   
            // return $cashier;   

            $salida = $loan->amountTotal - $amountDay;
            // return $salida;

            $movementBalance = $movement->SUM('balance');//para obtener el m onto total de todo los movimeintos de la caja
            // return $movementBalance;

            //verificamos si el monto de las caja y los movimiento sumado es mayor o igual al monto que de devolvera al cliente
            if($salida > $movementBalance)
            {
                DB::rollBack();
                return redirect()->route('cashiers.show', ['cashier'=>$request->cashier_id])->with(['message' => 'Upss.. Ocurrio un error, comuniquese con el administradoir.', 'alert-type' => 'warning']);
            }
            // return $movementBalance;
            // return $request;

            foreach($movement as $item)
            {
                if($item->balance > 0 && $salida > 0)
                {
                    if($item->balance >= $salida)
                    {
                        $item->decrement('balance', $salida);
                        $salida = 0;
                    }
                    else
                    {
                        $salida = $salida - $item->balance;
                        $item->decrement('balance', $item->balance);
                    }
                }
            }


            LoanDay::where('loan_id', $loan->id)->where('deleted_at',null)->update([
                'deleted_at' => Carbon::now(),
                'deleted_userId' => Auth::user()->id,
                'deleted_agentType' => $this->agent(Auth::user()->id)->role,
                'deletedKey'=>$loan->id
            ]);
            $loan->update([
                'deleted_at' => Carbon::now(),
                'deleted_userId' => Auth::user()->id,
                'deleted_agentType' => $this->agent(Auth::user()->id)->role,
                'deleteObservation' => $request->destroyObservation,
                'deletedKey'=>$loan->id
            ]);
            // $loan->update(['destroyDate'=>Carbon::now(), 'destroyObservation'=>$request->observation, 'destroy_userId'=>Auth::user()->id, 'destroy_agentType'=>$this->agent($request->agent_id)->role]);

            // return $cashier;

            DB::commit();
            return redirect()->route('loans.index')->with(['message' => 'Eliminado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('loans.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }


}
