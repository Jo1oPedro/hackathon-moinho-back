<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersFormRequest;
use App\Models\Institution;
use App\Models\Professional;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\UsersFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsersFormRequest $request)
    {
        //$request = json_decode($request);
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        try {
            $this->userType($user, $request->cnpj);
        } catch (\Exception $exception) {
            $user->delete();
            return response()->json('Erro ao criar o usuário', 400);
        }
        Auth::login($user);
        $token = Auth::user()->createToken('token');
        return response()->json(
            [
                'token' => $token->plainTextToken,
                'id' => $user->id,
            ], 200); // Não é necessario retornar um json pois o laravel já sabe transformar o retorno em um, porém para tornar mais claro isso é interessante usar essa função

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = match(User::find($id)?->user_type) {
            0 => Institution::where('user_id', $id)->get(),
            1 => Professional::where('user_id', $id)->get(),
            2 => Teacher::where('user_id', $id)->get(),
            default => null
        };

        if(!$user) {
            return response()->json('Usuário não encontrado', 400);
        }

        /*$user['posts'] = $user->posts;
            foreach($user['posts'] as $post) {
                $post->author = $user->name;
            }
        return response()->json($user, 201);*/

    }
    public function destroy($user)
    {
        User::destroy($user);
    }

    public function userType($user, $cnpj)
    {
        match($user->user_type) {
            0 => Institution::create(["user_id" => $user->id, "cnpj" => $cnpj]),
            1 => Professional::create(["user_id" => $user->id]),
            2 => Teacher::create(["user_id" => $user->id]),
            default => null
        };
    }
}
