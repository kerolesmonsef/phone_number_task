<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhoneRequest;
use App\Http\Resources\PhoneResource;
use App\Models\Country;
use App\Models\Phone;
use App\Services\Filters\QueryFiltersClasses\Where;
use App\Services\Filters\QueryFiltersClasses\WherePhoneIsValid;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class PhoneController extends Controller
{

    public function index()
    {
        return view("welcome", [
            'countries' => Country::all(),
        ]);
    }

    public function fetch(PhoneRequest $request)
    {
        // add it into service provider
        // if database driver is sqlite
        if (DB::Connection() instanceof \Illuminate\Database\SQLiteConnection) {
            DB::connection()->getPdo()->sqliteCreateFunction('REGEXP', function ($pattern, $value) {
                mb_regex_encoding('UTF-8');
                return mb_ereg("$pattern", $value) == 1 ? 1 : 0;
            });
        }

        $phone_query = Phone::query()
            ->select([
                DB::raw("countries.name as country_name"),
                DB::raw("REGEXP(countries.regex,phones.phone) as state"),
                DB::raw("countries.name as country_name"),
                'phones.phone'

            ])
            ->join("countries", "phones.country_id", "countries.id");

        app(Pipeline::class)
            ->send($phone_query)
            ->through([
                new Where('phones.country_id', request('country_id')),
                new WherePhoneIsValid(request('is_valid')),
            ])->thenReturn();

        $phones = $phone_query->simplePaginate();


        return response()->json([
            'phones' => json_decode(PhoneResource::collection($phones)->response()->getContent(), true) ?? [],
        ]);
    }
}
