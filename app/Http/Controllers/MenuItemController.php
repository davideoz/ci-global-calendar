<?php

namespace App\Http\Controllers;

use App\MenuItem;
use App\Menu;

use Illuminate\Http\Request;
use Validator;

class MenuItemController extends Controller
{
    
    /* Restrict the access to this resource just to logged in users, except some */
    public function __construct(){
        $this->middleware('admin', ['except' => ['updateOrder']]);
    }
    
    /***************************************************************************/
    /**
     * Display a listing of the resource.
     * @param  $id - the menu id
     * @return \Illuminate\Http\Response
     */
    public function index($id){
        
        $selectedMenuName = Menu::find($id)->name;
        $menuItems = MenuItem::where('menu_id','=',$id)
                                ->oldest()->get();
        //dump($menuItems);
        $menuItemsTree = array();
        foreach ($menuItems as $key => $menuItem) {
            if (!$menuItem['parent_item_id']){ // First level item
                array_push($menuItemsTree, $menuItem);
            }
            else{  // Sub item
                //dd($key);
                $parentItemId = $this->findParentItem($menuItemsTree,$menuItem['parent_item_id']);
                //dd($menuItemsTree[$parentItemId]);
                //$menuItemsTree[$parentItemId]['subItems'] = "ciao";
                $subItemsArray = $menuItemsTree[$parentItemId]['subItems'];
                $subItemsArray[] = $menuItem;
                $menuItemsTree[$parentItemId]['subItems'] = $subItemsArray;
                //$menuItemsTree[$parentItemId]['subItems'][] = $menuItem;
                //$menuItemsTree[$parentItemId]['subItems'][] = $menuItem;
                //dd($menuItemsTree);
                
            }
        }
        //dump($menuItemsTree);
        
        //dump($menuItems);
        
        return view('menuItems.index',compact('menuItems'))
                    ->with('selectedMenuName', $selectedMenuName);
        
    }
    
    /***************************************************************************/
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        
        $menu = Menu::orderBy('name')->pluck('name', 'id');
        $menuItems = MenuItem::orderBy('name')->pluck('name', 'id');
        //$menuItemsOrder = $this->getMenuItemsOrder();
        
        return view('menuItems.create')
            ->with('menuItems',$menuItems)
            ->with('menu',$menu);
    }
    
    /***************************************************************************/
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        // Validate form datas
            $validator = Validator::make($request->all(), [
                'name' => 'required'
            ]);
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

        $menuItem = new MenuItem();
        $this->saveOnDb($request, $menuItem);
        //dd($request->menu_id);
        
        return redirect()->route('menuItemsIndex', ['id' => $request->menu_id] )
                        ->with('success',__('messages.menu_added_successfully'));
        
    }

    /***************************************************************************/
    /**
     * Display the specified resource.
     *
     * @param  \App\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function show(MenuItem $menuItem){
        return view('menuItems.show',compact('menuItem'));
    }
    
    /***************************************************************************/
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function edit(MenuItem $menuItem){
        
        $menu = Menu::orderBy('name')->pluck('name', 'id');
        $menuItems = MenuItem::orderBy('name')->pluck('name', 'id');
        
        return view('menuItems.edit',compact('menuItem'))
                    ->with('menuItems',$menuItems)
                    ->with('menu',$menu);
    }

    /***************************************************************************/
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MenuItem $menuItem){

        request()->validate([
            'name' => 'required',
        ]);
        
        $this->saveOnDb($request, $menuItem);

        return redirect()->route('menuItemsIndex', ['id' => $request->menu_id] )
                        ->with('success',__('messages.menu_updated_successfully'));
    }
    
    /***************************************************************************/
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(MenuItem $menuItem){
        $menuItem->delete();
        
        return redirect()->route('menuItems.index')
                        ->with('success',__('messages.menu_item_deleted_successfully'));
    }

    /***************************************************************************/
    /**
     * Save/Update the record on DB
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string $ret - the ordinal indicator (st, nd, rd, th)
     */

    function saveOnDb($request, $menuItem){
        $menuItem->name = $request->get('name');
        $menuItem->compact_name = str_slug($request->get('name'), '-');   
        $menuItem->parent_item_id = $request->get('parent_item_id');
        $menuItem->url = $request->get('url');
        $menuItem->font_awesome_class = $request->get('font_awesome_class');
        $menuItem->lang_string = $request->get('lang_string');
        $menuItem->route = $request->get('route');
        $menuItem->type = $request->get('type');
        $menuItem->menu_id = $request->get('menu_id');
        $menuItem->access = $request->get('access');

        $menuItem->save();
    }

    /***************************************************************************/
    /**
     * Update the menu items order on DB (called by /resources/js/components/UlListDraggable.vue)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string $ret - the ordinal indicator (st, nd, rd, th)
     */

    function updateOrder(Request $request){
        
        foreach ($request->items as $key => $item) {
            $item['order'] = $key+1;
            $menuItem = MenuItem::find($item['id']);
            
            $menuItem->update($item);   
        }
    }

    /***************************************************************************/
    /**
     * find the element that correspont to the specified key
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string $key - the index of the parent item
     */

    function findParentItem($menuItemsTree, $parentItemId){
        foreach ($menuItemsTree as $key => $menuItem) {
            if ($menuItem->id == $parentItemId){
                return $key;
            }
        }
    }

}
