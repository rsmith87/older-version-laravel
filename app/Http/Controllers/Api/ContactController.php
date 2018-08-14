<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Contact;
use App\Http\Resources\ContactResource;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $contacts = Contact::all();
      return ContactResource::collection($contacts);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $resource = new ContactResource(Contact::find($id));
      return $resource;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $resource = new ContactResource(Contact::find($id));
      //run updaates to model resource here
      //may need to see if it provides a way to solidily update record
      //or if it updates transmuted record
      return $resource;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      //logic to delete data
      $resource = new ContactResource(Contact::find($id));
      //$resource->delete();
      return $resource;
    }
}
