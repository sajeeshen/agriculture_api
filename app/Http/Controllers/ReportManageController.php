<?php

/**
 * Controller use for adding the reports, approving the
 * reports ( Supervisor or Admin ) and then listing the 
 * reports.
 */

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Report;
use App\Tractor;
use App\Field;
use Carbon\Carbon;
use Auth;


class ReportManageController extends Controller
{
    
    use ApiResponser;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

 /**
    * @SWG\Get(
    *      
    *   path="/api/reports/list",
    *   summary="Version",
    *   tags={"Reports"},
    *   summary="List All Reports",
    *   security={{"token":{}}},
    *   @SWG\Response(response="200", description="Return Reports list")
        
    * )
    */
    /**
     * Retrive the list
     *
     * @return Illuminate\Http\Response
     */
    
    public function index()
    {
        $reports = Report::with('tractor', 'user', 'field', 'approved_user')->get();
        return $this->successResponse($reports);
    }

        /**
    * @SWG\Post(
    *      
    *   path="/api/report/add",
    *   summary="Version",
    *   tags={"Reports"},
    *   summary="Add Report Entry",
    *   security={{"token":{}}},
    *   @SWG\Parameter(
    *       name="tractor_name",
    *       in="query",
    *       description="Tractor name",
    *       required=true,
    *       type="string",
    * ),
    *   @SWG\Parameter(
    *       name="report_date",
    *       in="query",
    *       description="Report Date",
    *       required=true,
    *       type="string",
    *      
    *    ),
    *   @SWG\Parameter(
    *       name="field_name",
    *       in="query",
    *       description="Field Name",
    *       required=true,
    *       type="string",
    *      
    *    ),
    *   @SWG\Parameter(
    *       name="processed_area",
    *       in="query",
    *       description="Processed Area",
    *       required=true,
    *       type="integer",
    *      
    *    ),
    *   @SWG\Response(response="200", description="Return Created Field")
        
    * )
    */
    /**
     * Add report
     *
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'tractor_name' => 'required|max:255|exists:tractors,name',
            'report_date' => 'required|date',
            'field_name' => 'required|max:255|exists:fields,name',
            'processed_area' =>  'required|between:0,99.99',



        ];
        $this->validate($request, $rules);
        // Validate the processed area 
         // Get the Field information from DB
        $field = Field::where('name', $request->get('field_name'))->firstOrFail();
        if($field->area < $request->get('processed_area')){
            // Processed area is greater than the actual one. So cant process
             return $this->errorResponse("The Processed area shouldn't be greater than Actual Area"
            , Response::HTTP_BAD_REQUEST);
        }
        $request->request->add(['slug' => strtolower(Str::random())]);

        // Get the tractor information from DB
        $tractor = Tractor::where('name', $request->get('tractor_name'))->firstOrFail();
       


        $formattedDate = Carbon::parse($request->report_date)->format('Y-m-s');
        // Create new Report
        $report = new Report();
        $report->tractor()->associate($tractor);
        $report->user()->associate($request->auth);
        $report->field()->associate($field);
        $report->report_date = $formattedDate;
        $report->report_slug = Str::random();
        $report->processed_area = $request->get('processed_area');
        $report->save();
        
        return $this->successResponse(
            ['message' => 'Success, your report has been inserted successfully.'], 
            Response::HTTP_CREATED);

    }
    /**
    * @SWG\Get(
    *      
    *   path="/api/report/show/{slug}",
    *   summary="Version",
    *   tags={"Reports"},
    *   summary="Show selected Report",
    *   security={{"token":{}}},
    *   @SWG\Response(response="200", description="Return Selected Report")
        
    * )
    */
    /**
     * Retrive the single report with details
     *
     * @param [string] $slug
     * @return Illuminate\Http\Response
     */
    public function show($slug)
    {
        $report = Report::with('tractor', 'user', 
        'field', 'approved_user')->where('report_slug', $slug)->firstOrFail();
        return $this->successResponse($report);

    }
    /**
    * @SWG\Put(
    *      
    *   path="/api/report/update/{slug}",
    *   summary="Version",
    *   tags={"Reports"},
    *   summary="Update Report Entry",
    *   security={{"token":{}}},
    *   @SWG\Parameter(
    *       name="tractor_name",
    *       in="query",
    *       description="Tractor name",
    *       required=true,
    *       type="string",
    * ),
    *   @SWG\Parameter(
    *       name="report_date",
    *       in="query",
    *       description="Report Date",
    *       required=true,
    *       type="string",
    *      
    *    ),
    *   @SWG\Parameter(
    *       name="field_name",
    *       in="query",
    *       description="Field Name",
    *       required=true,
    *       type="string",
    *      
    *    ),
    *   @SWG\Parameter(
    *       name="processed_area",
    *       in="query",
    *       description="Processed Area",
    *       required=true,
    *       type="integer",
    *      
    *    ),
    *   @SWG\Parameter(
    *       name="slug",
    *       in="path",
    *       description="Report Slug",
    *       required=true,
    *       type="string",
    * ),
    *   @SWG\Response(response="200", description="Return Created Field")
        
    * )
    */
    /**
     * Update the non approved 
     * Report. If the report is already approved 
     * then throw error
     * @param Request $request
     * @param [string] $slug
     * @return Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $rules = [
            'tractor_name' => 'required|max:255|exists:tractors,name',
            'report_date' => 'required|date',
            'field_name' => 'required|max:255|exists:fields,name',
            'processed_area' =>  'required|between:0,99.99'



        ];
        $this->validate($request, $rules);
        $report = Report::where('report_slug', $slug)->where('approved', false)->firstOrFail();

        // Validate the processed area 
        $field = Field::where('name', $request->get('field_name'))->firstOrFail();
        if($field->area < $request->get('processed_area')){
            // Processed area is greater than the actual one. So cant process
                return $this->errorResponse("The Processed area shouldn't be greater than Actual Area"
            , Response::HTTP_BAD_REQUEST);
        }
        $formattedDate = Carbon::parse($request->report_date)->format('Y-m-s');

        // Get the tractor information from DB
        $tractor = Tractor::where('name', $request->get('tractor_name'))->firstOrFail();
        $report->tractor()->associate($tractor);
        $report->user()->associate($request->auth);
        $report->field()->associate($field);
        $report->report_date = $formattedDate;
        $report->processed_area = $request->get('processed_area');
        $report->save();
        return $this->successResponse($report);
    }

    /**
    * @SWG\Post(
    *      
    *   path="/api/report/approve/{slug}",
    *   summary="Version",
    *   tags={"Reports"},
    *   summary="Approve a Report ( Supervisor | Admin )",
    *   security={{"token":{}}},

    *   @SWG\Parameter(
    *       name="slug",
    *       in="path",
    *       description="Report Slug",
    *       required=true,
    *       type="string",
    * ),
    *   @SWG\Response(response="200", description="Return success message")
        
    * )
    */
    /**
     *  Function used for approved a non approved 
     *  Report. 
     * @param Request $request
     * @param String $slug
     * @return Illuminate\Http\Response
     */
    public function approve(Request $request, $slug)
    {
        $report = Report::where('report_slug', $slug)->where('approved', false)->firstOrFail();
        $report->approved = true;
        $report->approved_user()->associate($request->auth);

        $report->save();
        return $this->successResponse(['message' => 'Success, your report has been approved successfully.'], Response::HTTP_CREATED);

    }
    /**
    * @SWG\Delete(
    *      
    *   path="/api/report/delete/{slug}",
    *   summary="Version",
    *   tags={"Reports"},
    *   summary="Delete a Report ( Supervisor | Admin )",
    *   security={{"token":{}}},

    *   @SWG\Parameter(
    *       name="slug",
    *       in="path",
    *       description="Report Slug",
    *       required=true,
    *       type="string",
    * ),
    *   @SWG\Response(response="200", description="Return success message")
        
    * )
    */
    /**
     * Function use for deleting one Report from DB
     *
     * @param String $slug
     * @return Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $report = Report::where('report_slug', $slug)->firstOrFail();
        $report->delete();
        return $this->successResponse($report);
    }
    /**
    * @SWG\Get(
    *      
    *   path="/api/reports/view",
    *   summary="Version",
    *   tags={"Reports"},
    *   summary="View the Report ",
    *   security={{"token":{}}},

    *   @SWG\Response(response="200", description="Return the report list")
        
    * )
    */
    /**
     * Funcion use for displaying the report
     * @return Illuminate\Http\Response
     */

    public function report()
    {
        $reports = Report::with('tractor:id,name', 
        'user:id,first_name,last_name',
        'field:id,name,crops_type,area', 'approved_user:id,first_name,last_name')
        ->get();
        $results = [];
        $total_processed_area = 0;
        foreach($reports as $report)
        {
            $results[] = array(
                'report_date' => $report->report_date,
                'approved' => $report->approved,
                'tractor_name' => $report->tractor->name,
                'field_name' => $report->field->name,
                'actual_area' => $report->field->area,
                'culture' => $report->field->crops_type,
                'processed_area' => $report->processed_area
            );
            $total_processed_area+= $report->processed_area;
        }
        // Get the sum of the fields and append to the result array
        $sum_val_report = Report::crossJoin('fields', 'reports.field_id', '=', 'fields.id')
        ->groupBy('fields.id')
        ->selectRaw('count(fields.crops_type) as count_crops, fields.crops_type')
        ->get()->toArray();
        foreach($sum_val_report as $sum_val){
            $results[] = array(
                'crop_type' => $sum_val['crops_type'],
                'crop_count' => $sum_val['count_crops']
            );
        }
        $results[] = array('total_processed_area' => $total_processed_area);

        return $this->successResponse($results);
    }

    //
}
