<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'org_type',
        'activity_field',
        'country',
        'funding_needs',
        'budget_range',
        'project_description',
        'experience_level',
        'target_audience',
        'keywords',
        'organization_name',
        'website',
        'team_size',
        'previous_grants',
        'languages',
        'partners',
        'activity_region',
        'project_duration',
        'project_goals',
        'social_links',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
