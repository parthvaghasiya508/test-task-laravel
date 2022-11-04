<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Exception;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['login']]);
    }

    public function login(Request $request)
    {

        // validation
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        if (auth()->attempt(["email" => $request->email, "password" => $request->password])) {
            $token = auth()->user()->createToken("_token");

            return response()->json([
                "status" => 1,
                "message" => "Logged in successfully",
                "access_token" => $token->plainTextToken
            ]);
        }
        
        return response()->json([
            "status" => 0,
            "message" => "Invalid credentials"
        ]);
    }

    public function addMoney(Request $request) 
    {
        $data = $request->only('wallet');
        $validator = Validator::make($data, [
            'wallet' => 'required|min:3|max:100|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        try {
            $user = User::find(auth()->user()->id);
            $user->wallet = $user->wallet + $request->wallet;
            $user->save();

            return response()->json([
                'success' => true,
                'user' => $user,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not add wallet.',
            ], 500);
        }
 	
    }

    public function buyCookie(Request $request) 
    {
        $data = $request->only('quantity');
        $validator = Validator::make($data, [
            'quantity' => 'required|min:1|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        try {
            $user = User::find(auth()->user()->id);
            if($user->wallet < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Could not buy wallet, because request quantity is greater then wallet',
                ], 500);
            }
            $count = $user->wallet - $request->quantity;
            $user->wallet = $count;
            $user->save();
            
            return response()->json([
                'success' => true,
                'user' => $user,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not buy wallet.',
            ], 500);
        }
 	
    }
}
