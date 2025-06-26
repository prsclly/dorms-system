<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
      $fileName = 'verticalMenu.json'; // default

      if (Auth::guard('resident')->check()) {
          $fileName = 'verticalMenuResident.json';
      } elseif (Auth::guard('admin')->check()) {
          $fileName = 'verticalMenu.json';
      } elseif (Auth::guard('technician')->check()) {
          $fileName = 'verticalMenu.json'; 
      }

      $filePath = base_path("resources/menu/{$fileName}");

      if (file_exists($filePath)) {
          $menuData = json_decode(file_get_contents($filePath));
          View::share('menuData', [$menuData]);
      } else {
          View::share('menuData', []);
      }
  }
}
