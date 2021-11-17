<?php

namespace App\Http\Controllers;

use App\Jobs\FetchClearbitCompany;
use App\Models\Company;
use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CompanyController extends Controller {

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $data = $request->validate([
            'api_key' => 'required',
            'company_name' => 'required',
            'company_domain' => 'required',
        ]);

        if (Task::where('company_name', '=', $data['company_name'])->where('company_domain', '=', $data['company_domain'])->count() > 0) {
            return response()->json([
                        'message' => 'You have already requested company data with these details.',
            ]);
        }

        $task = Task::create($data);

        FetchClearbitCompany::dispatch($task)->delay(now()->addMinute());

        return response()->json([
                    'message' => 'Your request will be processed as soon as possible.',
                        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request) {
        $data = $request->validate([
            'company_domain' => 'required',
        ]);

        try {
            $company = Company::where('domain', '=', $data['company_domain'])->firstOrFail();
        } catch (ModelNotFoundException $exception) {

            return response()->json([
                        'error' => [
                            'message' => sprintf('Company with domain %s not found', $data['company_domain'])
                        ]
                            ], 404);
        }

        return response()->json($company->data);
    }

    /**
     * Display information for a certain task is not yet scrapped or it is
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request) {
        $data = $request->validate([
            'company_domain' => 'required',
        ]);

        try {
            $task = Task::where('company_domain', '=', $data['company_domain'])->firstOrFail();
        } catch (ModelNotFoundException $exception) {

            return response()->json([
                        'error' => [
                            'message' => sprintf('A task for %s is not found', $data['company_domain'])
                        ]
                            ], 404);
        }

        return response()->json([
                    'status' => $task->status,
                    'message' => sprintf('The status of request for %s is %s', $task->company_domain, $task->status)
        ]);
    }

}
