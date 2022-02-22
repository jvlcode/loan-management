<?php

namespace App\Traits;

trait ScheduleTrait{

    function calculate($beginning_balance,$weeks,$interest_percentage){
        $interest_rate = $interest_percentage/100;
        $interest = ($beginning_balance * $interest_rate) / $weeks;
        $principal = $beginning_balance/$weeks;
        $scheduled_payment = $principal + $interest ;
        $closing_balance = $beginning_balance - $principal;
        $data['beginning_balance'] = $beginning_balance;
        $data['closing_balance'] = $closing_balance;
        $data['scheduled_payment'] = $scheduled_payment;
        $data['principal'] = $principal;
        $data['interest'] = $interest;
        return $data;
    }
}
