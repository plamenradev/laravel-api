<?php

namespace App\Jobs;

use App\Mail\ClearbitDataFetchedMail;
use App\Models\Company;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class FetchClearbitCompany implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 10;

    /**
     * 
     * @var Task
     */
    public $task;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Task $task) {
        $this->task = $task;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        // Call Clearbit Api to get data
        $response = Http::withToken($this->task->api_key)->get('https://company.clearbit.com/v2/companies/find', [
            'name' => $this->task->company_name,
            'domain' => $this->task->company_domain,
        ]);

        // Add company record
        $company = Company::create([
            'name' => $this->task->company_name,
            'domain' => $this->task->company_domain,
            'data' => $response->json(),
        ]);

        // Update task status
        $this->task->status = 'processed';
        $this->task->save();

        // Send email notification
        Mail::to('test@test.com')
                ->send(new ClearbitDataFetchedMail($company));
    }

}
