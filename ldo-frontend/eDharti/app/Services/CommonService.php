<?php
namespace App\Services;

class CommonService
{
    public function getUniqueID($model, $prefix, $column)
    {
        $maxId = $model::max($column);

        if ($maxId) {
            // Extract numeric part after the prefix
            $numericPart = (int) substr($maxId, strlen($prefix));
            
            $nextNumericPart = $numericPart + 1;
            
            $paddedNumericPart = str_pad($nextNumericPart, 7, '0', STR_PAD_LEFT);

            $nextId = $prefix . $paddedNumericPart;
        } else {
            $nextId = $prefix . '0000001';
        }

        return $nextId;
    }
}
