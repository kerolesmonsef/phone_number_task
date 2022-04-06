<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Phone;
use Hoa\Compiler\Llk\Llk;
use Hoa\File\Read;
use Hoa\Math\Sampler\Random;
use Hoa\Regex\Visitor\Isotropic;
use Illuminate\Database\Seeder;

class PhoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Hoa\Regex\Exception
     */
    public function run()
    {

        $countries = Country::all();


        for ($i = 0; $i < 999; $i++) {
            $country = $countries->random(1)->first();

            if (rand() % 2 == 1) {
                $phone = $this->generate($country->regex);
            } else {
                $phone = "(" . rand(1, 999) . ")" . rand(1000000, 99999999);
            }

            Phone::create([
                'phone' => $phone,
                'country_id' => $country->id,
            ]);
        }
    }


    private function generate($pattern)
    {
        $grammar = new Read('hoa://Library/Regex/Grammar.pp');
        // 2. Load the compiler.
        $compiler = Llk::load($grammar);

        $ast = $compiler->parse($pattern);
        $generator = new Isotropic(new Random());
        return rtrim($generator->visit($ast));
    }
}
