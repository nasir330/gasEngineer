<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileStoreRequest;
use App\Http\Resources\ProfileListIndex;
use App\Http\Resources\ProfileViewResource;
use App\Models\Profiles;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Profiler\Profile;

class ProfileController extends Controller
{
    use HttpResponses;
    public function index()
    {
        $profiles = Profiles::orderBy('created_at', 'asc')->paginate(10);
        return ProfileListIndex::collection($profiles);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProfileStoreRequest $request)
    {
        $validated = $request->validated();
        $profile = Profiles::create($validated);
        return $this->success($profile, 'Profile created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Profiles $profile)
    {
        return new ProfileViewResource($profile);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Profiles $profile)
    {
        $profile->update($request->all());
        return $this->success(
            new ProfileViewResource($profile->fresh()),
            'Profile updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profiles $profile)
    {
        return $profile->delete();
    }
}
