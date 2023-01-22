<?php

namespace App\Http\Controllers;

use App\Models\Course_vacation;
use App\Models\Vacancy;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Vacancy::query();

        $request->salary ? $query->where('salary', '>=', $request->salary) : '';
        $request->role ? $query->where('role', $request->role) : '';
        $request->name ? $query->where('name', $request->name) : '';

        $vagas = $query->paginate(5);

        foreach ($vagas as $vaga) {
            $vaga['institution'] = Vacancy::where('institution_id', $vaga->user_id)->get();
            $vaga['courses'] = Course_vacation::where('vacancy_id', $vaga->id)->get();
        }

        return response()->json($vagas, 200);
        //salario
        //especialidade
        // nome
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
