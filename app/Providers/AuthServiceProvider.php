<?php

namespace App\Providers;

use App\Code\RolUsuario;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $super = RolUsuario::ADMIN;
        $permisos = [
            'configurar.reto' => [$super],
            'configurar.videos' => [$super],
            'configurar.programa' => [$super],
            'configurar.dia' => [$super],
            'configurar.suplementos' => [$super],
            'contactos' => [$super],
            'usuarios' => [$super],
        ];
        foreach ($permisos as $permiso => $roles) {
            Gate::define($permiso, function ($user) use ($roles) {
                return in_array(trim($user->rol), $roles);
            });
        }
    }
}
