<?php

namespace App\Http\Controllers;

use App\Statistic;

use Carbon\Carbon;

// DELETE THIS, WERE USED FOR THE STORE METHOD
use DB;
use App\User;
use App\Teacher;
use App\Organizer;
use App\Event;

use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    /* Restrict the access to this resource just to admin, the store method is called by Laravel Forge Deamon */
    public function __construct()
    {
        $this->middleware('admin', ['except' => ['store']]);
    }
    
    /***************************************************************************/

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lastUpdateStatistic = Statistic::find(\DB::table('statistics')->max('id'));
    
        return view('stats.index')
            ->with('statsDatas', $lastUpdateStatistic);    
    }
    
    /***************************************************************************/


    // REMOVE THIS METHOD - HAS BEEN SUBSTITUTED BY THE STATIC METHOD UPDATE STATISTICS IN THE STATISTIC MODEL

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $todayDate = Carbon::now()->format('d-m-Y');
        $lastUpdateStatistic = Statistic::find(\DB::table('statistics')->max('id'));
        $lastUpdateDate = ($lastUpdateStatistic != null) ? $lastUpdateStatistic->created_at->format('d-m-Y') : null;
        
        if ($lastUpdateDate != $todayDate){
            $statistics = new Statistic();
            $statistics->registered_users_number = User::count();
            $statistics->organizers_number = Organizer::count();
            $statistics->teachers_number = Teacher::count();
            $statistics->active_events_number = Event::getActiveEvents()->count();

            $statistics->save();

            dd("statistics updated");
        }
        else{
            dd("the statistics have been already updated today");
        }
    }
    
    
    
    
<<<<<<< HEAD
=======
    
>>>>>>> statsGeneral
}
