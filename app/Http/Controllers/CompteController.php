<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\Client;
use App\Models\Operation;
use App\Models\Configuration;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

 /**
     * 
     * @OA\Get (
     *     path="/compte",
     *     tags={"compte"},
     *   summary="Affiche Tous les comptes des client se trouvant dans la base de données",  
     * @OA\Response(
     *         response=200,
     *         description="success",
     *         
     *     )
     * )
     */

    public function index()
    {
        $compte = Compte::all();
        return response()->json($compte, 200);

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
     *     path="/compte",
     *     tags={"compte"},
     *     summary="Enregistrer un nouveau compte",
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



        //TypeCompte: 'fixe', devise: 'CDF', mise: '500', idClient: '15'
        
        //
        $validator = Validator::make($request->all(),[
            "TypeCompte"=> "required",
            "devise"=> "required",           
        ]);

        $idClient = $request->idClient;
        $typeCompte = $request->TypeCompte;
        $mise = $request->mise;
        $devise = $request->devise;

        $client=Client::where('id',$idClient)->get();
        $noms = $client[0]->prenom . ' ' . $client[0]->nom;


        
        if($validator->fails()){
            return response()->json([
                "error"=>$validator->messages(),
                "status"=>400
            ]);
        }else{
                $NombreCompteClient = Compte::where('client_id',$idClient)->count();

                if($NombreCompteClient==0){
                    $NouveaNumCompte =1; 
                }else  $NouveaNumCompte=(int) $NombreCompteClient + 1;
                
                if(empty($mise)){
                    $mise=0;
                } 
                // NumeroCompte`, `mise`, `typeCompte`, `devise`, `total`, `dette`, `MontantRetire`, 
                // `CommissionTouche`, `CycleR`, `CycleD`, `NbrCycle`, `Cloture`, `client_id`
                    $NouveauCompte = new Compte;

                    $NouveauCompte->NumeroCompte=$NouveaNumCompte;
                    $NouveauCompte->typeCompte=$typeCompte;
                    $NouveauCompte->mise=$mise;
                    $NouveauCompte->devise=$devise;
                    $NouveauCompte->client_id=$idClient;
                    $NouveauCompte->total=0;
                    $NouveauCompte->dette=0;
                    $NouveauCompte->MontantRetire=0;
                    $NouveauCompte->CommissionTouche=0;
                    $NouveauCompte->CycleR=0;
                    $NouveauCompte->CycleD=0;
                    $NouveauCompte->NbrCycle=0;
                    $NouveauCompte->Cloture=0;
                    // return response()->json("papa");
                    $NouveauCompte->save();
                    if($NouveauCompte){
                        return response()->json([
                            'compte'=>$NombreCompteClient,
                            'noms_client'=>$noms,
                            'status'=>200,
                        ]);
                    }else{
                        return response()->json([
                            "error"=>"problème survenu lors de l'enregistrement ! ",
                            "error_type"=>"enregistrement",
                            "status"=>400
                        ]);

                    }
                  
                   

            
        }     
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Compte  $compte
     * @return \Illuminate\Http\Response
     */

     /**
     *
     * @OA\Get (
     *     path="/compte/{idCompte}",
     *     tags={"compte"},
     *     summary=" Affiche tous les comptes ayant l'Id recherché",
     *      @OA\Parameter(
     *         name="idCompte",
     *         in="path",
     *         description="idCompte designe l'Id recherché",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example="1"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="visualisation du compte recherché",
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
        $compte = Compte::find($id);

        return response()->json([
            "compte"=>$compte,
            "statut"=>200
        ]);
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Compte  $compte
     * @return \Illuminate\Http\Response
     */
    public function edit(Compte $compte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Compte  $compte
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Compte $compte)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Compte  $compte
     * @return \Illuminate\Http\Response
     */

    public function destroy(Compte $compte)
    {
        //
    }

    function compteClient($idClient)  {
        $comptes = Compte::where('client_id',$idClient)
        ->orderBy('id','DESC')->get();
       
        if($comptes){

            $data=[
               'donnee'=>$comptes,
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

    function depotCompte(Request $request){

        // return response()->json($request->all(), 200);

        $validator =Validator::make([
            "operation"=>$request->operation,
            "montant"=>$request->montant,
            "idAgent"=>$request->idAgent,
            "idClient"=>$request->idClient,
            "numCompte"=>$request->numCompte,
            ],[
            "operation"=> "required",
            "montant"=> "required",
            "idAgent"=> "required",
            "idClient"=> "required",
            "numCompte"=> "required"
        ]) ;

        $Type_operation = 'depot';

        if($validator->fails()){
            return response()->json([
               'erros'=> $validator->messages(),
                'status'=>400
            ]);
        }else{
            
           // $compte->client_id = $request->idClient;
            $compte = $request->numCompte;

            $montantMult = (int)$request->montantMult;

                 
            if(isset($montantMult) && $montantMult !=0){
                $MontantAmultiplie =  $montantMult ;
            } else $MontantAmultiplie = 0;

           

           // $compte[0]->mise
           // $compte[0]->typeCompte 
          //  $compte->devise
            // $compte[0]->total 
           // $compte[0]->dette 
          //  $compte->MontantRetire
           // $compte->CommissionTouche 
           // $compte[0]->CycleR 
           // $compte->NbrCycle 
           // $compte->Cloture 

           $montantDepose = $request->montant;    

          // `typeCompte`,`total`,`dette`,`mise`,`CycleD`

           $compte = Compte::where('id',$compte)->get();

           $typeCompte = $compte[0]->typeCompte;
           $idCompte = $compte[0]->id;

           $client_id = $compte[0]->client_id;
           $client=Client::where('id',$client_id)->get();

           $noms = $client[0]->prenom . ' ' . $client[0]->nom;



           $compteMaj = Compte::find($idCompte);

         //  return $compteMaj;


           $NombreDepot = Operation::where([
               ['compte_id','=',$compte],
                ['Type_operation','=', $Type_operation],
            ])->count();

            if ($typeCompte == "fixe") {// compte fixe

                $dette = $compte[0]->dette;
                $mise = $compte[0]->mise;
                $total = $compte[0]->total;
                $CycleD = $compte[0]->CycleD;
                
                $MontantActuel = 0;

                if($MontantAmultiplie>0){
                    $MontantActuel = $MontantAmultiplie * $mise;
                    $NouveauCycleD = $CycleD + (int)$MontantAmultiplie;
              
                }else{                    
                    $MontantActuel= $montantDepose;
                    $NouveauCycleD = $CycleD + 1;
                }             
                
                if ($CycleD < 31) { // pas totaliser un cycle
                    if ($dette > 0) { // dette 
                        if ($dette < $MontantActuel) {
 
                            $ActuelDette = 0;
                            $reste = $MontantActuel - $dette;

                            $totalActuel = $total + $reste;


                            $compteMaj->CycleD = $NouveauCycleD;
                            $compteMaj->dette = $ActuelDette;

                            $compteMaj = $compteMaj->update();

                            if($compteMaj){ // modification OK

                                $operation = new Operation;

                                $jours = date("Y-m-d");
                                
                                $operation->user_id = $request->idAgent;
                                $operation->compte_id = $request->numCompte;
                                $operation->Type_operation =  $Type_operation;;
                                $operation->montant = $MontantActuel;
                                $operation->date = $jours;

                                $operation->save();

                                return response()->json([
                                    'nbr_depot'=>$NouveauCycleD,
                                    'noms_client'=>$noms,
                                    'status'=>200,
                                ]);

                            }else{ // pas de modification

                                return response()->json([
                                    'noms_client'=>$noms,
                                    'status'=> 400,
                                ]);
                            }
                        }else{ // reste un motant après la paye --> dette superieur

                            $ActuelDette = $dette - $MontantActuel;

                            $compteMaj->dette = $ActuelDette;

                            $compteMaj = $compteMaj->update(); 

                            if($compteMaj){ // modification OK

                                $operation = new Operation;

                                $jours = date("d-m-Y");
                                
                                $operation->user_id = $request->idAgent;
                                $operation->compte_id = $request->numCompte;
                                $operation->Type_operation =  $Type_operation;
                                $operation->montant = $MontantActuel;
                                $operation->date = $jours;

                                $operation->save();

                               return response()->json([
                                    'nbr_depot'=>$NouveauCycleD,
                                    'noms_client'=>$noms,
                                    'status'=>200,
                                ]);


                            }else{ // pas de modification
                                 return response()->json([
                                    'noms_client'=>$noms,
                                    'status'=> 400,
                                ]);
                            }
                            
                          
                            
                            
                        }    
                    }else{ //pas de dette

                        $totalActuel = $total + $MontantActuel; 
                        $compteMaj->CycleD = $NouveauCycleD;
                        $compteMaj->total = $totalActuel;

                        $compteMaj = $compteMaj->update(); 

                        if($compteMaj){ // modification OK

                                $operation = new Operation;
                                $jours = date("d-m-Y");
                                $operation->user_id = $request->idAgent;
                                $operation->compte_id = $request->numCompte;
                                $operation->Type_operation =  $Type_operation;
                                $operation->montant = $MontantActuel;
                                $operation->date = $jours;

                                $operation->save();

                                return response()->json([
                                    'nbr_depot'=>$NouveauCycleD,
                                    'noms_client'=>$noms,
                                    'status'=>200,
                                ]);


                        }else{ // pas de modification
                              return response()->json([
                                    'noms_client'=>$noms,
                                    'status'=> 400,
                                ]);
                        }


                    }

                }// un cycle  est atteint
           
            }else{ // compte desordre

                $dette = $compte[0]->dette;
                $total = $compte[0]->total;
                $CycleD = $compte[0]->CycleD;

                $MontantActuel = (int)$montantDepose;
                $NouveauCycleD = $CycleD + 1;

                if ($NombreDepot < 31) { // pas totaliser un cycle
                    if ($dette > 0) { // dette 
                        if ($dette < $MontantActuel) {
                            $ActuelDette = 0;
                            $reste = $MontantActuel - $dette;
                            $totalActuel = $total + $reste;


                            
                            $compteMaj->CycleD = $NouveauCycleD;
                            $compteMaj->dette = $ActuelDette;
                            $compteMaj->total = $totalActuel;
                            
                            $compteMaj = $compteMaj->update();

                            if($compteMaj){ // modification OK

                                $operation = new Operation;

                                

                                $jours = date("d-m-Y");
                                
                                $operation->user_id = $request->idAgent;
                                $operation->compte_id = $request->numCompte;
                                $operation->Type_operation =  $Type_operation;
                                $operation->montant = $MontantActuel;
                                $operation->date = $jours;

                                $operation->save();

                                return response()->json([
                                    'nbr_depot'=>$NouveauCycleD,
                                    'noms_client'=>$noms,
                                    'status'=>200,
                                ]);


                            }else{ // pas de modification
                                 return response()->json([
                                    'noms_client'=>$noms,
                                    'status'=> 400,
                                ]);
                            }
                        }else{ // reste un motant après la paye

                            $ActuelDette = $dette - $MontantActuel;

                            $compteMaj->dette = $ActuelDette;                            
                            $compteMaj = $compteMaj->update();

                            if($compteMaj){ // modification OK

                                $operation = new Operation;

                                $jours = date("d-m-Y");
                                
                                

                                $operation->user_id = $request->idAgent;
                                $operation->compte_id = $request->numCompte;
                                $operation->Type_operation =  $Type_operation;
                                $operation->montant = $MontantActuel;
                                $operation->date = $jours;

                                $operation->save();

                               return response()->json([
                                    'nbr_depot'=>$NouveauCycleD,
                                    'noms_client'=>$noms,
                                    'status'=>200,
                                ]);


                            }else{ // pas de modification
                                 return response()->json([
                                    'noms_client'=>$noms,
                                    'status'=> 400,
                                ]);
                            }

                        }    
    
                    }else{ // pas de dette

                        $totalActuel = $total + $MontantActuel;

                        $compteMaj->CycleD = $NouveauCycleD;                            
                        $compteMaj->total = $totalActuel;                            
                        
                        $compteMaj = $compteMaj->update();

                        if($compteMaj){ // modification OK

                            $operation = new Operation;

                            $jours = date("d-m-Y");
                            
                            $operation->user_id = $request->idAgent;
                            $operation->compte_id = $request->numCompte;
                            $operation->Type_operation =  $Type_operation;
                            $operation->montant = $MontantActuel;
                            $operation->date = $jours;

                            $operation->save();

                           return response()->json([
                                    'nbr_depot'=>$NouveauCycleD,
                                    'noms_client'=>$noms,
                                    'status'=>200,
                                ]);

                        }else{ // pas de modification
                             return response()->json([
                                    'noms_client'=>$noms,
                                    'status'=> 400,
                                ]);
                        }

                    } //fin else pas de dette
                    
                } // un cycle  est atteint
            }
        }
    }

    function retraitCompte(Request $request){
       
        
        $validator =Validator::make([
            "operation"=>$request->operation,
            "montant"=>$request->montant,
            "idAgent"=>$request->idAgent,
            "idClient"=>$request->idClient,
            "numCompte"=>$request->numCompte,
            ],[
            "operation"=> "required",
            "montant"=> "required",
            "idAgent"=> "required",
            "idClient"=> "required",
            "numCompte"=> "required"
        ]) ;

        $Type_operation= 'retrait';
        $compte = $request->numCompte;

        $compte = Compte::where('id',$compte)->get();

      

        $typeCompte = $compte[0]->typeCompte;
        $idCompte = $compte[0]->id;

        $client_id = $compte[0]->client_id;
        $client=Client::where('id',$client_id)->get();

        $noms = $client[0]->prenom . ' ' . $client[0]->nom;

        //return response()->json($compte, 200);
       /// return response()->json($request->all(), 200);

        $compteMaj = Compte::find($idCompte);

    

        if (isset($request->montant)) $montantRetrait = $request->montant;    
        $montantRetrait = (int)$montantRetrait;

        $CommissionValide=0;
        $SoldeReelX=0;

        $configuration = Configuration::where('id',1)->get();

        
        $Commission1=$configuration[0]->Commission1;
        $Commission2=$configuration[0]->Commission2;
        $Taux=$configuration[0]->Taux;

        $typeCompte = $compte[0]->typeCompte;
        $idCompte = $compte[0]->id;
        $total = $compte[0]->total;
        $dette = $compte[0]->dette;
        $typeCompte = $compte[0]->typeCompte;
        $CycleR = $compte[0]->CycleR;
        $NbrCycle=$compte[0]->NbrCycle;
        $mise = $compte[0]->mise;
        $devise=$compte[0]->devise;


        $NombreDepot = Operation::where([
            ['compte_id','=',$compte],
            ['Type_operation','=',$Type_operation],
        ])->count();



        if($typeCompte == "fixe"){// type fixe

            $Commission=$mise * ($NbrCycle+1);
            $SoldeReelX=$total - $Commission; 
            
        }else{ // type desordre

            //conversion devise
      
            $montantConvOuPas=0;

            if($devise="USD"){
                $montantConvOuPas=$total*$Taux;
            }else if($devise="CDF"){
                $montantConvOuPas=$total;
            }

            $CommissionTouche=$compte[0]->CommissionTouche;
                    
            if($montantConvOuPas<50000){
                $CommissionValide= ($Commission1 * $total)  / 100;
                $SoldeReelX=$total-$CommissionValide;
            }else if($montantConvOuPas>=50000){
                $CommissionValide=($Commission2 * $total)  / 100;
                $SoldeReelX=$total-$CommissionValide;
            }    
        }

     

        if( $SoldeReelX > $montantRetrait + $dette){ // on ne viole pas la commision + dette

            if ($typeCompte == "fixe") {
                $dette = $compte[0]->dette;
          
                $NouveauCycleR = $CycleR + 1;


                if ($total >  $montantRetrait + $dette) { // + dette
                            
                    $nouvelleDette=$dette+$montantRetrait;
                    
                    $compteMaj->CycleR = $NouveauCycleR;
                    $compteMaj->dette = $nouvelleDette;

                    

                    $compteMaj = $compteMaj->update();

                     
                    if ($compteMaj) { // modification OK
                        
                        $operation = new Operation;

                        $jours = date("Y-m-d");

                        
                        
                        $operation->user_id = $request->idAgent;
                        $operation->compte_id = $request->numCompte;
                        $operation->Type_operation = $Type_operation;
                        $operation->montant = $montantRetrait;
                        $operation->date = $jours;

                        $operation->save();

                        // return response()->json("papa");

                        return response()->json([
                            'nbr_retrait'=>$NouveauCycleR,
                            'noms_client'=>$noms,
                            'status'=>200,
                        ]);
                      
                    } else { // pas de modification

                        return response()->json([
                            'noms_client'=>$noms,
                            'type'=>'echec retrait',
                            'status'=> 400,
                        ]);
                    }
                    /*} else { // le client a toujours une dette avec ce compte 
                  
                
                 } // fin else où  le client a toujours une dette avec ce compte */
                } else { // total < montant à emprunter
               
                    return response()->json([
                        'noms_client'=>$noms,
                        'type'=>'insuffisance retrait',
                        'status'=>400,
                    ]);              
                }
              //  $NouveauMontant
          
          
          
            }else{ //compte desordre
          
                $dette = $compte[0]->dette;
                $total = $compte[0]->total;
                $CycleR = $compte[0]->CycleR;
            
                $NouveauCycleR = $CycleR + 1;

                // return response()->json([
                //     "total"=>$total,
                //     "montant act"=>$montantRetrait
                // ]);
                if ($total > $montantRetrait) {
                    
                   
                    if ($dette == 0) {
                        $compteMaj->CycleR = $NouveauCycleR;                            
                        $compteMaj->dette = $montantRetrait;

                        
                        $compteMaj = $compteMaj->update();
                
                      
          
                        if($compteMaj){ // modification OK

                            $operation = new Operation;

                            $jours = date("Y-m-d");
                            
                            $operation->user_id = $request->idAgent;
                            $operation->compte_id = $request->numCompte;
                            $operation->Type_operation = $Type_operation;
                            $operation->montant = $MontantActuel;
                            $operation->date = $jours;

                            $operation->save();

                            return response()->json([
                                'nbr_retrait'=>$NouveauCycleR,
                                'noms_client'=>$noms,
                                'status'=>200,
                            ]);
                           
                        }else{
                            return response()->json([
                                'noms_client'=>$noms,
                                'type'=>'echec retrait',
                                'status'=> 400,
                            ]);
                          
                        }  
                    }else{ // le client a toujours une dette avec ce compte 
                            //header("Location:../clients.php?nRetrait=$NouveauCycleR & DetteRetrait=1 & nom=$noms");
                            $soldeReel=$total-$dette;
          
                            if( $montantRetrait<$soldeReel){
                                $detteActuelle= $dette+$montantRetrait;

                                $compteMaj->CycleR = $montantRetrait;                            
                                $compteMaj->dette = $detteActuelle; 
                                                        
                                $compteMaj = $compteMaj->update();

                                if($compteMaj){ // modification OK

                                    $operation = new Operation;

                                    $jours = date("Y-m-d");
                                    
                                    $operation->user_id = $request->idAgent;
                                    $operation->compte_id = $request->numCompte;
                                    $operation->Type_operation = $Type_operation;
                                    $operation->montant = $montantRetrait;
                                    $operation->date = $jours;

                                    $operation->save();

                                    return response()->json([
                                        'nbr_retrait'=>$NouveauCycleR,
                                        'noms_client'=>$noms,
                                        'status'=>200,
                                    ]);
                                  
                                }else{ //echec modification
                                    return response()->json([
                                        'noms_client'=>$noms,
                                        'type'=>'erreur retrait',
                                        'status'=> 400,
                                    ]);
                                }    
                            }else{//motant deuxième retrait depasse le solde Réel  
                                return response()->json([
                                    'noms_client'=>$noms,
                                    'type'=>'erreur deuxième retrait',
                                    'status'=> 400,
                                ]);
                            }
                    }
                                         
                }else{ // total < montant à emprunter
                   
                            return response()->json([
                                'noms_client'=>$noms,
                                'type'=>'insuffisance retrait',
                                'status'=> 400,
                            ]);
                                      
                 }
            }
        
        }else{ // on viole la commision

            return response()->json([
                'noms_client'=>$noms,
                'type'=>'violation comission',
                'status'=> 400,
            ]);
                            
        } // fin else où on viole la commision

    } // fin post retrait

    function clotureCompte(Request $request){
        
        // password: '1234', Operation: 'cloture', idAgent: '_idAgent', 
        // idClient: '15', numCompte: '39'
       
        $validator = Validator::make($request->all(),[
            "idClient"=> "required",
            "numCompte"=> "required",
            "Operation"=> "required",
            "idAgent"=> "required",
            "password"=> "required",

        ]);

        if($validator->fails()){

        }else{

            $numCompte=$request->numCompte;
            $idClient=$request->idClient;
            $Operation=$request->Operation;
            $idAgent=$request->idAgent;
            $password=$request->password;

            $configuration = Configuration::where('id',1)->get();

            

            $pass_retrait=$configuration[0]->pass_retrait;
            $Commission1=$configuration[0]->Commission1;
            $Commission2=$configuration[0]->Commission2;
            $Taux=$configuration[0]->Taux;

            // `NumeroCompte`, `mise`, `typeCompte`, `devise`, 
            // `total`, `dette`, `MontantRetire`, `CommissionTouche`, 
            // `CycleR`, `CycleD`, `NbrCycle`, `Cloture`, `client_id`

            if($password==$pass_retrait){              
                
                
                $compteMaj = Compte::find($numCompte);

                $typeCompte = $compteMaj->typeCompte;                            
                $dette = $compteMaj->dette; 
                $mise = $compteMaj->mise; 
                $total = $compteMaj->total; 
                $devise = $compteMaj->devise; 
                $CycleR = $compteMaj->CycleR; 
                $MontantRetire = $compteMaj->MontantRetire; 
                $CommissionTouche = $compteMaj->CommissionTouche; 
                $Cloture = $compteMaj->Cloture; 
                $Cycle = $compteMaj->NbrCycle; 
                
                $montantConvOuPas=0;
  
                //conversion devise
                if($devise="USD"){
                $montantConvOuPas=$total*$Taux;
                }else if($devise="CDF"){
                $montantConvOuPas=$total;
                }

                $CycleAct= $Cycle +1;

                if($montantConvOuPas<50000){
                    $CommissionValide= ($Commission1 * $total)  / 100;
                }else if($montantConvOuPas>=50000){
                  $CommissionValide=($Commission2 * $total)  / 100;
                }

               
                $MontantAremettre=0;
  
                if($typeCompte=="fixe"){

                    if($dette>0){
                        $MontantAremettre=$total-$dette;
                    }else{
                        $MontantAremettre=$total;
                    }

                    $CommissionToucheActuelle= $mise * $CycleAct ;
  
                    $MontantRetireActuelle=$MontantAremettre - $CommissionToucheActuelle;
                    $SoldeActuelle=$MontantRetireActuelle;
                    $dette=0;
                    
                    $Cloture=1;
                    
                    $compteMaj->CommissionTouche = $CommissionToucheActuelle;    
                    $compteMaj->MontantRetire = $MontantRetireActuelle;    
                    $compteMaj->total = $SoldeActuelle;                     
                    $compteMaj->dette = $dette;    
                    $compteMaj->Cloture = $Cloture;    
                               
                    $compteMaj = $compteMaj->update();

                    if($compteMaj){
                        $operation = new Operation;

                        $jours = date("Y-m-d");
                        $operation->user_id = $idAgent;
                        $operation->compte_id =  $numCompte;
                        $operation->Type_operation = $Operation;
                        $operation->montant = $CommissionToucheActuelle;
                        $operation->montant_touche_client = $SoldeActuelle;
                        $operation->date = $jours;

                        $operation->save();
                        
                        return response()->json([
                            "compte"=>$numCompte,
                            "status"=>200
                        ]);
                    }else{
                        return response()->json([
                            "message"=>"problème survenu lors de la cloture !",
                            "status"=>400
                        ]);
                    }
                }else if($typeCompte=="desordre"){
                    if($dette>0){
                        $MontantAremettre=$total-$dette;
                    }else{
                        $MontantAremettre=$total;
                    }
                    $CommissionToucheActuelle= $CommissionValide ;
                    $MontantRetireActuelle=$MontantAremettre - $CommissionToucheActuelle;
                
                    $SoldeActuelle=$MontantRetireActuelle;
                    $dette=0;
                
                    $Cloture=1;

                    $compteMaj->CommissionTouche = $CommissionToucheActuelle;    
                    $compteMaj->MontantRetire = $MontantRetireActuelle;    
                    $compteMaj->MontantRetire = $SoldeActuelle;    
                    $compteMaj->dette = $dette;    
                    $compteMaj->Cloture = $Cloture;    
                                            
                    $compteMaj = $compteMaj->update();

                    if($compteMaj){
                        $operation = new Operation;

                        $jours = date("Y-m-d");
                        $operation->user_id = $idAgent;
                        $operation->compte_id =  $numCompte;
                        $operation->Type_operation = $Operation;
                        $operation->montant = $CommissionToucheActuelle;
                        $operation->montant_touche_client = $SoldeActuelle;
                        $operation->date = $jours;

                        $operation->save();
                        
                        return response()->json([
                            "compte"=>$numCompte,
                            "status"=>200
                        ]);

                        return response()->json([
                            "compte"=>$numCompte,
                            "status"=>200
                        ]);
                    }else{
                        return response()->json([
                            "message"=>"problème survenu lors de la cloture !",
                            "status"=>400
                        ]);
                    }


                }    

            }else{
                return response()->json([
                    "message"=>"saisissez un bon mot de passe !",
                    "status"=>400
                ]);
            }    
            
                 
         
    
        }

       

        // $validator =Validator::make([
        //     "MdpclotureCompte"=>$request->MdpclotureCompte,
        //     "idAgentclotureCompte"=>$request->idAgentclotureCompte,
        //     "idClientclotureCompte"=>$request->idClientclotureCompte,
        //     "numCompteclotureCompte"=>$request->numCompteclotureCompte,
        //     "operation"=>$request->operation,
        //     ],[
        //     "operation"=> "required",
        //     "montant"=> "required",
        //     "idAgent"=> "required",
        //     "idClient"=> "required",
        //     "numCompte"=> "required"
        // ]) ;

        // $MdpclotureCompte = $request->MdpclotureCompte;
        // $idAgentclotureCompte = $request->idAgentclotureCompte;
        // $idClientclotureCompte = $request->idClientclotureCompte;
        // $numCompteclotureCompte = $request->numCompteclotureCompte;
        // $Type_operation = $request->operation;


        // $configuration = Configuration::where('id',1)->get();

        // $Mdp_retrait = $configuration[0]->pass_retrait ;

        // if($validator->fails()){
        //     return response()->json([
        //        'erros'=> $validator->messages(),
        //         'status'=>400
        //     ]);
        // }else{
        //     if($MdpclotureCompte == $Mdp_retrait){

        //         $Commission1 = $configuration[0]->Commission1;     
        //         $Commission2 = $configuration[0]->Commission2;     
        //         $Taux = $configuration[0]->Taux;   

        //         return response()->json([
        //             'com1'=>$Commission1,
        //             'com2'=>$Commission2
        //         ], 200);  


        //     }else{
        //         return response()->json([
        //             'erros'=> 'mot de passe incorrect !',
        //             'status'=>400
        //         ]);  
        //     }
            

        // }

    }
        


    function coutCompte($compte,$operation){
        $acienneOperation = Operation::where([
            ['numCompte','=',$compte],
            ['Type_Operation','=',$operation],
        ])->count();

        return response()->json([
            "nombre " => $acienneOperation,
            "statut" => 200
        ]);
    }
    
}
