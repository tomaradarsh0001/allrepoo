<?php

namespace App\Helpers;

use App\Models\UserActionLog;
use Illuminate\Support\Facades\Auth;
use App\Models\Module;
use Carbon\Carbon;

class UserActionLogHelper
{
    public static function UserActionLog($action, $url, $moduleName, $description = null)
    {
        $module = Module::where('name', $moduleName)->first();
        if (empty($module)) {
            $newModule =  Module::create(['name' => $moduleName, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
            $moduleId = $newModule->id;
        } else {
            $moduleId = $module->id;
        }
        $log = new UserActionLog();
        $log->user_id = Auth::id();
        $log->module_id = $moduleId;
        $log->action = $action;
        $log->url = $url;
        $log->description = $description;
        $log->save();
    }
}
