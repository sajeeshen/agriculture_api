<?php
namespace App\Http\Controllers;
use Validator;
use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use App\Traits\ApiResponser;
use Laravel\Lumen\Routing\Controller as BaseController;
class AuthController extends BaseController 
{
    use ApiResponser;
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;
    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }
    /**
     * Create a new token.
     * 
     * @param  \App\User   $user
     * @return string
     */
    protected function jwt(User $user) {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued. 
            'exp' => time() + 60*60 // Expiration time
        ];
        
        return JWT::encode($payload, env('JWT_SECRET'));
    } 

    /**
    * @SWG\Post(
    *      
    *   path="/auth/login",
    *   summary="Version",
    *   tags={"Users"},
    *   summary="Generate Tokens",
    *   @SWG\Parameter(
    *       name="email",
    *       in="query",
    *       description="Email",
    *       required=true,
    *       type="string",
    * ),
    *   @SWG\Parameter(
    *       name="password",
    *       in="query",
    *       description="Password",
    *       required=true,
    *       type="string",
    * ),
    *   @SWG\Response(response="200", description="Generated Token")
        
    * )
    */
    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     * 
     * @param  \App\User   $user 
     * @return mixed
     */
    public function authenticate(User $user) {
        $this->validate($this->request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);
        // Find the user by email
        $user = User::where('email', $this->request->input('email'))->first();
        if (!$user) {
            return $this->errorResponse('Email doesn not exist', 400);
        }
        // Verify the password and generate the token
        if (Hash::check($this->request->input('password'), $user->password)) {

            return $this->successResponse([
                'token' => $this->jwt($user)
            ], 200);
        }
        // Bad Request response
        return $this->errorResponse('Email or password is wrong', 400);

    }
}