<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class AuthController extends Controller
{
    public function registerUser(Request $request){
        try{
            DB::beginTransaction(); 
            $userId=DB::table('users')->insertGetId([
                'username' => $request['username'],
                'email' => $request['email'],
                'password' => $request['password'],
                'created_at' => now(),
            ]);
           
            if($userId){
                $lastId=DB::table('users_cellphone')->insertGetId([
                    'number' => $request['phoneNumber'],
                    'user_id'=>$userId,
                    'created_at' => now(),
                ]);
                if($lastId){
                    DB::table('users_information')->insertGetId([
                        'user_id'=>$userId,
                        'first_name' => $request['name'],
                        'last_name' => $request['lastName'],
                        'created_at' => now(),
                    ]);
                }
            }
           
            
            DB::commit(); // Confirmar la transacción
            return response()->json([
                'success' => 'success',
                'message' => 'User registered successfully'
            ], 200);
        }catch(\Exception $e){
            DB::rollback(); // Revertir la transacción en caso de excepción

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function loginUser(Request $request){
        try{
            $user=DB::table('users as u')->where('email',$request['email'])->
            join('users_information','u.id','=','user_id')->first();
            if($user){
                if($user->password==$request['password']){
                    $params=[
                        $user->id,
                    ];
                    $userContacts=DB::select('call sp_get_user_contacts(?)',$params);
                    return response()->json([
                        'success' => 'success',
                        'message' => 'User logged successfully',
                        'user'=>$user,
                        'contacts'=>$userContacts
                    ], 200);
                }else{
                    return response()->json([
                        'success' => false,
                        'message' => 'Password incorrect'
                    ], 500);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 500);
            }
        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 501);
        }
    }
}
