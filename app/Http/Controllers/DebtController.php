<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Debt;
use App\Payment;

class DebtController extends Controller
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
        return view('debt');
    }

    public function store(Request $request)
    {
        // recalculate if added debt with earlier date
        $validatedData = $request->validate([
            'title' => 'required|string',
            'value' => 'required|numeric',
            'date' => 'required|date'
        ]);

        if ($validatedData['value'] < 0) {
            return array(
                'success' => false,
                'message' => 'Value must be greater than 0',
            );
        }

        $debt = new Debt;
        $debt->title = $validatedData['title'];
        $debt->value = $validatedData['value'];
        $debt->action_date = $validatedData['date'];
        $debt->save();

        // if (Debt::where('action_date', '>', $debt->action_date)->where('id', '<>', $debt->id)->first()) {
        //     Payment::recalculatePaidDebts();
        //     return array(
        //         'success' => true,
        //     );
        // }

        $debt->payWithLeftOverMoney();

        return array(
            'success' => true,
        );
    }
}
