<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Fluent;

class RemoteJobListing extends Fluent
{
    public static function fromRemoteDataObject(object $data)
    {
        return new static([
            'title' => $data->otsikko,
            'description' => $data->kuvausteksti,
            'company_name' => $data->tyonantajanNimi,
            'published_at' => Carbon::parse($data->ilmoituspaivamaara),
        ]);
    }
}
