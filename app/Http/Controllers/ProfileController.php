<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $profile = auth()->user()->profile;
        return view('profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        UserProfile::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'org_type'            => $request->org_type,
                'activity_field'      => $request->activity_field,
                'country'             => $request->country,
                'funding_needs'       => $request->funding_needs,
                'budget_range'        => $request->budget_range,
                'project_description' => $request->project_description,
                'experience_level'    => $request->experience_level,
                'target_audience'     => $request->target_audience,
                'keywords'            => $request->keywords,
                'organization_name'   => $request->organization_name,
                'website'             => $request->website,
                'team_size'           => $request->team_size,
                'previous_grants'     => $request->previous_grants,
                'languages'           => $request->languages,
                'partners'            => $request->partners,
                'activity_region'     => $request->activity_region,
                'project_duration'    => $request->project_duration,
                'project_goals'       => $request->project_goals,
                'social_links'        => $request->social_links,
            ]
        );

        return redirect('/grants')->with('success', 'Профіль збережено! AI-агент тепер знає ваші потреби краще.');
    }
}
