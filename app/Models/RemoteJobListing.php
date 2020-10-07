<?php

namespace App\Models;

use Illuminate\Support\Fluent;

class RemoteJobListing extends Fluent
{
    public static function fromRemoteDataObject(object $data)
    {
        return new static([
            'title' => $data->otsikko,
            'description' => $data->kuvausteksti,
            'company_name' => $data->tyonantajanNimi,
            'published_at' => $data->ilmoituspaivamaara,
        ]);
    }
}
