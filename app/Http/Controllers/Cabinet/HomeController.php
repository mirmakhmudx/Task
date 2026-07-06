<?php

namespace App\Http\Controllers\Cabinet;

use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Dialog\Dialog;
use App\Entity\Banner\Banner;
use App\Entity\Ticket\Ticket;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $advertByStatus = Advert::where('user_id', $userId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $unreadMessages = (int) Dialog::where('user_id', $userId)->sum('user_new_messages')
            + (int) Dialog::where('client_id', $userId)->sum('client_new_messages');

        $stats = [
            'adverts' => [
                'total'      => (int) $advertByStatus->sum(),
                'draft'      => (int) ($advertByStatus[Advert::STATUS_DRAFT] ?? 0),
                'moderation' => (int) ($advertByStatus[Advert::STATUS_MODERATION] ?? 0),
                'active'     => (int) ($advertByStatus[Advert::STATUS_ACTIVE] ?? 0),
                'closed'     => (int) ($advertByStatus[Advert::STATUS_CLOSED] ?? 0),
            ],
            'favorites'      => Auth::user()->favorites()->count(),
            'unreadMessages' => $unreadMessages,
            'openTickets'    => Ticket::where('user_id', $userId)->where('status', Ticket::STATUS_OPEN)->count(),
            'banners'        => Banner::where('user_id', $userId)->count(),
        ];

        $recentAdverts = Advert::where('user_id', $userId)
            ->with(['category', 'region'])
            ->latest('id')
            ->take(5)
            ->get();

        return view('cabinet.home', compact('stats', 'recentAdverts'));
    }
}
