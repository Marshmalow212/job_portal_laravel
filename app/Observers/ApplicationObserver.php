<?php

namespace App\Observers;

use App\Events\ApplicationSubmitted;
use App\Models\Application;
use App\Models\JobListing;
use Illuminate\Support\Facades\Log;

class ApplicationObserver
{
    /**
     * Handle the Application "created" event.
     */
    public function created(Application $application): void
    {
        $job_id = $application->job_id;
        $application_count = Application::where('job_id',$job_id)->count();
        $application->count = $application_count;
        // error_log(json_encode($application));
        try {
            ApplicationSubmitted::dispatch($application);
            Log::info('ApplicationObserver data : ',$application->toArray());
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('ApplicationObserver onCreate ',$th->getTrace());
        }
    }

    /**
     * Handle the Application "updated" event.
     */
    public function updated(Application $application): void
    {
        error_log(json_encode($application));
    }

    /**
     * Handle the Application "deleted" event.
     */
    public function deleted(Application $application): void
    {
        //
    }

    /**
     * Handle the Application "restored" event.
     */
    public function restored(Application $application): void
    {
        //
    }

    /**
     * Handle the Application "force deleted" event.
     */
    public function forceDeleted(Application $application): void
    {
        //
    }
}
