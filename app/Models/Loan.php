<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function type(){
        return $this->belongsTo(LoanType::class,'loan_type_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function plan(){
        return $this->belongsTo(LoanPlan::class,'loan_plan_id');
    }

    public function payments(){
        return $this->hasMany(Payment::class);
    }

    public function settled(){
        $this->update(['is_settled'=>1]);
    }

    public function getInterestAttribute(){
       return $this->amount*($this->plan->interest_rate/100);
    }
}
