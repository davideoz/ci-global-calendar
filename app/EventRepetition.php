<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventRepetition extends Model
{
    protected $fillable = [
        'event_id', 'start_repeat', 'end_repeat'
    ];

    public function user()
    {
        return $this->belongsTo('App\Event', 'event_id', 'id');
    }
    
    
    /***************************************************************************/
    /**
     * Get for each event the first event repetition in the near future (JUST THE QUERY to use as SUBQUERY)
     *
     * @param  $searchStartDate - The start date of the interval
     * @param  $searchEndDate - The end date of the interval
     * @return $ret - string - The query string
     */
     public static function getLastestEventsRepetitionsQuery($searchStartDate, $searchEndDate){
         $ret = EventRepetition::
                     selectRaw('event_id, MIN(id) AS rp_id, start_repeat, end_repeat')
                     ->when($searchStartDate, function ($query, $searchStartDate) {
                         return $query->where('event_repetitions.start_repeat', '>=',$searchStartDate);
                     })
                     ->when($searchEndDate, function ($query, $searchEndDate) {
                         return $query->where('event_repetitions.end_repeat', '<=', $searchEndDate);
                     })
                     ->groupBy('event_id');
         
         return $ret;
     }
}
