<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;


class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

   /**
     * 
     * @OA\Get (
     *     path="/agent",
     *     tags={"agent"},
     *   summary="Affiche Tous agents se trouvant dans la base de données",  
     * @OA\Response(
     *         response=200,
     *         description="success",
     *         
     *     )
     * )
     */

    
    public function index()
    {
        //
       $agent = Agent::all();
       return response()->json($agent, 200);
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

       /**
     * @OA\Post(
     *     path="/agent",
     *     tags={"agent"},
     *     summary="Enregistrer un nouveau agent",
     *     @OA\Response(
     *         response=200,
     *         description="Enregistrement avec success",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="erreur lors de l'enregistremnt"
     *     ),
     *     
     * )
     */


    public function store(Request $request)
    {
     
        $request->validate([
            'nom'=>'required',
            'prenom'=>'required',
            'adresse'=>'required',
            'type'=>'required|max:1|min:1',
            'etat'=>'required|max:1|min:1',
            'email'=>'required|email|unique:agents,email',
            'pass'=>'required|min:3',
            'Autorisation'=>'required|max:1|min:1',
        ]);

        return Agent::create($request->all());

        // return Agent::create([
        //     'nom'=>'kofi',
        //     'prenom'=>'peter',
        //     'photoProfil'=>'',
        //     'adresse'=>'',
        //     'type'=>1,
        //     'etat'=>0,
        //     'email'=>'peterkofi@gmail.com',
        //     'pass'=>1234,
        //     'Autorisation'=>1
      
        // ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
 //   public function show(Agent $agent)
    public function show($id)
    {
        //
        $agent = Agent::find($id);
       
        if($agent){

            $data=[
               'donnee'=>$agent,
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
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function edit(Agent $agent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        //
            $agent = Agent::find($id);
                      
            if($agent){                 
                $data=[
                    'message'=>'mise à jour avec succès !',
                    'statut'=>200
                ];    
                $agent->update($request->all());         
            }else{
                $data=[
                    'message'=>'Aucune donnée',
                    'statut'=>404
                ]; 
            }
    
            return response()->json($data);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        //
        $agent = Agent::find($id);
                
        $data=[
            'message'=>'suppression avec succès !',
            'statut'=>200
        ];

        return response()->json($data);
    }

    public function search($nom)
    {
        //
        $agent = Agent::where('nom','like','%'.$nom.'%')->orWhere("id",$nom)->get();               
       
       
        $data=[
            'donnee'=>$agent,
            'statut'=>200
        ];

        return response()->json($data);
    }

    


}
