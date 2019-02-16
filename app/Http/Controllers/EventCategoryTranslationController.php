<?php

namespace App\Http\Controllers;

use App\EventCategory;
use App\EventCategoryTranslation;

use Illuminate\Http\Request;

use Validator;

class EventCategoryTranslationController extends Controller
{
    /* Restrict the access to this resource just to logged in users */
    public function __construct(){
        $this->middleware('admin');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($eventCategoryId, $languageCode){
        $selectedLocaleName = $this->getSelectedLocaleName($languageCode);
        
        return view('eventCategoryTranslations.create')
                ->with('eventCategoryId',$eventCategoryId)
                ->with('languageCode',$languageCode)
                ->with('selectedLocaleName',$selectedLocaleName);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        // Validate form datas
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

        $eventCategoryTranslation = new EventCategoryTranslation();
        $eventCategoryTranslation->event_category_id = $request->get('event_category_id');
        $eventCategoryTranslation->locale = $request->get('language_code');
        
        $eventCategoryTranslation->name = $request->get('name');
        $eventCategoryTranslation->slug = str_slug($eventCategoryTranslation->name, '-');

        $eventCategoryTranslation->save();
        
        return redirect()->route('eventCategories.index')
                        ->with('success','Translation created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EventCategoryTranslation  $eventCategoryTranslation
     * @return \Illuminate\Http\Response
     */
    public function show(EventCategoryTranslation $eventCategoryTranslation){
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EventCategoryTranslation  $eventCategoryTranslation
     * @return \Illuminate\Http\Response
     */
    public function edit($eventCategoryId, $languageCode){
        $eventCategoryTranslation = EventCategoryTranslation::where('event_category_id', $categoryId)
                        ->where('locale', $languageCode)
                        ->first();
                        
        $selectedLocaleName = $this->getSelectedLocaleName($languageCode);
        
        return view('eventCategoryTranslations.edit',compact('eventCategoryTranslation'))
                    ->with('eventCategoryId',$eventCategoryId)
                    ->with('languageCode',$languageCode)
                    ->with('selectedLocaleName',$selectedLocaleName);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EventCategoryTranslation  $eventCategoryTranslation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request){
        request()->validate([
            'name' => 'required',
        ]);

        $categoryTranslation = EventCategoryTranslation::where ('id', $request->get('event_category_translation_id'));

        $event_category_t['name'] = $request->get('name');
        $event_category_t['slug'] = str_slug($request->get('name'), '-');

        $eventCategoryTranslation->update($event_category_t);

        return redirect()->route('eventCategories.index')
                        ->with('success','Translation updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EventCategoryTranslation  $eventCategoryTranslation
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventCategoryTranslation $eventCategoryTranslation){
        //
    }
}
