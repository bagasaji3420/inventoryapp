<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class SecurityController extends Controller
{

    public function index()
    {
        $sessions = DB::table('sessions')
            ->where('user_id', Auth::id())
            ->get()
            ->map(function ($session) {

                $agent = $session->user_agent;

                // simple detection (basic dulu)
                $browser = str_contains($agent, 'Chrome') ? 'Chrome' : 'Unknown';
                $os = str_contains($agent, 'Windows') ? 'Windows' : (str_contains($agent, 'Android') ? 'Android' : (str_contains($agent, 'iPhone') ? 'iPhone' : (str_contains($agent, 'Mac') ? 'MacOS' : 'Unknown')));

                return (object)[
                    'id'=> $session->id,
                    'browser' => $browser,
                    'os' => $os,
                    'ip' => $session->ip_address,
                    'last_activity' => Carbon::createFromTimestamp($session->last_activity)
                        ->format('d M Y H:i'),
                ];
            });

        // dd($sessions);

        $title = 'Security';

        return view('Admin.User.security', compact('sessions', 'title'));
    }

    public function destroySession(Request $request, $id)
    {
        $currentSessionId = $request->session()->getId();

        DB::table('sessions')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        // kalau yang dihapus adalah session device sekarang
        if ($id === $currentSessionId) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login')->with('success', 'You have been logged out');
        }

        return back()->with('success', 'Session terminated');
    }
}
