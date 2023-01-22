<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query();

        $request->name ? $query->where('name', 'LIKE',  '%'.$request->name.'%') : '';

        $courses = $query->paginate(5);

        foreach ($courses as $course) {
            $course['teacher'] = Teacher::where('id', $course->teacher_id)->get();
        }

        return response()->json($courses, 200);
    }
}
