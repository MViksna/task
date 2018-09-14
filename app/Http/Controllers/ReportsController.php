<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Debt;
use App\Payment;

class ReportsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('reports');
    }

    public function reportLists()
    {
        return array(
            'debts' => Debt::all(),
            'payments' => Payment::all(),
        );
    }

    public function reportPayment()
    {
        $report = [];
        $id = request()->id ?? 0;
        $payments = Payment::where('id', $id)->with('debts')->first();
        if (!$payments) {
            return array('report' => [],);
        }

        foreach ($payments->debts as $debt) {
            $debtReport = [
                "title" => $debt->title,
                "value" => $debt->pivot->value,
            ];
            $report[] = $debtReport;
        }

        return array(
            'report' => $report,
        );
    }

    public function reportDebt()
    {
        $report = [];
        $id = request()->id ?? 0;
        $date = request()->date ?? null;
        if (!$date) {
            return array('report' => [], 'balance' => 0);
        }
        $debt = Debt::where('id', $id)->where('action_date', '<=', $date)->with(['payments' => function($q) use ($date) {
            $q->where('action_date', "<=", $date);
        }])->first();
        if (!$debt) {
            return array('report' => [], 'balance' => 0);
        }

        $paid = 0;
        foreach ($debt->payments as $payment) {
            $reportPayment = [
                "title" => $payment->title,
                "action_date" => $payment->action_date,
                "value" => $payment->pivot->value,
            ];
            $paid += $payment->pivot->value;
            $report[] = $reportPayment;
        }
        return array(
            'report' => $report,
            'balance' => $debt->value - $paid,
        );
    }
    public function reportTotal()
    {
        $report = [];
        $date = request()->date ?? null;
        $debts = array_sum(Debt::where('action_date', "<=", $date)->pluck('value')->toArray());
        $payments = array_sum(Payment::where('action_date', "<=", $date)->pluck('value')->toArray());
        $balance = $payments - $debts;

        return array(
            "debts" => $debts,
            "payments" => $payments,
            "balance" => $balance,
        );
    }
}
