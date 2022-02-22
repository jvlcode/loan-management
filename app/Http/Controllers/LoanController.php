<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Loan as LoanResource;
use App\Models\LoanPlan;
use App\Http\Resources\LoanPlanCollection;
use App\Http\Resources\LoanTypeCollection;
use App\Models\LoanType;
use App\Models\Payment;
use App\Traits\ScheduleTrait;

class LoanController extends Controller
{ use ScheduleTrait;

    //payment will be processed by interest calculations and weeks
    public function payment(Request $request){
        $validator = Validator::make($request->all(),
            [
                'ref_no'=>'required',
                'payee'=>'required',
                'amount'=>'required|numeric',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['message'=>'validation error'],400);
        }


        $payment = $request->input('amount');
        $ref_no = $request->input('ref_no');
        $payee = $request->input('payee');

        if($loan = Loan::whereRefNo($ref_no)->first()){


           //payment will not be processed if:
           //1. payments meets the weeks
           //2. is_settled status is 1
           //3. loan status is not 1
            if($loan->status!=1 || $loan->is_settled || $loan->payments()->count() == $loan->plan->weeks){

                return response()->json([
                    'message'=>'payment cannot be processed',
                ],400);
            }

            $balance = $loan->amount - $loan->payments()->sum('amount');
            $interest_rate = $loan->plan->interest_rate/100;
            $weeks = $loan->plan->weeks - $loan->payments()->count();

            $data = $this->calculate($balance,$weeks,$interest_rate);
            $scheduled_payment = $data['scheduled_payment'];



            //scheduled payment should meet the payee's payment in order to process
            //more or less amount cannot be processed
            if( number_format( $scheduled_payment ,2) == number_format($payment,2) ){

                $loan_payment = new Payment;
                $loan_payment->amount = $payment;
                $loan_payment->payee = $payee;

                $loan->payments()->save($loan_payment);
                if($loan->payments()->count() == $loan->plan->weeks ){
                    //loan completion will be indicated with the is_setttled status
                    $loan->settled();
                }

                return response()->json(['success'=>true]);
            }


            return response()->json([
                'message'=>'invalid payment. scheduled payment is '.number_format($scheduled_payment,2,'.','')
            ],400);
        }

        return response()->json([
            'message'=>'invalid ref_no'
        ],400);

    }

    //applying the loan
    public function apply(Request $request){
        $validator = Validator::make($request->all(),
            [
                'loan_type_id'=>'required|exists:loan_plans,id',
                'amount'=>'required|numeric',
                'purpose'=>'required',
                'loan_plan_id'=>'required|exists:loan_plans,id',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['message'=>'validation error'],400);
        }

        $data = $validator->validated();
        $data['user_id'] = auth()->id();
        $data['ref_no'] = rand();
        $data['status'] = 0;
        Loan::create($data);
        return response()->json(['ref_no'=>$data['ref_no']],200);
    }

    //for loan details and approval status
    public function loan(Request $request){
        $validator = Validator::make($request->all(),
            [
                'ref_no'=>'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['message'=>'validation error'],400);
        }
        $ref_no = $request->input('ref_no');
        if($loan = Loan::whereRefNo($ref_no)->first()){
            return new LoanResource($loan);
        }
        return response()->json(['message'=>'invalid ref_no'],404);
    }

    //update the approval status of loan application
    public function status(Request $request){
        $validator = Validator::make($request->all(),
            [
                'ref_no'=>'required',
                'status'=>'required|numeric',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['message'=>'validation error'],400);
        }
        $ref_no = $request->input('ref_no');
        $status = $request->input('status');
        if($loan = Loan::whereRefNo($ref_no)->first()){
            $loan->update(['status'=>$status]);
            return response()->json(['success'=>true],200);
        }
        return response()->json(['message'=>'invalid ref_no'],404);
    }

    public function loans(){
        $loan_plans = LoanPlan::all();
        return new LoanPlanCollection($loan_plans);
    }

    public function types(){
        $loan_types = LoanType::all();
        return new LoanTypeCollection($loan_types);
    }

}
