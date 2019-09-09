<?php

/**
 *  Controller user for manage Fields 
 */

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Field;
class FieldController extends Controller
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
     * @SWG\SecurityScheme(
     *   securityDefinition="token",
     *   type="apiKey",
     *   in="query",
     *   name="token"
     * )
     */
    /**
    * @SWG\Get(
    *      
    *   path="/api/fields",
    *   summary="Version",
    *   tags={"Fields"},
    *   summary="List All Fields",
    *   security={{"token":{}}},
    *   @SWG\Response(response="200", description="Return Field list")
        
    * )
    */
    /**
     * Retrive the list
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        $fields = Field::all();
        return $this->successResponse($fields);
    }

    /**
    * @SWG\Post(
    *      
    *   path="/api/field/add",
    *   summary="Version",
    *   tags={"Fields"},
    *   summary="Add new Field",
    *   security={{"token":{}}},
    *   @SWG\Parameter(
    *       name="name",
    *       in="query",
    *       description="Field name",
    *       required=true,
    *       type="string",
    * ),
    *   @SWG\Parameter(
    *       name="area",
    *       in="query",
    *       description="Area",
    *       required=true,
    *       type="integer",
    *      
    *    ),
    *   @SWG\Parameter(
    *       name="crops_type",
    *       in="query",
    *       description="Crop Types",
    *       required=true,
    *       type="string",
    *      
    *    ),
    *   @SWG\Response(response="200", description="Return Created Field")
        
    * )
    */
    /**
     * Add new Field
     * @param Request $request
     * @return Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255|unique:fields',
            'area' => 'required|between:0,99.99',
            'crops_type' => 'required|in:Wheat,Broccoli,Strawberries'
        ];
        $this->validate($request, $rules);
        $slug_val = str_replace(' ','_',$request->input('name'));
        $request->request->add(['slug' => strtolower($slug_val.Str::random())]);
        $field = Field::create($request->all());
        return $this->successResponse($field, Response::HTTP_CREATED);

    }

    /**
    * @SWG\Get(
    *      
    *   path="/api/field/{slug}",
    *   summary="Version",
    *   tags={"Fields"},
    *   summary="Display individual Field detail",
    *   security={{"token":{}}},
    *   @SWG\Parameter(
    *       name="slug",
    *       in="path",
    *       description="Field Slug",
    *       required=true,
    *       type="string",
    * ),
    *   @SWG\Response(response="200", description="Return selected Field details")
        
    * )
    */
    /**
     * Display individual Field details
     *
     * @param string $slug
     * @return Illuminate\Http\Response
     */
    public function show($slug)
    {
        $field = Field::where('slug', $slug)->firstOrFail();
        return $this->successResponse($field);

    }
    /**
    * @SWG\Put(
    *      
    *   path="/api/field/update/{slug}",
    *   summary="Version",
    *   tags={"Fields"},
    *   summary="Add new Field",
    *   security={{"token":{}}},
    *   @SWG\Parameter(
    *       name="name",
    *       in="query",
    *       description="Field name",
    *       required=true,
    *       type="string",
    * ),
    *   @SWG\Parameter(
    *       name="area",
    *       in="query",
    *       description="Area",
    *       required=true,
    *       type="integer",
    *      
    *    ),
    *   @SWG\Parameter(
    *       name="crops_type",
    *       in="query",
    *       description="Crop Types",
    *       required=true,
    *       type="string",
    *      
    *    ),
    *   @SWG\Parameter(
    *       name="slug",
    *       in="path",
    *       description="Field Slug",
    *       required=true,
    *       type="string",
    * ),
    *   @SWG\Response(response="200", description="Return Updated Field")
        
    * )
    */
    /**
     * Update existing Field details
     * @param Request $request
     * @param String $slug
     * @return Illuminate\Http\Response
     */

    public function update(Request $request, $slug)
    {
        $field = Field::where('slug', $slug)->firstOrFail();
        $rules = [
            'name' => 'required|max:255|unique:fields,name,'.$field->id,
            'area' => 'required|between:0,99.99',
            'crops_type' => 'required|in:Wheat,Broccoli,Strawberries'
        ];
        $this->validate($request, $rules);
        
        $field->fill($request->all());

        if($field->isClean()){
            return $this->errorResponse('No changes made for the Field'
            , Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $field->save();
        return $this->successResponse($field);
    }

    /**
    * @SWG\Delete(
    *      
    *   path="/api/field/delete/{slug}",
    *   summary="Version",
    *   tags={"Fields"},
    *   summary="Delete a Field",
    *   security={{"token":{}}},
    *   @SWG\Parameter(
    *       name="slug",
    *       in="path",
    *       description="Field Slug",
    *       required=true,
    *       type="string",
    * ),
    *   @SWG\Response(response="200", description="Return Deleted Tractor details")
        
    * )
    */
    /**
     * Delete a Tractor
     *
     * @param string $slug
     * @return Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $tractor = Tractor::where('slug', $slug)->firstOrFail();
        $tractor->delete();
        return $this->successResponse($tractor);
    }

    //
}
