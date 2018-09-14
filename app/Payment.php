<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Debt;

class Payment extends Model
{
    protected $table = 'payments';

    const UPDATED_AT = null;

    public function debts()
 	{
 	   return $this->belongsToMany('App\Debt', 'debts_payments')->withPivot('value');
 	}

 	public function remaining()
 	{
 		return $this->value - array_sum(\DB::table('debts_payments')->where('payment_id', $this->id)->pluck('value')->toArray());
 	}

 	public function saveLeftOver()
 	{
 		$this->debts()->attach(0, ['value'=> $this->remaining()]);
 	}

 	public function payDebt() // recursive
 	{
 		$debt = Debt::where('paid', 0)->orderBy('action_date', 'asc')->first();
 		if (!$debt) {
 			$this->saveLeftOver();
 			return false;
 		}
 		$debtRemaining = $debt->remaining();
 		$paymentRemaining = $this->remaining();

 		$balance = $paymentRemaining - $debtRemaining;

 		$notEnough = $balance < 0;
 		$tooMuch = $balance > 0;
 		$equal = $balance === 0;

 		if ($notEnough) {
 			$this->debts()->attach($debt->id, ['value'=> $paymentRemaining]);
 			return 'not_paid';
 		} elseif ($tooMuch) {
 			$this->debts()->attach($debt->id, ['value'=> $debtRemaining]);
 			$debt->paid = 1;
 			$debt->save();
 			$this->payDebt(); // recursive
 		} elseif ($equal) {
 			$this->debts()->attach($debt->id, ['value'=> $debtRemaining]);
 			$debt->paid = 1;
 			$debt->save();
 		}
 	}

 	public static function recalculatePaidDebts()
 	{
 		\DB::table('debts_payments')->truncate();
 		$payments = self::orderBy('action_date', 'asc')->get();
 		$debts = Debt::all();

 		foreach ($debts as $debt) {
 			$debt->paid = 0;
 			$debt->save();
 		}
 		foreach ($payments as $payment) {
 			$payment->payDebt();
 		}
 	}
}
