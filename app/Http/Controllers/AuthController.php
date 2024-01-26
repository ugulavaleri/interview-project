<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\ForgotPasswordRequest;
    use App\Http\Requests\LoginRequest;
    use App\Http\Requests\RegistrationRequest;
    use App\Http\Requests\ResetPasswordRequest;
    use App\Models\User;
    use App\Notifications\ResetPasswordVerificationNotification;
    use Ichtrojan\Otp\Otp;
    use Illuminate\Support\Facades\Hash;

    class AuthController extends Controller
    {
        private $otp;
        public function __construct()
        {
            $this->middleware('auth:api', ['except' => ['login','register']]);
            $this->otp = new Otp;
        }

        public function login(LoginRequest $request)
        {
            $credentials = $request->validated();

            if (!$token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Invalid Credentials!'], 401);
            }

            return $this->respondWithToken($token);
        }

        public function register(RegistrationRequest $request){
            $validatedData = $request->validated();
            $validatedData['password'] = Hash::make($validatedData['password']);

            $user = User::create($validatedData);

            return response()->json([
                'success' => 'user registered successfully!',
                'user' => $user,
            ]);
        }
        public function forgetPassword(ForgotPasswordRequest $request){
            $request->validated();
            $email = $request->email;
            $user = User::where('email',$email)->first();

            $user->notify(new ResetPasswordVerificationNotification());

            return response()->json([
                'success' => 'check email!'
            ]);
        }
        public function logout()
        {
            auth()->logout();
            return response()->json(['message' => 'Successfully logged out']);
        }
        public function resetPassword(ResetPasswordRequest $request){
            $request->validated();
            $validatedOtp = $this->otp->validate($request->email, $request->otp);
            if(!$validatedOtp->status){
                return response()->json([
                    'error' => $validatedOtp
                ]);
            }
            $user = User::where('email',$request->email)->first();
            $user->update(['password' => Hash::make($request->password)]);
            $user->tokens()->delete();
            return response()->json([
                'success' => 'password changed successfully'
            ]);
        }
        public function me()
        {
            return response()->json(auth()->user());
        }

        public function refresh()
        {
            return $this->respondWithToken(auth()->refresh());
        }

        protected function respondWithToken($token)
        {
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]);
        }
    }
