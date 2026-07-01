<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\UserNotification;
use RealRashid\SweetAlert\Facades\Alert;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->paginate(10);

        $title = 'Notifications';

        return view('Admin.User.notification', compact('notifications', 'title'));
    }
    
    public function homeNotif()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->paginate(10);

        $title = 'Notifications';

        return view('Home.notif', compact('notifications', 'title'));
    }

    public function destroy($id)
    {
        Auth::user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail()
            ->markAsRead();

        Auth::user()
            ->notifications()
            ->where('id', $id)
            ->delete();

        return back();
    }

    public function destroyAll()
    {
        Auth::user()->notifications()->delete();

        Alert::success('Success', 'All notifications cleared');

        return back();
    }
}
