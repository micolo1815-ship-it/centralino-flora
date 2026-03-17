<?php
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

function logActivity($action)
{
    ActivityLog::create([
        'user_id' => Auth::id(),
        'action' => $action,
        'ip_address' => Request::ip(),
        'user_agent' => Request::header('User-Agent'),
    ]);
}
