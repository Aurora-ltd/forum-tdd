<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
        return view('profiles.show', [
            'user' => $user,
            'activities' => $this->getActivities($user)
        ]);
    }

    protected function getActivities($user)
    {
        return $user->activity()->with('subject')->get()->groupBy(function ($activity) {
            $activity->created_at->format('Y-m-d');
        });
    }
}
