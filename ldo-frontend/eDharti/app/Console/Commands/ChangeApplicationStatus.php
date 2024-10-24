<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ChangeApplicationStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:change-application-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change status of application entries if no action taken for three days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $newStatus = Item::where('group_id', 1031)->where('item_code', 'APP_NEW')->first();
        $pendingStatus = Item::where('group_id', 1031)->where('item_code', 'APP_PEN')->first();
        if (!is_null($newStatus) && !is_null($pendingStatus)) {
            $newStatusId = $newStatus->id;
            $pendingStatusId = $pendingStatus->id;

            // Update Application statuses
            $affectedRows = Application::where('status', $newStatusId)
                ->where('created_at', '<', Carbon::now()->subDays(3))
                ->update(['status' => $pendingStatusId]);

            // Log the result of the update
            Log::info('Rows Updated:', ['affectedRows' => $affectedRows]);
        } else {
            Log::error('One or both statuses not found.', [
                'newStatus' => $newStatus,
                'pendingStatus' => $pendingStatus
            ]);
        }
    }
}
