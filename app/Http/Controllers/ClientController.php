<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

 /** @OA\Info(title="Libenga API", version="0.1") */

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     /**
     * 
     * @OA\Get (
     *     path="/client",
     *     tags={"client"},
     *   summary="Affiche Tous Client se trouvant dans la base de données",  
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
        $client = Client::all();
        return response()->json($client, 200);
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
     *     path="/client",
     *     tags={"client"},
     *     summary="Enregistrer un nouveau client",
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
 
        $validator = Validator::make($request->all(),[
            "nom"=> "required",
            "prenom"=> "required",
            "Delegue1"=> "required",
            "Delegue2"=> "required",
            "Adresse"=> "required",
            "NumTel"=> "required",
            "Age"=> "required"
        ]);


        
        if($validator->fails()){

            return response()->json([
                "error"=>$validator->messages(),
                "status"=>400
            ]);
        }else{
                  
            $nom = $request->nom;
            $formatNom=str_split($nom,1);
            $initialNom=$formatNom[0];

            $prenom = $request->prenom;
            $formatPrenom=str_split($prenom,1);
            $initialPrenom=$formatPrenom[0];

            $initialNomPrenom=$initialNom."".$initialPrenom;

            $DernierClient = Client::orderBy('id','DESC')->limit(1)->get();

            if($DernierClient!=null){
                $DernierId = 0;
            }else  $DernierId=$DernierClient[0]->id;


            $idClientActuel=$DernierId+1;            

            $NumeroClient =$idClientActuel ."". $initialNomPrenom ."LL". 23;
        
            $client = new Client;

            $client->NumeroClient = $NumeroClient;
            $client->nom = $nom;
            $client->prenom = $prenom;
            $client->Delegue1 = $request->Delegue1;
            $client->Delegue2 = $request->Delegue2;
            $client->NumeroTelephone = $request->NumTel;
            $client->adresse = $request->Adresse;
            $client->DateNaissance = $request->Age;

            $noms =$prenom."_".$nom; 
        

            if($request->hasfile("photoProfil")){

                $file = $request->photoProfil;
                $extension = $file->getClientOriginalExtension(); 
                $file_name =$noms."_". time() .'.'. $extension;

                $file->move('uploads/clients/Profil/',$file_name);
                
                $client->photoProfil =  $file_name ;
                //return response()->json($extension);
            }

            if($request->hasfile("photoSignature")){
                $file = $request->photoSignature;
                $extension = $file->getClientOriginalExtension(); 
                $file_name =$noms."_". time() .'.'. $extension;

                $file->move('uploads/clients/Signature',$file_name);
               
                

                $client->photoSignature =  $file_name;
            // return response()->json($extension);
            }

            $client->save();
            
            return response()->json([
                "client"=>$noms,
                "status"=>200
            ]);
            
            
        }     
        
        
            
       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */

    /**
     *
     * @OA\Get (
     *     path="/client/{idClient}",
     *     tags={"client"},
     *     summary=" Affiche tous les clients ayant l'Id recherché",
     *      @OA\Parameter(
     *         name="idClient",
     *         in="path",
     *         description="idClient designe l'Id recherché",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example="1"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="visualisation du client recherché",
     *     ),
     *      @OA\Response(
     *         response=400,
     *         description="Id Invalide",
     *     ),
     *   @OA\Response(
     *         response=404,
     *         description="le client n'existe pas",
     *     ),
     * )
     */
    public function show($id)
    {
        //
        $client = Client::find($id);
       
        if($client){

            $data=[
               'client'=>$client,
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
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        //
    }

    public function clientCompte()
    {
        //
        $clientComptes=[];
        $clients = Client::all();

        foreach ($clients as $cl) {
            foreach ($cl->comptes as $clc) {
                $clientComptes[$cl->id] = [$clc];
            }
        }
       
        return response()->json($clientComptes, 200);
    }
}
