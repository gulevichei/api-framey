<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Info(title="Employee API", version="0.1")
 */
class EmployeeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/employee",
     *     tags={"employee-api"},
     *     summary="Get employees with filter",
     *     @OA\Parameter(
     *         name="position",
     *         in="query",
     *         description="Optional parameter filter for position",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns some sample category things",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/EmployeeResource"))
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied."
     *     )
     * )
     */
    public function list(Request $request)
    {
        $position  = $request->input("position");
        $employees = Employee::where('position', 'like', '%' . $position . '%')->get();
        return response()->json($employees, Response::HTTP_OK);
    }

    /**
     * @OA\Get (
     *     path="/api/employee/{id}",
     *     tags={"employee-api"},
     *     summary="Get employee data",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id for employee",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Employee data",
     *         @OA\JsonContent(ref="#/components/schemas/EmployeeResource")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Error not found",
     *     )
     * )
     */
    public function get(Request $request, $id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(
                ['message' => Response::$statusTexts[Response::HTTP_NOT_FOUND]],
                Response::HTTP_NOT_FOUND
            );
        }
        return response()->json($employee);
    }

    /**
     * @OA\Get (
     *     path="/api/employee/{id}/subordinates",
     *     tags={"employee-api"},
     *     summary="Get all employee subordinates",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id for employee",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Employee chaild data",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Error not found",
     *     )
     * )
     */
    public function getSubordinates(Request $request, $id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(
                ['message' => Response::$statusTexts[Response::HTTP_NOT_FOUND]],
                Response::HTTP_NOT_FOUND
            );
        }

        $subordinates = $employee->subordinates()->get();

        return response()->json($subordinates);
    }

    /**
     * @OA\Post(
     *      path="/api/employee",
     *      tags={"employee-api"},
     *      summary="Store new employee",
     *      description="Returns employee data",
     *      @OA\RequestBody(
     *          required=true,
     *         @OA\JsonContent(ref="#/components/schemas/EmployeeResource")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/EmployeeResource")
     *       ),
     *      @OA\Response(
     *          response="400",
     *          description="Error: Bad request. When required parameters were not supplied.",
     *      )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->json()->all(),
            [
                'name'       => 'string|max:255',
                'position'   => 'string|max:255',
                'start_date' => 'date',
                'end_date'   => 'date|nullable',
                'superior'   => 'numeric|nullable',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $employee = Employee::create($request->json()->all());
        return response()->json($employee, Response::HTTP_OK);
    }

    /**
     * @OA\Put (
     *     path="/api/employee/{id}",
     *     summary="Update employee",
     *     tags={"employee-api"},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="Id for employee",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Employee deleted",
     *         @OA\JsonContent(ref="#/components/schemas/EmployeeResource")
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request.",
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(
                ['message' => Response::$statusTexts[Response::HTTP_NOT_FOUND]],
                Response::HTTP_NOT_FOUND
            );
        }

        $validator = Validator::make(
            $request->json()->all(),
            [
                'name'       => 'string|max:255',
                'position'   => 'string|max:255',
                'start_date' => 'date',
                'end_date'   => 'date|nullable',
                'superior'   => 'numeric|nullable',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $employee->update($request->json()->all());
        return response()->json($employee, Response::HTTP_OK);
    }

    /**
     * @OA\Delete (
     *     path="/api/employee/{id}",
     *     summary="Delete employee",
     *     tags={"employee-api"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id for employee",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Employee deleted",
     *         @OA\JsonContent(ref="#/components/schemas/EmployeeResource")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Error not found",
     *     )
     * )
     */
    public function destroy(Request $request, $id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(
                ['message' => Response::$statusTexts[Response::HTTP_NOT_FOUND]],
                Response::HTTP_NOT_FOUND
            );
        }
        $employee->delete();
        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
