<?php

namespace App\Console\Commands;

use App\Models\Item;
use App\Models\UserRegistration;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ChangeRegistrationStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:change-registration-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change status of registration entries if no action taken for three days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $newStatus = Item::where('group_id', 17000)->where('item_code', 'RS_NEW')->first();

        $pendingStatus = Item::where('group_id', 17000)->where('item_code', 'RS_PEN')->first();
        // Check if both statuses were found
        if (!is_null($newStatus) && !is_null($pendingStatus)) {
            $newStatusId = $newStatus->id;
            $pendingStatusId = $pendingStatus->id;

            // Update UserRegistration statuses
            $affectedRows = UserRegistration::where('status', $newStatusId)
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
