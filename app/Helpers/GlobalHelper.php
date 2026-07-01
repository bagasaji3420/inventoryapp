<?php

use Illuminate\Support\Facades\Auth;

function isOnline($userId)
{
    return \Illuminate\Support\Facades\Cache::has('user-is-online-' . $userId);
}

function UserStatus()
{

    return Auth::user()->status;
}


if (!function_exists('shortNumber')) {
    function shortNumber($num)
    {
        if ($num >= 1000000) {
            return round($num / 1000000, 1) . 'M';
        }

        if ($num >= 1000) {
            return round($num / 1000, 1) . 'K';
        }

        return $num;
    }
}


function extractImages($html)
{
    preg_match_all('/<img[^>]+src="([^">]+)"/', $html, $matches);
    return $matches[1] ?? [];
}
