<?php

namespace App\Http\Controllers;

use App\filed;
use Illuminate\Http\Request;

class FiledController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filed = filed::all()->toArray();
        return array_reverse($filed);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // $fileName = time().'.'.$request->file('image')->getClientOriginalExtension();
        // $path = $request->file('image')->move(public_path('upload'), $fileName);
        // $extension = $request->file('image')->extension();
        // $photourl =url('/'.$extension);


      $image = request()->file('image');
      $imageName =$image->getClientOriginalName();
      $imageName = time().'_'.$imageName;
      $image->move(public_path('/images'),$imageName);
      print_r($imageName);
        
        // $request ->validate([
        //     'name' => ['required'],
        //     'image' =>['required']
           
        // ]);
    
        filed::create([
            'name' => $request -> name,
            'image'=> 'images/'.$imageName
  
    
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\filed  $filed
     * @return \Illuminate\Http\Response
     */
    public function show(filed $filed)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\filed  $filed
     * @return \Illuminate\Http\Response
     */
    public function edit(filed $filed)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\filed  $filed
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $filed = filed::find($id);

        // This is like $request->all except it doesn't set the avatar
        // You "fill" the new data so that it doesn't call save yet.
        $filed->fill($request->except('image'));
        
        // If there is a file, we set the avatar
        if( $request->hasFile('image')) {
            
        $image = request()->file('image');
        $imageName =$image->getClientOriginalName();
        $imageName = time().'_'.$imageName;
        $image->move(public_path('/images'),$imageName);
        print_r($imageName);
           
      
          
            $filed->image = 'images/'.$imageName ;
        }
        
        // Then we just save
        $filed->save();
 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\filed  $filed
     * @return \Illuminate\Http\Response
     */
    public function destroy(filed $filed)
    {
        //
    }
}
