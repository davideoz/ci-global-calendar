<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

use Illuminate\Support\Facades\View;

use \Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
        // FIX THE LOCALE BUG WITH CARBON - https://vegibit.com/what-is-a-view-composer-in-laravel/
        View::composer('*', function ($view) {

            $locale = App::getLocale();
            
            Carbon::setUtf8(true);
            Carbon::setLocale($locale);
            
            // Getting FROM date suffix string
                $fromSuffixString = Carbon::getTranslator()->trans('period_start_date');
                
                if ($fromSuffixString != "period_start_date"){
                    $fromSuffixArray = explode(" :", $fromSuffixString);
                    $fromSuffix = $fromSuffixArray[0];
                }
                else{
                    $fromSuffix = "";
                }
            
            // Getting TO date suffix string
                $toSuffixString = Carbon::getTranslator()->trans('period_end_date');
                
                if ($toSuffixString != "period_end_date"){
                    $toSuffixArray = explode(" :", $toSuffixString);
                    $toSuffix = "- ".$toSuffixArray[0];
                }
                else{
                    $toSuffix = "-";
                }
            
            $view
                ->with('fromSuffix', $fromSuffix)
                ->with('toSuffix', $toSuffix);
        });
        
        
        Blade::directive('date', function ($expression) {
            return "<?php echo date('d/m/Y', strtotime($expression))?>";
        });
        Blade::directive('date_monthname', function ($expression) {
            /*return "<?php echo date('d M Y', strtotime($expression))?>";*/
            return "<?php echo Carbon\Carbon::parse($expression)->isoFormat('D MMM YYYY'); ?>";
        });
        Blade::directive('day', function ($expression) {
            return "<?php echo date('d', strtotime($expression))?>";
        });
        Blade::directive('month', function ($expression) {
            /*return "<?php echo date('M', strtotime($expression))?>";*/
            return "<?php echo Carbon\Carbon::parse($expression)->isoFormat('MMM')?>";
        });
        Blade::directive('time_am_pm', function ($expression) {
            return "<?php echo date('g.i a', strtotime($expression))?>";
        });
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
