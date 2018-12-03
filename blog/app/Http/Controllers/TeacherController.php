<?php

namespace App\Http\Controllers;

use App\Teacher;
use App\Country;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $countries = Country::pluck('name', 'id');

        $searchKeywords = $request->input('keywords');
        $searchCountry = $request->input('country_id');

        // Show just to the owner - Get created_by value if the user is not an admin or super admin
        $user = Auth::user();
        $createdBy = (!$user->isSuperAdmin()&&!$user->isAdmin()) ? $user->id : 0;

        if ($searchKeywords||$searchCountry){
            $teachers = DB::table('teachers')
                ->when($createdBy, function ($query, $createdBy) {
                    return $query->where('created_by', $createdBy);
                })
                ->when($searchKeywords, function ($query, $searchKeywords) {
                    return $query->where('name', $searchKeywords)->orWhere('name', 'like', '%' . $searchKeywords . '%');
                })
                ->when($searchCountry, function ($query, $searchCountry) {
                    return $query->where('country_id', '=', $searchCountry);
                })
                ->paginate(20);
        }
        else
            $teachers = Teacher::latest()
            ->when($createdBy, function ($query, $createdBy) {
                return $query->where('created_by', $createdBy);
            })
            ->paginate(20);

        return view('teachers.index',compact('teachers'))
            ->with('i', (request()->input('page', 1) - 1) * 20)->with('countries', $countries)->with('searchKeywords',$searchKeywords)->with('searchCountry',$searchCountry);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $countries = Country::pluck('name', 'id');
        return view('teachers.create')->with('countries', $countries);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        request()->validate([
            'name' => 'required'
        ]);

        $this->saveOnDb($request);

        return redirect()->route('teachers.index')
                        ->with('success','Teacher created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function show(Teacher $teacher){
        return view('teachers.show',compact('teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit(Teacher $teacher){
        $countries = Country::pluck('name', 'id');

        return view('teachers.edit',compact('teacher'))->with('countries', $countries);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teacher $teacher){
        request()->validate([
            'name' => 'required'
        ]);

        $teacher->update($request->all());

        return redirect()->route('teachers.index')
                        ->with('success','Teacher updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher){
        $teacher->delete();
        return redirect()->route('teachers.index')
                        ->with('success','Teacher deleted successfully');
    }

    /**
     * Save the record on DB
     *
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
     public function saveOnDb($request){
         $teacher = new Teacher();

         $teacher->name = $request->get('name');
         $teacher->bio = $request->get('bio');
         $teacher->country_id = $request->get('country_id');
         $teacher->year_starting_practice = $request->get('year_starting_practice');
         $teacher->year_starting_teach = $request->get('year_starting_teach');
         $teacher->significant_teachers = $request->get('significant_teachers');
         $teacher->image = $request->get('image');
         $teacher->website = $request->get('website');
         $teacher->facebook = $request->get('facebook');

         $teacher->created_by = \Auth::user()->id;
         $teacher->slug = str_slug($teacher->name, '-').rand(10000, 100000);

         $teacher->save();
     }

    /**
     * Open a modal in the event view when create teachers is clicked
     *
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function modal(){
        $countries = Country::pluck('name', 'id');
        return view('teachers.modal')->with('countries', $countries);
    }

    /**
     * Store a newly created teacher from the create event view modal in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFromModal(Request $request){
        request()->validate([
            'name' => 'required'
        ]);

        $this->saveOnDb($request);

        return redirect()->back()->with('message', 'Teacher created');
        //return redirect()->back()->with('message', __('auth.successfully_registered'));
        //return true;
    }
}
