<?php

namespace App\Console\Commands;

use App\Models\AdminPublicGrievance;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ChangePublicGrevanceStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:change-public-grevance-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change status of public grevences if no action is taken for 3 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $newStatus = Item::where('group_id', 17004)->where('item_code', 'PG_NEW')->first();

        $pendingStatus = Item::where('group_id', 17004)->where('item_code', 'PG_PEN')->first();
        // Check if both statuses were found
        if (!is_null($newStatus) && !is_null($pendingStatus)) {
            $newStatusId = $newStatus->id;
            $pendingStatusId = $pendingStatus->id;

            $affectedRows = AdminPublicGrievance::where('status', $newStatusId)
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
