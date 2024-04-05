<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\MultipleChoiceQuestion;
use App\Models\User;
use App\Policies\DescriptiveQuestionPolicy;
use App\Policies\ExamPolicy;
use App\Policies\MultipleChoiceQuestionPolicy;
use App\Policies\QuestionPolicy;
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
       // User::class => UserPolicy::class,

        //MultipleChoiceQuestion::class => MultipleChoiceQuestionPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //$this->registerPolicies();
        Gate::define('delete-Multiple-Choice', [MultipleChoiceQuestionPolicy::class, 'delete']);
        Gate::define('update-Multiple-Choice', [MultipleChoiceQuestionPolicy::class, 'update']);
        Gate::define('delete-Descriptive', [DescriptiveQuestionPolicy::class, 'delete']);
        Gate::define('update-Descriptive', [DescriptiveQuestionPolicy::class, 'update']);
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
