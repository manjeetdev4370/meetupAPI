<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Participants;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;
use Illuminate\Http\Request; 
use Carbon\Carbon;
use DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin';
    public $name  = '';
    public $locality = '';
    public $isPagiante = true;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    protected function createParticipants(Request $request)
    {
        $validationArr = [
            'name' => "required|max:255|regex:/^[a-z ,.'-]+$/i",
            'dob' => 'required|date',
            'age' => "required|in:".Carbon::parse($request->dob)->age,
            'profession' => 'required|in:Employed,Student',
            'locality' => 'required|max:50',
            'noofguest' => 'required|in:0,1,2',
            'address' => 'nullable|max:50',//|regex:/^[a-zA-Z0-9' .\-]+$/
        ];
        
        $validator = \Validator::make($request->all(), $validationArr);

        if ($validator->fails()) {
            return response()->json(['success'=>false, 'message'=>$validator->messages()], 200);
        }

        try{
            DB::beginTransaction();

            $userData = new User;
            $userData->name = $request->name;
            $userData->dob = $request->dob;
            $userData->age = $request->age;
            $userData->profession = $request->profession;
            $userData->locality = $request->locality;
            $userData->noofguest = $request->noofguest;
            $userData->address = $request->address;
            $userData->created_at = Carbon::now();
            $userData->updated_at = Carbon::now();

            $userData->save();

            DB::commit();

        } catch(Exception $e){
            DB::rollback();

            $jsonArray['error'] = 'error';
            $jsonArray['msg'] = 'Error While Registering Participant';
        }
        
        return response()->json(['success'=>true, 'message'=>'Participant Has been Registered Successfully','userDetails' => $userData]);
    }

    public function getUsers(){
        
        $userData = User::select('*');
        
        if (isset($this->name)) {
            $userData->where('name','like', '%'.$this->name.'%');
        }

        if (isset($this->locality)) {
            $userData->where('locality', 'like', '%'.$this->locality.'%');
        }
        
        if ($this->isPagiante) {
                $userData = $userData->paginate(2);
        }else{
            $userData = $userData->get();
        }

        if(strpos(url()->current(),'admin')){
            return $userData;
        }

        return response()->json(['success'=>true, 'message'=>'List of Registered Participants','participantsDetails' => $userData]);
    }

    public function updateUsers(Request $request,$id){

        try{
            DB::beginTransaction();

            $userData = User::findOrFail($id);

            $userData->name = $request->name ? $request->name : $userData->name;
            $userData->dob = $request->dob ? $request->dob : $userData->dob;
            $userData->age = $request->age ? $request->age : $userData->age;
            $userData->profession = $request->profession ? $request->profession : $userData->profession;
            $userData->locality = $request->locality ? $request->locality : $userData->locality;
            $userData->noofguest = $request->noofguest ? $request->noofguest : $userData->noofguest;
            $userData->address = $request->address ? $request->address : $userData->address;
            $userData->updated_at = Carbon::now() ? Carbon::now() : $userData->updated_at;
            $userData->save();

            DB::commit();

        } catch(Exception $e){
            DB::rollback();

            $jsonArray['error'] = 'error';
            $jsonArray['msg'] = 'Error While Registering Participant';
        }
        
        return response()->json(['success'=>true, 'message'=>'Participant Has been Registered Successfully','userDetails' => $userData]);
    }


}
