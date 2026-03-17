<?php
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

function logActivity($action, $event = null, $typeName = null, $subject = null)
{
    // Find or create the activity type record
    $type = null;
    if ($typeName) {
        $type = \App\Models\ActivityType::firstOrCreate(['name' => $typeName]);
    }

    $subjectId = null;
    $subjectType = null;

    if (is_object($subject)) {
        $subjectId = $subject->id ?? null;
        $subjectType = get_class($subject);
    } elseif (is_array($subject) && isset($subject['id'], $subject['type'])) {
        $subjectId = $subject['id'];
        $subjectType = $subject['type'];
    }

    ActivityLog::create([
        'user_id'      => Auth::id(),
        'action'       => $action,
        'event'        => $event,
        'type_id'      => $type ? $type->id : null,
        'subject_id'   => $subjectId,
        'subject_type' => $subjectType,
        'ip_address'   => Request::ip(),
        'user_agent'   => Request::header('User-Agent'),
    ]);
}
