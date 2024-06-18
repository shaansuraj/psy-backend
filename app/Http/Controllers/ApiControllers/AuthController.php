<?php

namespace App\Http\Controllers\ApiControllers;

use Mail;
use DB;
use App\Models\AppUser;
use App\Models\Otp;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiControllers\HelperController as HelperController;
use Intervention\Image\ImageManagerStatic as Image;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;

class AuthController extends HelperController
{

    public function logout()
    {
        $user = AppUser::where('id', Auth::user()->id)->first();
        $user->tokens()->delete();

        return $this->sendresponse('true', 'user logout successfully', null);
    }

    public function login(Request $request)
    {
        $validatedUser = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required",
        ]);

        if ($validatedUser->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedUser->errors());
        }

        $user = AppUser::where("email", $request->email)->first();

        if ($user && $user->provider != 'web') {
            return $this->sendresponse('false', 'Use Google login!!', null);
        }

        if ($user && Hash::check($request->password, $user->password)) {

            $token = $user->createToken('apptoken')->plainTextToken;
            return $this->sendresponse('true', 'user found', ['user' => $user, 'token' => $token]);
        } elseif (!$user) {
            return $this->sendresponse('false', 'User not found!!', null);
        } else {
            return $this->sendresponse('false', 'Wrong email or password!!', null);
        }
    }

    // public function sendVerifyEmail($email)
    // {
    //     $user = AppUser::where('email', $email)->first();

    //     $random = Str::random(40);
    //     $domain = URL::to('/');
    //     $url = $domain . '/verify-email/' . $random;

    //     $data['url'] = $url;
    //     $data['email'] = $email;
    //     $data['name'] = $user->user_name;

    //     Mail::send('email.index', ['data' => $data], function ($message) use ($data) {
    //         $message->from('thedeveloper200@gmail.com', 'Psychetric Rounds');
    //         $message->to($data['email']);
    //         $message->subject('Email Verification');
    //     });

    //     $user->remember_token = $random;
    //     $user->save();

    //     return $this->sendresponse('true', 'Mail sent successfully', null);
    // }

    public function send_email_otp(Request $request)
    {
        $validatedUser = Validator::make($request->all(), [
            "email" => "required|email|unique:app_users,email",
            "name" => "required",
            "country" => "required",
            "user_name" => "required|max:30|alpha_dash|unique:app_users,user_name",
            "password" => "required|min:8",
            "confirm_password" => 'required_with:password|same:password|min:8'
        ]);

        if ($validatedUser->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedUser->errors());
        }

        $code = random_int(100000, 999999);

        $data['code'] = $code;
        $data['email'] = $request->email;

        $newOtp = new Otp;
        $newOtp->otp = $code;
        $newOtp->user = $request->ip();
        $newOtp->save();

        Mail::send('email.send_otp', ['data' => $data], function ($message) use ($data) {
            $message->from('thedeveloper200@gmail.com', 'Psychetric Rounds');
            $message->to($data['email']);
            $message->subject('Email Verification');
        });

        return $this->sendresponse('true', 'Mail sent successfully', null);
    }

    public function verifyEmail(Request $request)
    {
        $validatedUser = Validator::make($request->all(), [
            "token" => "required",
        ]);

        if ($validatedUser->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedUser->errors());
        }

        $user = AppUser::where('remember_token', $request->token)->first();

        if ($user) {
            $datetime = Carbon::now()->format('Y-m-d H:i:s');
            $user->remember_token = '';
            $user->email_verified_at = $datetime;
            $user->save();

            return $this->sendresponse('true', 'email verified successfully', $user);
        } else {
            return $this->sendresponse('true', 'email already verified', null);
        }
    }

    public function register(Request $request)
    {
        $validatedUser = Validator::make($request->all(), [
            "email" => "required|email|unique:app_users,email",
            "name" => "required",
            "country" => "required",
            "otp" => "required",
            "user_name" => "required|max:30|alpha_dash|unique:app_users,user_name",
            "password" => "required|min:8",
            "confirm_password" => 'required_with:password|same:password|min:8'
        ]);

        if ($validatedUser->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedUser->errors());
        }

        $otp = Otp::where('otp', $request->otp)->first();

        if ($otp) {
            $user = AppUser::create([
                "email" => $request->email,
                "name" => $request->name,
                "country" => $request->country,
                "profile" => 'default.png',
                "banner" => 'default.png',
                "provider" => 'web',
                "user_name" => $request->user_name,
                "password" => Hash::make($request->password),
            ]);

            $token = $user->createToken('apptoken')->plainTextToken;

            if ($user) {
                return $this->sendresponse('true', 'user created successfully', ['user' => $user, 'token' => $token]);
            } else {
                return $this->sendresponse('false', 'something went wrong', null);
            }
        } else {
            return $this->sendresponse('false', 'wrong', null);
        }
    }

    // public function register(Request $request)
    // {

    //     $validatedUser = Validator::make($request->all(), [
    //         "email" => "required|email|unique:app_users,email",
    //         "name" => "required",
    //         "country" => "required",
    //         "user_name" => "required|max:30|alpha_dash|unique:app_users,user_name",
    //         "password" => "required|min:8",
    //         "confirm_password" => 'required_with:password|same:password|min:8'
    //     ]);

    //     if ($validatedUser->fails()) {
    //         return $this->sendresponse('false', 'validation error', $validatedUser->errors());
    //     }

    //     $user = AppUser::create([
    //         "email" => $request->email,
    //         "name" => $request->name,
    //         "country" => $request->country,
    //         "profile" => 'default.png',
    //         "banner" => 'default.png',
    //         "provider" => 'web',
    //         "user_name" => $request->user_name,
    //         "password" => Hash::make($request->password),
    //     ]);

    //     $this->sendVerifyEmail($request->email);

    //     $token = $user->createToken('apptoken')->plainTextToken;

    //     if ($user) {
    //         return $this->sendresponse('true', 'user created successfully', ['user' => $user, 'token' => $token]);
    //     } else {
    //         return $this->sendresponse('false', 'something went wrong', null);
    //     }
    // }

    public function forgetPassword(Request $request)
    {
        $validatedUser = Validator::make($request->all(), [
            "email" => "required|email|exists:app_users",
        ]);

        if ($validatedUser->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedUser->errors());
        }
        $user = AppUser::where('email', $request->email)->first();

        if ($user->provider != 'web') {
            return $this->sendresponse('false', 'Use Google login!!', null);
        }

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        $data['email'] = $request->email;
        $data['name'] = $user->user_name;

        Mail::send('email.forgot', ['token' => $token, 'data' => $data], function ($message) use ($data) {
            $message->from('thedeveloper200@gmail.com', 'Psychetric Rounds');
            $message->to($data['email']);
            $message->subject('Reset Password');
        });

        return $this->sendresponse('true', 'Mail sent!!', null);
    }

    public function resetPassword(Request $request)
    {
        $validatedUser = Validator::make($request->all(), [
            'token' => 'required',
            'password' => 'required|string|min:8',
            "confirm_password" => 'required_with:password|same:password|min:8'
        ]);

        if ($validatedUser->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedUser->errors());
        }

        $updatePassword = DB::table('password_resets')
            ->where([
                'token' => $request->token
            ])
            ->first();

        if (!$updatePassword) {
            return $this->sendresponse('false', 'invalid token', null);
        }

        AppUser::where('email', $updatePassword->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['token' => $request->token])->delete();

        return $this->sendresponse('true', 'password reset successfull', null);
    }

    public function socialLogin(Request $request)
    {
        $validatedUser = Validator::make($request->all(), [
            'access_token' => 'required',
        ]);

        if ($validatedUser->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedUser->errors());
        }

        try {
            /** @var FirebaseAuth $firebaseAuth */
            $firebaseAuth = app(FirebaseAuth::class);

            // Verify the ID token
            $verifiedIdToken = $firebaseAuth->verifyIdToken($request->access_token);

            // Extract claims from the verified ID token
            $uid = $verifiedIdToken->claims()->get('sub');
            // $userEmail = $verifiedIdToken->claims()->get('email');

            // Check if the user exists in your database
            $user = AppUser::where('access_token', $uid)->first();

            if ($user) {
                $token = $user->createToken('apptoken')->plainTextToken;
                Auth::loginUsingId($user->id);

                // User exists, return user data
                return $this->sendresponse('true', 'user authenticated successfully', ['user' => $user, 'token' => $token]);
            } else {
                // User does not exist in your database, handle accordingly
                return $this->sendresponse('false', 'user not found', null);
            }
        } catch (\Exception $e) {
            // Handle authentication errors
            return $this->sendresponse('false', 'authentication error', $e->getMessage());
        }
    }


    public function socialRegister(Request $request)
    {
        $validatedUser = Validator::make($request->all(), [
            'email' => 'required|email|unique:app_users,email',
            "name" => "required",
            "country" => "required",
            "user_name" => "required|max:30|alpha_dash|unique:app_users,user_name",
            'access_token' => 'required',
        ]);

        if ($validatedUser->fails()) {
            return $this->sendresponse('false', 'validation error', $validatedUser->errors());
        }

        try {
            /** @var FirebaseAuth $firebaseAuth */
            $firebaseAuth = app(FirebaseAuth::class);

            // Verify the ID token
            $verifiedIdToken = $firebaseAuth->verifyIdToken($request->access_token);

            // Extract claims from the verified ID token
            $uid = $verifiedIdToken->claims()->get('sub');
            $userEmail = $verifiedIdToken->claims()->get('email');

            // Check if the user already exists in your database
            $existingUser = AppUser::where('email', $userEmail)->first();

            if ($existingUser) {
                // User already exists, return an error or handle it as per your requirements
                return $this->sendresponse('false', 'user already exists', null);
            }

            $filename = basename($request->profile . '.png');

            Image::make($request->profile)->save(public_path('server/profile/' . $filename));


            $user = AppUser::create([
                "email" => $request->email,
                "name" => $request->name,
                "profile" => $filename,
                "number" => $request->number,
                "country" => $request->country,
                "banner" => 'default.png',
                "provider" => 'google',
                "user_name" => $request->user_name,
                "access_token" => $uid, // Save Firebase UID for future reference
            ]);

            $token = $user->createToken('apptoken')->plainTextToken;

            // Return success response
            return $this->sendresponse('true', 'user registered successfully', ['user' => $user, 'token' => $token]);
        } catch (\Throwable $e) {
            // Handle registration errors
            return $this->sendresponse('false', 'registration error', $e->getMessage());
        }
    }
}
