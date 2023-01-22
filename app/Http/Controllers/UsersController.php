<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersFormRequest;
use App\Models\Course_professional;
use App\Models\Institution;
use App\Models\Professional;
use App\Models\Professional_role;
use App\Models\Teacher;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersController extends Controller
{

    public function index(Request $request)
    {
        $request->headers->all()['user'][0];
        $request->headers->all()['user_type'][0];

        if(Auth::user()->user_type == 0) {
            $institution = Auth::user();
            if(!$institution) {
                return response()->json('Não foi possível encontrar o usuário', 400);
            }

            $vacancies = Vacancy::where('institution_id', $institution->user_id)->get();
            return response()->json([
                "institution" => $institution,
                "vacancies" => $vacancies,
            ], 200);
        } else if(Auth::user()->user_type == 1) {
            $professional = Auth::user();
            if(!$professional) {
                return response()->json('Não foi possível encontrar o usuário', 400);
            }

            $roles = Professional_role::where('professional_id', $professional->user_id)->get();
            $courses = Course_professional::where('professional_id', $professional->user_id)->get();
            return response()->json([
                "professional" => $professional,
                "roles" => $roles,
                "courses" => $courses,
            ]);

        } else {
            $teacher = Teacher::find(Auth::user());
            if(!$teacher) {
                return response()->json('Não foi possível encontrar o usuário', 400);
            }
        }
    }
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
        Auth::attempt(["email" => $user->email, "password" => $request->password]);
        $user = Auth::user();
        $token = $user->createToken('token');

        return response()->json(
            [
                'token' => $token->plainTextToken,
                'user_type' => $user->user_type,
                'id' => $user->id,
            ], 200
        ); // Não é necessario retornar um json pois o laravel já sabe transformar o retorno em um, porém para tornar mais claro isso é interessante usar essa função
    }

    public function show($user, $type)
    {
        if($type == 0) {
            $institution = Institution::find($user);
            if(!$institution) {
                return response()->json('Não foi possível encontrar o usuário', 400);
            }

            $vacancies = Vacancy::where('institution_id', $institution->user_id)->get();
            return response()->json([
                "institution" => $institution,
                "vacancies" => $vacancies,
            ], 200);
        } else if($type == 1) {
            $professional = Professional::find($user);
            if(!$professional) {
                return response()->json('Não foi possível encontrar o usuário', 400);
            }

            $roles = Professional_role::where('professional_id', $professional->user_id)->get();
            $courses = Course_professional::where('professional_id', $professional->user_id)->get();
            return response()->json([
                "professional" => $professional,
                "roles" => $roles,
                "courses" => $courses,
            ]);

        } else {
            $teacher = Teacher::find($user);
            if(!$teacher) {
                return response()->json('Não foi possível encontrar o usuário', 400);
            }
        }
    }

    public function destroy($user)
    {
        User::destroy($user);
        return response()->json('Usuário deletado com sucesso', 200);
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
