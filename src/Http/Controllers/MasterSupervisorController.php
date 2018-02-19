<?php

namespace Vzool\Horizon\Http\Controllers;

use Vzool\Horizon\Contracts\SupervisorRepository;
use Vzool\Horizon\Contracts\MasterSupervisorRepository;

class MasterSupervisorController extends Controller
{
    /**
     * Get all of the master supervisors and their underlying supervisors.
     *
     * @param  \Vzool\Horizon\Contracts\MasterSupervisorRepository  $masters
     * @param  \Vzool\Horizon\Contracts\SupervisorRepository  $supervisors
     * @return \Illuminate\Support\Collection
     */
    public function index(MasterSupervisorRepository $masters,
                          SupervisorRepository $supervisors)
    {
        $masters = collect($masters->all())->keyBy('name')->sortBy('name');

        $supervisors = collect($supervisors->all())->sortBy('name')->groupBy('master');

        return $masters->each(function ($master, $name) use ($supervisors) {
            $master->supervisors = $supervisors->get($name);
        });
    }
}
