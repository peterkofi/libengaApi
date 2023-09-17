<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OperationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $operations= Operation::all();
        return response()->json($operations, 200);
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Operation  $operation
     * @return \Illuminate\Http\Response
     */
    public function show(Operation $operation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Operation  $operation
     * @return \Illuminate\Http\Response
     */
    public function edit(Operation $operation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Operation  $operation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Operation $operation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Operation  $operation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Operation $operation)
    {
        //
    }
    
    public function rechercheOperation(Request $request) {
        // operation: 'depot', dataDu: null, dataAu: null
        
        $validator = Validator::make($request->all(),[
            "operation"=>"required"
        ]);

        if($validator->fails()){
            return response()->json([
                "error"=>$validator->messages(),
                "statut"=>400
            ]);
        }else{                   
                $operationMin = strtolower($request->operation);
                $dataDu = $request->dataDu;
                $dataAu = $request->dataAu;

                if($dataDu && !$dataAu){
                    $dataAu="2024-12-30";
                }
        
                if($dataAu && !$dataDu){
                    $dataDu="2010-01-01";
                }

                if(!$dataAu && !$dataDu){
                    $dataDu="2010-01-01";
                    $dataAu="2024-12-30";
                }  

                if($operationMin==='commission'){                    
                    $operation = Operation::whereBetween("date",[$dataDu,$dataAu])->where("montant_touche_client",">",0)->orderBy('montant_touche_client','DESC')->get();
                    return response()->json([
                        "operation"=> $operation,
                        "statut"=> 200,
                    ]);
                }else{
                                    

                    $operation = Operation::whereBetween("date",[$dataDu,$dataAu])->where("Type_operation",$operationMin)->get();
                    
                    return response()->json([
                        "operation"=> $operation,
                        "statut"=> 200,
                    ]);
                    
                }

              

                

        }
        
    }

    public function notificationOperation(Request $request) {
          
        $validator = Validator::make($request->all(),[
            "operation"=>"required"
        ]);

        if($validator->fails()){
            return response()->json([
                "error"=>$validator->messages(),
                "statut"=>400
            ]);
        }else{                   
                $operationMin = strtolower($request->operation);
                $dateNotif = $request->dateNotif;

                if(!$dateNotif){
                    $dateNotif="2024-12-30";
                }
                
                if($operationMin==='commission'){                    
                    $operation = Operation::where("date",$dateNotif)->where("montant_touche_client",">",0)->orderBy('montant_touche_client','DESC')->get();
                    return response()->json([
                        "operation"=> $operation,
                        "statut"=> 200,
                    ]);
                }else if($operationMin=='toutes'){
                    $operation = Operation::where("date",$dateNotif)->orderBy('numCompte','DESC')->get();
                    return response()->json([
                        "operation"=> $operation,
                        "statut"=> 200,
                    ]);
                }else{                        
                        $operation = Operation::where("date",$dateNotif)->where("Type_operation",$operationMin)->orderBy('numCompte','DESC')->get();
                        
                        return response()->json([
                            "operation"=> $operation,
                            "statut"=> 200,
                        ]);
                    
                }

              

                

        }
        
    }

    public function agentOperation($id) {
        // operation: 'depot', dataDu: null, dataAu: null
        
       $operation = Operation::where("user_id",$id)->get();

       return response()->json([
        'operation'=>$operation,
        'statut'=>200
       ], 200);
       
    }
}
