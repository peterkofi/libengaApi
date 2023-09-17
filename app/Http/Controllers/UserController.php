<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //
        $user = User::all();
        return response()->json($user, 200);
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
        //
        $request->validate([
            'name'=>'required',
            'last_name'=>'required',
            'profile'=>'required',
            'address'=>'required',
            'type'=>'required|max:1|min:1',
            'state'=>'required|max:1|min:1',
            'email'=>'required|email|unique:agents,email',
            'password'=>'required|min:3',
            'Autorisation'=>'required|max:1|min:1',
        ]);

        return User::create($request->all());

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
       
        $user = User::find($id);
       
        if($user){

            $data=[
               'donnee'=>$user,
               'statut'=>200
            ];
        }else{
            $data=[
                'message'=>'Aucune donnée',
                'statut'=>404
             ];
        }
        return response()->json($data);
    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
         // modifEmail: 'chrisloma@gmail.com', 
        // modifMotdePasse: null, idAgent: '1'

        //autorisation: 'bloque'

        //return response()->json($request->all());
        $fields= $request->validate([
            'idAgent'=>'required',                       
        ]);

        $idAgent =$fields['idAgent'];
        $modifEmail =$request->modifEmail;
        $modifMotdePasse = $request->modifMotdePasse;
        $autorisation = $request->autorisation;

       
        
        $user = User::find($idAgent);
        
        if($modifMotdePasse && $modifMotdePasse != ""){
            $user->password= bcrypt($modifMotdePasse);   
        }
        if($modifEmail){
            $user->email= $modifEmail;
        }
        if($autorisation && $autorisation=="autorise"){
            $user->Autorisation= 1;   
        }
        

        if($autorisation && $autorisation=="bloque"){
           
            $user->Autorisation= 2;   
        }


        //return response()->json($user);

        $user->update();
       
        return response()->json([
            "message"=>"modification avec success !",
            "status"=>200
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
        $agent = User::find($id);
        $agent->update($request->all());

        return $agent;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
        $agent = User::find($id);
                
        $data=[
            'message'=>'suppression avec success',
            'statut'=>200
        ];

        return response()->json($data);
    }

    public function search($nom)
    {
        //
        $agent = User::where('nom','like','%'.$nom.'%')->get();
                
        $data=[
            'donnee'=>$agent,
            'statut'=>200
        ];

        return response()->json($data);
    }
}
