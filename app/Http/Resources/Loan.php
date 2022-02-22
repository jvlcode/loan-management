<?php

namespace App\Http\Resources;

use App\Traits\ScheduleTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class Loan extends JsonResource
{   use ScheduleTrait;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [];
        $weeks = $this->plan->weeks;
        $beginning_balance = $this->amount;

        for ($i=1; $i <=$this->plan->weeks ; $i++) {
            $schedule_data = $this->calculate($beginning_balance,$weeks,$this->plan->interest_rate);
            $weeks--;
            $data[] = [
                'week'=> $i,
                'beginning_balance'=>$schedule_data['beginning_balance'],
                'principal'=>$schedule_data['principal'],
                'interest'=>$schedule_data['interest'],
                'payment'=>$schedule_data['scheduled_payment'],
                'closing_balance'=>$schedule_data['closing_balance']
            ];
            $beginning_balance = $schedule_data['closing_balance'];
        }


        return [
            "borrower"=> $this->user->fullname,
            "ref_no"=>$this->ref_no,
            "loan_type"=> $this->type->name,
            "loan_plan"=>  $this->plan->weeks.' weeks and Interest '.$this->plan->interest_rate.'%',
            "amount"=> $this->amount,
            "interest"=> $this->interest,
            "total"=> $this->amount+$this->interest,
            "purpose"=> $this->purpose,
            "status"=> $this->status==0?'pending':($this->status==1?'approved':'rejected'),
            "payment_settled"=> $this->is_settled==1?'yes':'no',
            "schedules"=>$data
        ];
    }
}
