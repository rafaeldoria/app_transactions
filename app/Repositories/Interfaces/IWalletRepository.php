<?php

namespace App\Repositories\Interfaces;

interface IWalletRepository
{
    public function getWalletByUser(Int $userId);
}
