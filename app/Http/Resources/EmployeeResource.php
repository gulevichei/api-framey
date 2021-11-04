<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="EmployeeResource",
 *     description="Employee resource",
 *     @OA\Xml(
 *         name="EmployeeResource"
 *     )
 * )
 */
class EmployeeResource extends JsonResource
{

    /**
     * @OA\Property(
     *     description="Name"
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @OA\Property(
     *     description="Position"
     * )
     *
     * @var string
     */
    private $position;

    /**
     * @OA\Property(
     *     description="Employee start date work format Y-m-d"
     * )
     *
     * @var Date
     */
    private $start_date;

    /**
     * @OA\Property(
     *     description="Employee end date work format Y-m-d"
     * )
     *
     * @var Date
     */
    private $end_date;

    /**
     * @OA\Property(
     *     description="Link to Employee Id"
     * )
     *
     * @var int
     */
    private $superior;

    /**
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
