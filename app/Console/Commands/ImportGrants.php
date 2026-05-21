<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Grant;

class ImportGrants extends Command
{
    protected $signature = 'grants:import';

    protected $description = 'Import grants from API';

    public function handle()
    {

        $response = Http::get('https://www.grants.gov/grantsws/rest/opportunities/search/');

        if($response->successful()){

            $data = $response->json();

            foreach(array_slice($data['data'] ?? [], 0, 5) as $item){

                Grant::create([

                    'title' => $item['title'] ?? 'No title',

                    'description' => $item['description'] ?? 'No description',

                    'country' => 'USA',

                    'category' => 'Grant',

                    'funding_amount' => '$10000',

                    'source_link' => $item['link'] ?? '#',

                ]);

            }

            $this->info('Grants imported successfully');

        }else{

            $this->error('API error');

        }

    }
}
