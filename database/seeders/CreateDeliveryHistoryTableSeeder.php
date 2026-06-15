<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryHistoryTableSeeder extends Seeder
{
    public function run()
    {
        // Check if table already exists
        if (!Schema::hasTable('delivery_history')) {
            DB::statement('
                CREATE TABLE delivery_history (
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    delivery_log_id BIGINT UNSIGNED NOT NULL,
                    action_type VARCHAR(255) NOT NULL,
                    old_values JSON NULL,
                    new_values JSON NULL,
                    description TEXT NOT NULL,
                    changed_by BIGINT UNSIGNED NULL,
                    changed_at TIMESTAMP NOT NULL,
                    ip_address VARCHAR(255) NULL,
                    user_agent TEXT NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    
                    KEY idx_delivery_history_log_date (delivery_log_id, changed_at),
                    KEY idx_delivery_history_action_date (action_type, changed_at),
                    
                    FOREIGN KEY (delivery_log_id) REFERENCES delivery_logs(id) ON DELETE CASCADE,
                    FOREIGN KEY (changed_by) REFERENCES users(id) ON DELETE SET NULL
                )
            ');
            
            echo "✅ delivery_history table created successfully\n";
        } else {
            echo "ℹ️ delivery_history table already exists\n";
        }
    }
}