<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->string('organization_name')->nullable()->after('keywords');
            $table->string('website')->nullable()->after('organization_name');
            $table->integer('team_size')->nullable()->after('website');
            $table->string('previous_grants')->nullable()->after('team_size');
            $table->string('languages')->nullable()->after('previous_grants');
            $table->string('partners')->nullable()->after('languages');
            $table->string('activity_region')->nullable()->after('partners');
            $table->string('project_duration')->nullable()->after('activity_region');
            $table->text('project_goals')->nullable()->after('project_duration');
            $table->string('social_links')->nullable()->after('project_goals');
        });
    }

    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'organization_name', 'website', 'team_size',
                'previous_grants', 'languages', 'partners',
                'activity_region', 'project_duration',
                'project_goals', 'social_links'
            ]);
        });
    }
};
