<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;


    public function getCountryCode(): string
    {
        $string = ' ' . $this->phone;
        $ini = strpos($string, "(");
        if ($ini == 0) return '';
        $ini += strlen("(");
        $len = strpos($string, ")", $ini) - $ini;
        return substr($string, $ini, $len);
    }


}
