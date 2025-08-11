<?php

use App\Models\Tanents\Tanent;

if (!function_exists('currentTenantId')) {
    function currentTenantId()
    {
        $tenant = app('currentTenant');

        if ($tenant instanceof Tanent) {
            return $tenant->id;
        }

        if (is_array($tenant) && isset($tenant['id'])) {
            return $tenant['id'];
        }

        if (is_numeric($tenant)) {
            return Tanent::find($tenant)?->id;
        }

        return null;
    }
}
