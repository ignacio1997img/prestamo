<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use App\Models\Loan;
use App\Models\LoanDay;
use App\Models\LoanDayAgent;
use App\Models\LoanRoute;
use App\Models\LoanRequirement;
use App\Models\People;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function listTransaction($loan)
    {

        $data = DB::table('loans as l')
            ->join('loan_days as ld', 'ld.loan_id', 'l.id')
            ->join('loan_day_agents as lda', 'lda.loanDay_id', 'ld.id')
            ->join('transactions as t', 't.id', 'lda.transaction_id')
            ->join('users as u', 'u.id', 'lda.agent_id')
            ->join('people as p', 'p.id', 'l.people_id')
            ->leftJoin('users as ud', 'ud.id', 'lda.deleted_userId')
            ->where('l.id', $loan)
            ->where('l.deleted_at', null)
            ->select('l.id as loan', DB::raw('SUM(lda.amount)as amount'), 'u.name', 'lda.agentType', 'p.id as people', 'lda.transaction_id', 'ud.name as eliminado', 't.transaction', 't.deleted_at', 't.created_at')
            ->groupBy('loan', 'transaction')
            ->orderBy('transaction', 'ASC')
            ->get();

            // return $data;

        return view('transaction.browse', compact('data'));
    }
}
