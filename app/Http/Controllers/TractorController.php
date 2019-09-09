<?php

/**
 * Controller for manage Tractor
 */

 
namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Tractor;
class TractorController extends Controller
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
    *   path="/api/tractors",
    *   summary="Version",
    *   tags={"Tractors"},
    *   summary="List All Tractors",
    *   security={{"token":{}}},
    *   @SWG\Response(response="200", description="Return Tractors list")
        
    * )
    */
    /**
     * Retrive the list
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        $tractors = Tractor::all();
        return $this->successResponse($tractors);
    }
    /**
    * @SWG\Post(
    *      
    *   path="/api/tractor/add",
    *   summary="Version",
    *   tags={"Tractors"},
    *   summary="Add new Tractor",
    *   security={{"token":{}}},
    *   @SWG\Parameter(
    *       name="name",
    *       in="query",
    *       description="Tractor name",
    *       required=true,
    *       type="string",
    * ),

    *   @SWG\Response(response="200", description="Return Created Tractor")
        
    * )
    */
    /**
     * Add new Tractor
     * @param Request $request
     * @return Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255|unique:tractors'

        ];
        $this->validate($request, $rules);
        $slug_val = str_replace(' ','_',$request->input('name'));
        $request->request->add(['slug' => strtolower($slug_val.'_'.Str::random())]);
        $tractor = Tractor::create($request->all());
        return $this->successResponse($tractor, Response::HTTP_CREATED);

    }
    /**
    * @SWG\Get(
    *      
    *   path="/api/tractor/{slug}",
    *   summary="Version",
    *   tags={"Tractors"},
    *   summary="Display individual Tractor detail",
    *   security={{"token":{}}},
    *   @SWG\Parameter(
    *       name="slug",
    *       in="path",
    *       description="Tractor Slug",
    *       required=true,
    *       type="string",
    * ),
    *   @SWG\Response(response="200", description="Return selected Tractor details")
        
    * )
    */
    /**
     * Display individual Tractor details
     *
     * @param string $slug
     * @return Illuminate\Http\Response
     */
    public function show($slug)
    {
        $tractor = Tractor::where('slug', $slug)->firstOrFail();
        return $this->successResponse($tractor);

    }

    /**
    * @SWG\Put(
    *   path="/api/tractor/update/{slug}",
    *   summary="Version",
    *   tags={"Tractors"},
    *   summary="Add new Tractor",
    *   security={{"token":{}}},
    *   @SWG\Parameter(
    *       name="name",
    *       in="query",
    *       description="Tractor Name",
    *       required=true,
    *       type="string",
    * ),
    *   @SWG\Parameter(
    *       name="slug",
    *       in="path",
    *       description="Tractor Slug",
    *       required=true,
    *       type="string",
    * ),
    *   @SWG\Response(response="200", description="Return Created Tractor")
        
    * )
    */

    /**
     * Update existing Tractor details
     * @param Request $request
     * @param String $slug
     * @return Illuminate\Http\Response
     */

    public function update(Request $request, $slug)
    {
        $tractor = Tractor::where('slug', $slug)->firstOrFail();
        $rules = [
            'name' => 'required|max:255|unique:tractors,name,'.$tractor->id

        ];
        $this->validate($request, $rules);
        
        
        $tractor->fill($request->all());

        if($tractor->isClean()){
            return $this->errorResponse('No changes made for the Tractor'
            , Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $tractor->save();
        return $this->successResponse($tractor);
    }
    
    /**
    * @SWG\Delete(
    *      
    *   path="/api/tractor/delete/{slug}",
    *   summary="Version",
    *   tags={"Tractors"},
    *   summary="Delete a Tractor",
    *   security={{"token":{}}},
    *   @SWG\Parameter(
    *       name="slug",
    *       in="path",
    *       description="Tractor Slug",
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
