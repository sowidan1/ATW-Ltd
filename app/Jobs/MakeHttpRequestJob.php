<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MakeHttpRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The URL of the endpoint to make the HTTP request to.
     *
     * @var string
     */
    protected $endpointUrl = 'https://randomuser.me/api/';

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Http::get($this->endpointUrl);
        $responseBody = $response->json();

        if (isset($responseBody['results'])) {
            Log::info('HTTP Request Response Results:', $responseBody['results']);
        } else {
            Log::warning('HTTP Request Response does not contain "results" key');
        }
    }
}
