<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function register(Request $request){

        
        // return response()->json($request->all());
        // name: 'bulas', last_name: 'emma', 
        // address: 'a1245', email: 'emmabulas@gmail.com'
      

        $fields= $request->validate([
            'name'=>'required',
            'last_name'=>'required',
            'address'=>'required',            
            'email'=>'required|email|unique:agents,email',
            'password'=>'required|min:3|confirmed',           
        ]);


            //  return response()->json($fields['name']); 
            $name =$fields['name'];
            $last_name =$fields['last_name'];
            $names = $last_name . "" . $name;

            $user_profil="";
            $user_type=1;   //agent  2-> Admin
            $user_state=1;     //en ligne 2-> Admin Deconnecté
            $user_autorisation=1; //autorisé 2-> non autorisé


            if($request->hasFile("profile")){
                $file = $request->profile;
                $extension = $file->getClientOriginalExtension();

                $file_name =$names."_". time() .'.'. $extension; 
                $file->move('uploads/agents/Profil',$file_name);
                
                $user_profil=$file_name;

            }

            $user = User::create([
                'name'=>$fields['name'],
                'last_name'=>$fields['last_name'],
                'profile'=>$user_profil,
                'address'=>$fields['address'],
                'type'=>$user_type,
                'state'=>$user_state,
                'email'=>$fields['email'],
                'password'=>bcrypt($fields['password']), 
                'Autorisation'=>$user_autorisation
            ]);

            $token = $user->createToken('mallrdc')->plainTextToken;

            $response=[ 
            'user'=> $user,
            'token'=>$token
            ];

            return response()->json($response);
      

    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return [
            "message"=>"deconnecté"
        ];
    }

    public function login(Request $request){
        
        if($request->email && $request->password){

            $valide = $request->validate([
                "email"=>"required|string",
                "password"=>"required|string",
            ]);


            $user= User::where("email", $valide["email"])->first();

           
            if(!$user || !Hash::check($valide["password"], $user->password)){
                return response()->json([
                    "message"=>"utilisateur invalid !",
                    "status"=>400
                ]);                
            }
            if($user->Autorisation != 1){
                // return response()->json([
                //     "message"=>"utilisateur non autorisé"
                // ],401);
                return response()->json([
                    "message"=>"utilisateur n'est pas autorisé !",
                    "status"=>400
                ]);
                
            }
            
            $token = $user->createToken('mallrdc')->plainTextToken;

            $response=[
                "user"=>$user,
                "token"=>$token
            ];

            return response()->json($response,201);
        }else{
            return response()->json([
                "message"=>"entrer l'email et le mot de passe !",
                "status"=>400
            ]);
          }
    }
}