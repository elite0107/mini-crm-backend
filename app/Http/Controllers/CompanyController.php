<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

// import model
use App\Models\Company;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response()->json(([
            'message' => 'Operation success',
            'companies' => Company::all()
        ]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'string|email|max:100|unique:companies',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $name = $request->file('logo')->getClientOriginalName();
        $path = $request->file('logo')->store('public');

        $company = Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'logo' => $path,
            'website' => $request->website
        ]);

        return response()->json([
            'message' => 'Company successfully created',
            'user' => $company
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json([
            'message' => 'Operation success',
            'company'=> Company::where('id', $id)->first()
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return response()->json([
            'message' => 'Operation success',
            'company'=> Company::where('id', $id)->first()
        ], 200);
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'string|email|max:100|unique:companies',
            'logo' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $company = Company::find('id', $id);
        $company->name = $request->name;
        $company->email = $request->email;
        $company->logo = $request->logo;
        $company->website = $request->website;
        $company->save();

        return response()->json([
            'message'=> 'Company successfully Updated',
            'company' => $company
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Company::destroy($id);

        return response()->json([
            'message' => 'Company successfully destroyed',
            'company' => $company
        ]);
    }
}