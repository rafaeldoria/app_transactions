<?php

namespace App\Services;

class OrderService
{
    public function totalCalculate(array $itens) : float 
    {
        $total = 0;
        foreach ($itens as $key => $item) {
            $total += $item['amount'] * $item['price'];
        }
        return $total;    
    }
}
