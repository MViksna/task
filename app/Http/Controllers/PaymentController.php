<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Payment;

class PaymentController extends Controller
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
        return view('payment');
    }

    public function store(Request $request)
    {
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

        $payment = new Payment;
        $payment->title = $validatedData['title'];
        $payment->value = $validatedData['value'];
        $payment->action_date = $validatedData['date'];
        $payment->save();

        // if (Payment::where('action_date', '>', $payment->action_date)->where('id', '<>', $payment->id)->first()) {
        //     Payment::recalculatePaidDebts();
        //     return array(
        //         'success' => true,
        //     );
        // }

        $payment->payDebt();

        return array(
            'success' => true,
        );
    }
}
