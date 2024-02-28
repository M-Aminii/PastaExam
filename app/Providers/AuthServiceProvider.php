<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Policies\ExamPolicy;
use App\Policies\MultipleChoiceQuestionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
       // Gate::define('ShowExamDetails', [ExamPolicy::class, 'ShowExamDetails']);
        //$this->registerGates();
    }

    private function registerGates()
    {
        Gate::before(function ($user, $ability) {
            if ($user->isAdmin()) {
                return true;
            }
        });
    }
}
