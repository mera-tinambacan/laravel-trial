<?php

namespace App\Http\Controllers\Api;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        if($employees->count()>0){

            return response()->json([
                'status' => 200,
                'employees' => $employees
            ], 200);
        }else{

            return response()->json([
                'status' => 404,
                'message' => 'No records found!'
            ], 404);
        }

        
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'position' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'required|digits:11',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ],422);
        }else{

            $employee = Employee::create([
                'name' => $request->name,
                'position' => $request->position,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            if($employee){
                return response()->json([
                    'status' => 200,
                    'message' => "Employee created Successfully"
                ], 200);
            }else{

                return response()->json([
                    'status' => 500,
                    'message' => "Something went wrong"
                ], 500);
            }
        }
    }
}
