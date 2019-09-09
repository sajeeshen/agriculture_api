<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Hash;
class UserManageController extends Controller
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
    *   path="/api/user/list",
    *   summary="Version",
    *   tags={"Users"},
    *   summary="List All Users",
    *   security={{"token":{}}},
    *   @SWG\Response(response="200", description="Return Users list")
        
    * )
    */
    /**
     * Retrive the list
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with(['roles' => function ($query) {
            $query->select('name');
        }])->get();
        return $this->successResponse($users);
    }
    /**
    * @SWG\Post(
    *      
    *   path="/api/user/add",
    *   summary="Version",
    *   tags={"Users"},
    *   summary="Add new User ( Admin can only add new user )",
    *   security={{"token":{}}},
    *   @SWG\Parameter(
    *       name="first_name",
    *       in="query",
    *       description="First name",
    *       required=true,
    *       type="string",
    * ),
    *   @SWG\Parameter(
    *       name="last_name",
    *       in="query",
    *       description="Last name",
    *       required=true,
    *       type="string",
    *      
    *    ),
    *   @SWG\Parameter(
    *       name="email",
    *       in="query",
    *       description="Email",
    *       required=true,
    *       type="string",
    *      
    *    ),
    *   @SWG\Parameter(
    *       name="password",
    *       in="query",
    *       description="Password",
    *       required=true,
    *       type="string",
    *      
    *    ),
    *   @SWG\Parameter(
    *       name="role",
    *       in="query",
    *       description="Role ( User | Supervisor | Admin )",
    *       required=true,
    *       type="string",
    *      
    *    ),
    *   @SWG\Response(response="200", description="Return Created Field")
        
    * )
    */
    /**
     * Create new User and return
     *
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
            'role' => 'in:User,Supervisor,Admin'

        ];
        $this->validate($request, $rules);

        $user = User::create([
            'email' => $request->get('email'),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'password'=> Hash::make($request->get('password')),
            'slug' => Str::random(),
        ]);
        $role_user = Role::where('name', $request->get('role'))->first();
        $user->roles()->attach($role_user);
        return $this->successResponse($user, Response::HTTP_CREATED);

    }
    /**
    * @SWG\Get(
    *      
    *   path="/api/user/view/{slug}",
    *   summary="Version",
    *   tags={"Users"},
    *   summary="Display single user details ",
    *   security={{"token":{}}},
    *   @SWG\Parameter(
    *       name="slug",
    *       in="path",
    *       description="User Slug",
    *       required=true,
    *       type="string",
    * ),
    *   @SWG\Response(response="200", description="Return selected User details")
        
    * )
    */
    /**
     * Fetch the given user detais  and return
     * @param String $slug
     * @return Illuminate\Http\Response
     */
    public function show($slug)
    {
        $user = User::with('roles')->where('slug', $slug)->firstOrFail();
        return $this->successResponse($user);

    }
    /**
    * @SWG\Put(
    *      
    *   path="/api/user/update/{slug}",
    *   summary="Version",
    *   tags={"Users"},
    *   summary="Add new User ( Admin can only add new user )",
    *   security={{"token":{}}},
    *   @SWG\Parameter(
    *       name="first_name",
    *       in="query",
    *       description="First name",
    *       required=true,
    *       type="string",
    * ),
    *   @SWG\Parameter(
    *       name="last_name",
    *       in="query",
    *       description="Last name",
    *       required=true,
    *       type="string",
    *      
    *    ),
    *   @SWG\Parameter(
    *       name="email",
    *       in="query",
    *       description="Email",
    *       required=true,
    *       type="string",
    *      
    *    ),
    *   @SWG\Parameter(
    *       name="password",
    *       in="query",
    *       description="Password",
    *       required=true,
    *       type="string",
    *      
    *    ),
    *   @SWG\Parameter(
    *       name="role",
    *       in="query",
    *       description="Role ( User | Supervisor | Admin )",
    *       required=true,
    *       type="string",
    *      
    *    ),
    *   @SWG\Parameter(
    *       name="slug",
    *       in="path",
    *       description="User Slug",
    *       required=true,
    *       type="string",
    * ),
    *   @SWG\Response(response="200", description="Return Updated User")
        
    * )
    */
    /**
     * Update the selected user and return
     * @param Request $request
     * @param String $slug
     * @return Illuminate\Http\Response
     */

    public function update(Request $request, $slug)
    {
        $rules = [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'password' => 'min:6',
            'email' => 'email|max:255|exists:users',
            'role' => 'in:User,Supervisor,Admin'

        ];
        $this->validate($request, $rules);
        $user = User::where('slug', $slug)->firstOrFail();
        
        $user->fill($request->all());

        if($user->isClean()){
            return $this->errorResponse("You have't made any changes"
            , Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $user->save();
        return $this->successResponse($user);
    }
    /**
    * @SWG\Delete(
    *      
    *   path="/api/user/delete/{slug}",
    *   summary="Version",
    *   tags={"Users"},
    *   summary="Delete selected user ",
    *   security={{"token":{}}},
    *   @SWG\Parameter(
    *       name="slug",
    *       in="path",
    *       description="User Slug",
    *       required=true,
    *       type="string",
    * ),
    *   @SWG\Response(response="200", description="Return selected User details")
        
    * )
    */
    /**
     * Delete the selected user
     *
     * @param String $slug
     * @return Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();
        $user->delete();
        return $this->successResponse($user);
    }

    //
}
