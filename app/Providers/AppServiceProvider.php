<?php

namespace App\Providers;
use App\Clients;
use App\FooterLink;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
		Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        View::composer(['website.*'], function ($view) {
            // $clients = Clients::where("is_active","1")->whereNotNull('image')->where('image', '!=', '')->take(10)->get();
            // $footer_links = FooterLink::where("is_active","1")->orderBy('display_order', 'asc')->get();
            $view->with([
                'clients' => Clients::where("is_active","1")->whereNotNull('image')->where('image', '!=', '')->get(),
                'footer_links' => FooterLink::where("is_active","1")->orderBy('display_order', 'asc')->get(),
                'aboutusMenus' => \App\About::where("is_active","1")->orderBy('display_order', 'asc')->get()
            ]);
        });
    }
}
