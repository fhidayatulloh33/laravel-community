<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class DashboardAnalytics extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dashboard-analytics';

    //protected static $navigationLabel = 'Dashboard Analytics';

    //protected static $navigationGroup = 'Analytics';

    //protected static $navigationSort = 1;

    public function getData(): array
    {
        return [
            'postCount' => Post::count(),
            'commentCount' => Comment::count(),
            'userCount' => User::count(),
        ];
    }
}
