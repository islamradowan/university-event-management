<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                if (!$this->indexExists('events', 'idx_events_status_start')) {
                    $table->index(['status', 'start_at'], 'idx_events_status_start');
                }
                if (!$this->indexExists('events', 'idx_events_category')) {
                    $table->index(['category'], 'idx_events_category');
                }
            });
        }

        if (Schema::hasTable('registrations')) {
            Schema::table('registrations', function (Blueprint $table) {
                if (!$this->indexExists('registrations', 'idx_registrations_event_user')) {
                    $table->index(['event_id', 'user_id'], 'idx_registrations_event_user');
                }
            });
        }
    }
    
    private function indexExists($table, $name)
    {
        try {
            $indexes = \DB::select("SHOW INDEX FROM {$table} WHERE Key_name = '{$name}'");
            return !empty($indexes);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex('idx_events_status_start');
            $table->dropIndex('idx_events_category');
            $table->dropIndex('idx_events_organizer');
            $table->dropIndex('idx_events_created');
        });

        Schema::table('registrations', function (Blueprint $table) {
            $table->dropIndex('idx_registrations_event_user');
            $table->dropIndex('idx_registrations_user');
            $table->dropIndex('idx_registrations_created');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_role');
            $table->dropIndex('idx_users_email');
        });

        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropIndex('idx_feedbacks_event');
            $table->dropIndex('idx_feedbacks_user');
        });
    }
};