<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\People;
use App\Models\AgentType;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('agents.browse');
    }

    public function list($search = null){
        $user = Auth::user();

        // $query_filter = 'busine_id = '.$user->busine_id;
        // if (Auth::user()->hasRole('admin')) {
        //     $query_filter = 1;
        // }
        // dd($user);
        $paginate = request('paginate') ?? 10;
        $data = Agent::with(['people'=> function($query) use ($search){
                        $query->OrWhereRaw($search ? "id = '$search'" : 1)
                        ->OrWhereRaw($search ? "first_name like '%$search%'" : 1)
                        ->OrWhereRaw($search ? "last_name like '%$search%'" : 1)
                        ->OrWhereRaw($search ? "CONCAT(first_name, ' ', last_name) like '%$search%'" : 1)
                        ->OrWhereRaw($search ? "ci like '%$search%'" : 1);
                    }, 'agentType'])
                    
                    // ->where(function($query) use ($search){
                    // $query->OrWhereRaw($search ? "id = '$search'" : 1)
                    // ->OrWhereRaw($search ? "first_name like '%$search%'" : 1)
                    // ->OrWhereRaw($search ? "last_name like '%$search%'" : 1)
                    // ->OrWhereRaw($search ? "CONCAT(first_name, ' ', last_name) like '%$search%'" : 1)
                    // ->OrWhereRaw($search ? "ci like '%$search%'" : 1);
                    // })
                    ->where('deleted_at', NULL)->orderBy('id', 'DESC')->paginate($paginate);

                    // $data = 1;
        return view('agents.list', compact('data'));
    }

    public function create()
    {
        $people = People::where('deleted_at', null)->where('status', 1)->orderBy('last_name', 'ASC')->get();
        $type = AgentType::where('deleted_at', null)->where('status', 1)->get();
        return view('agents.add', compact('people', 'type'));
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $agent = Agent::where('people_id', $request->people_id)->where('deleted_at', null)->where('status', 1)->select('*')->first();
            if($agent)
            {
                return redirect()->route('agents.create')->with(['message' => 'La persona ya se encuentra como agente.', 'alert-type' => 'error']);
            }
            Agent::create([
                'people_id' => $request->people_id,
                'agentType_id' => $request->type_id,
                'observation' => $request->observation,
                'register_userId' => $user->id
            ]);
            DB::commit();
            return redirect()->route('voyager.agents.index')->with(['message' => 'Agente Registrado Correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('voyager.agents.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function edit($id)
    {
        $agent =Agent::find($id);
        $people = People::where('deleted_at', null)->where('status', 1)->orderBy('last_name', 'ASC')->get();
        $type = AgentType::where('deleted_at', null)->where('status', 1)->get();
        return view('agents.edit', compact('type', 'people', 'agent'));
    }
    public function update(Request $request, $id )
    {
        DB::beginTransaction();
        try {
            $agent = Agent::where('people_id', $request->people_id)->where('id', '!=', $id)->where('deleted_at', null)->where('status', 1)->select('*')->first();
            if($agent)
            {
                return redirect()->route('agents.create')->with(['message' => 'La persona ya se encuentra como agente.', 'alert-type' => 'error']);
            }
            $agent = Agent::find($id);
            $agent->update([
                'people_id' => $request->people_id,
                'agentType_id' => $request->type_id,
                'observation' => $request->observation
            ]);
            DB::commit();
            return redirect()->route('voyager.agents.index')->with(['message' => 'Agente actualizado correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('voyager.agents.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function destroy($id)
    {
        // return $id;
        try {
            Agent::where('id', $id)->update([
                'deleted_at' => Carbon::now()
            ]);
            return redirect()->route('voyager.agents.index')->with(['message' => 'Anulado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            return redirect()->route('voyager.agents.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
}
