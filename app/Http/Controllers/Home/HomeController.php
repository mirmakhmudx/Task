<?php

namespace App\Http\Controllers\Home;

use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Banner\Banner;
use App\Entity\Page\Page;
use App\Entity\Region\Region;
use App\Entity\Ticket\Ticket;
use App\Http\Controllers\Controller;
use App\Models\User;

class HomeController extends Controller
{
    public function dashboard()
    {
        $advertByStatus = Advert::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');
        $userByStatus   = User::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');
        $ticketByStatus = Ticket::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');
        $bannerByStatus = Banner::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');

        $stats = [
            'users' => [
                'total'  => (int) $userByStatus->sum(),
                'active' => (int) ($userByStatus[User::STATUS_ACTIVE] ?? 0),
                'wait'   => (int) ($userByStatus[User::STATUS_WAIT] ?? 0),
            ],
            'adverts' => [
                'total'      => (int) $advertByStatus->sum(),
                'draft'      => (int) ($advertByStatus[Advert::STATUS_DRAFT] ?? 0),
                'moderation' => (int) ($advertByStatus[Advert::STATUS_MODERATION] ?? 0),
                'active'     => (int) ($advertByStatus[Advert::STATUS_ACTIVE] ?? 0),
                'closed'     => (int) ($advertByStatus[Advert::STATUS_CLOSED] ?? 0),
            ],
            'tickets' => [
                'total'    => (int) $ticketByStatus->sum(),
                'open'     => (int) ($ticketByStatus[Ticket::STATUS_OPEN] ?? 0),
                'approved' => (int) ($ticketByStatus[Ticket::STATUS_APPROVED] ?? 0),
                'closed'   => (int) ($ticketByStatus[Ticket::STATUS_CLOSED] ?? 0),
            ],
            'banners' => [
                'total'  => (int) $bannerByStatus->sum(),
                'active' => (int) ($bannerByStatus[Banner::STATUS_ACTIVE] ?? 0),
            ],
            'categories' => Category::count(),
            'regions'    => Region::count(),
            'pages'      => Page::count(),
        ];

        $moderationAdverts = Advert::where('status', Advert::STATUS_MODERATION)
            ->with(['user', 'category'])
            ->latest('id')
            ->take(5)
            ->get();

        $openTickets = Ticket::where('status', Ticket::STATUS_OPEN)
            ->with('user')
            ->latest('id')
            ->take(5)
            ->get();

        return view('admin.home.home', compact('stats', 'moderationAdverts', 'openTickets'));
    }

    public function index()
    {
        return view('home');
    }
}
