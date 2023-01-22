<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query();

        $request->name ? $query->where('name', 'LIKE',  '%'.$request->name.'%') : '';

        $courses = $query->paginate(5);

        foreach ($courses as $course) {
            $course['teacher'] = Teacher::where('id', $course->teacher_id)->get();
            $course['teacher'][0]['user'] = User::where('id', $course['teacher'][0]['user_id'])->get();
        }

        return response()->json($courses, 200);
    }
}
