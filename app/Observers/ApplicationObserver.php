<?php

namespace App\Observers;

use App\Jobs\ApplicationSubmitted;
use App\Jobs\ApplicationUpdated;
use App\Models\Application;
use App\Models\JobListing;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use function Laravel\Prompts\error;

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
        
        try {
            $job = new ApplicationSubmitted($application);
            dispatch($job);
            // ApplicationSubmitted::dispatch($application);
            Log::info('ApplicationObserver data : ',$application->toArray());
            error_log('Application Create Notification Send');
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
        // error_log(json_encode($application));
        $user = auth()->user();
        if($user->role == Str::slug('Employer')){
            try {
                $job = new ApplicationUpdated($application);
                dispatch($job);
                Log::info('ApplicationObserver data : ',$application->toArray());
                error_log('Application Update Notification Send');
            } catch (\Throwable $th) {
                //throw $th;
                Log::error('ApplicationObserver onUpdate ',$th->getTrace());
            }

        }
        else{
            Log::info('Application Updated by Applicant: ',[$user,$application->toArray()]);
        }
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
