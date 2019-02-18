<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title', 'description', 'organized_by', 'category_id', 'venue_id', 'image', 'facebook_event_link', 'website_event_link', 'status', 'repeat_type', 'repeat_until', 'repeat_weekly_on', 'repeat_monthly_on', 'on_monthly_kind'
    ];

    /***************************************************************************/
    /**
     * Get the teachers for the event.
     */
    public function teachers(){
        return $this->belongsToMany('App\Teacher', 'event_has_teachers', 'event_id', 'teacher_id');
    }

    /***************************************************************************/
    /**
     * Get the organizers for the event.
     */
    public function organizers(){
        return $this->belongsToMany('App\Organizer', 'event_has_organizers', 'event_id', 'organizer_id');
    }

    /***************************************************************************/
    /**
     * Get the organizers for the event.
     */
    public function eventRepetitions($type = null)
    {
        return $this->hasMany('App\EventRepetition', 'event_id');
    }

    /***************************************************************************/

    /***************************************************************************/
    /**
     * Return start and end dates of the first repetition of an event 
     *
     * @param  \App\Event  $post
     * @return array the event repetition start and end repeat dates
     */
    public static function getFirstEventRpDates($eventId){
        $ret = DB::table('event_repetitions')
                ->select('start_repeat','end_repeat')
                ->where('event_id',$eventId)
                ->first();
                
        return $ret;
    }
}
