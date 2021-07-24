<?php

namespace App\Providers;

use App\Repositories\Contracts\IInvitation;
use App\Repositories\Contracts\IProject;
use App\Repositories\Contracts\ISubTask;
use App\Repositories\Contracts\ITask;
use App\Repositories\Contracts\IUser;
use App\Repositories\Eloquent\InvitationRepository;
use App\Repositories\Eloquent\ProjectRepository;
use App\Repositories\Eloquent\SubTaskRepository;
use App\Repositories\Eloquent\TaskRepository;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Support\ServiceProvider;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(IUser::class, UserRepository::class);
        $this->app->bind(ITask::class, TaskRepository::class);
        $this->app->bind(ISubTask::class, SubTaskRepository::class);
        $this->app->bind(IProject::class, ProjectRepository::class);
        $this->app->bind(IInvitation::class, InvitationRepository::class);
    }
}
