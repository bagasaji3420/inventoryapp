<?php

namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;

class AuditController extends Controller
{
    public function index()
    {
        $title = 'Activity Logs';

        return view('Admin.Audit.activity-logs', compact('title'));
    }



    public function activityLogs()
    {
        $logs = Activity::with(['causer', 'subject'])->latest();

        return DataTables::of($logs)
            ->addColumn('user', function ($log) {
                return $log->causer?->username ?? 'System';
            })

            ->addColumn('event_badge', function ($log) {
                $color = match ($log->event) {
                    'created' => 'bg-success',
                    'updated' => 'bg-warning',
                    'deleted' => 'bg-danger',
                    default => 'bg-secondary',
                };

                return "<span class='badge {$color}'>{$log->event}</span>";
            })

            ->addColumn('action', function ($log) {
                return '
                <button class="btn btn-sm btn-info"
                    data-bs-toggle="modal"
                    data-bs-target="#logModal"
                    onclick=\'showLog(' . json_encode($log->attribute_changes) . ')\'>
                    View
                </button>
            ';
            })

            ->addColumn('date', function ($log) {
                return $log->created_at->format('H:i d/m/y');
            })

            ->rawColumns(['event_badge', 'action']) // 🔥 penting!
            ->make(true);
    }

    
}
