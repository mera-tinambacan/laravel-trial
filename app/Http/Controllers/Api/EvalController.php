<?php

namespace App\Http\Controllers\Api;

use App\Models\Employee;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class EvalController extends Controller
{
    public function indexEval()
    {
        $evals = Evaluation::all();
        if($evals->count()>0){

            return response()->json([
                'status' => 200,
                'evals' => $evals
            ], 200);
        }else{

            return response()->json([
                'status' => 404,
                'message' => 'No records found!'
            ], 404);
        }

        
    }

    public function storeEval(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'projectName' => 'required|string|max:191',
            'evalPeriod' => 'required|string|max:191',
            'workLoc' => 'required|string|max:191',
            'employee_id' => 'required|exists:employees,id', // Validate that employee_id exists in the employees table
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ], 422);
        } else {
            // Fetch the employee name based on the provided employee_id
            $employee = Employee::findOrFail($request->employee_id);
            $employeeName = $employee->name;

            $eval = Evaluation::create([
                'projectName' => $request->projectName,
                'evalPeriod' => $request->evalPeriod,
                'workLoc' => $request->workLoc,
                'projectMembers' => $employeeName, // Assign the employee name to projectMembers
            ]);

            if ($eval) {
                return response()->json([
                    'status' => 200,
                    'message' => "indexEval created Successfully"
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => "Something went wrong"
                ], 500);
            }
        }
    }
}
