<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Payment;

class Debt extends Model
{
 	protected $table = 'debts';

 	const UPDATED_AT = null;

 	public function payments()
 	{
 	   return $this->belongsToMany('App\Payment', 'debts_payments')->withPivot('value');
 	}

 	public function remaining()
 	{
 		$paid = array_sum(\DB::table('debts_payments')->where('debt_id', $this->id)->pluck('value')->toArray());
 		return $this->value - $paid;
 	}

 	public function payWithLeftOverMoney()
 	{
 		$leftOverPayment = \DB::table('debts_payments')->where('debt_id', 0)->pluck('payment_id')->toArray();
 		if (empty($leftOverPayment)) {
 			return false;
 		}
 		$payment = Payment::find(min($leftOverPayment));
 		$payment->debts()->detach(0);


 		if ($payment->payDebt() === 'not_paid') {
 			$this->payWithLeftOverMoney();
 		}
 	}
}
