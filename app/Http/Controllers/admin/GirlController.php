<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use App\User;
use App\Girl;
use App\Media;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Rules\FilesJsonMax;
use App\Rules\FilesJsonMin;
use App\Rules\FilesJsonArray;
use App\Rules\FilesJsonBase64;
use App\Rules\FilesJsonMaxResolution;
use Illuminate\Support\Facades\File;


use Illuminate\Support\Facades\Storage;

class GirlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //Get men fro DB
        $grilsDB = Girl::with('user')->get();

        //Form men for front end
        foreach ($grilsDB as $k => $grilDB) {
            $grilsDB[$k]['id'] = $grilDB->user->id;
        }

        return view('admin.pages.girls')->with('girls',json_encode($grilsDB));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.addGirl');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // dd($request->video);
        
        //  Validatoe
        $customErrors = [
            'min' => 'The :attribute field is required',
            'numeric' => 'Error in :attribute field',
            'max' => 'Error in :attribute field',
        ];
        Validator::make($request->all(), [
            'name'                  => 'required|min:2|max:35',
            'day'                   => 'required|numeric|min:1|max:31',
            'month'                 => 'required|numeric|min:1|max:12',
            'year'                  => 'required|numeric|min:1900',
            'location'              => 'required|min:3|max:35',
            'weight'                => 'required|numeric|min:20|max:255',
            'height'                => 'required|numeric|min:50|max:255',
            'hair'                  => 'required|numeric|min:1|max:8',
            'eyes'                  => 'required|numeric|min:1|max:5',
            'religion'              => 'required|numeric|min:1|max:4',
            'education'             => 'required|numeric|min:1|max:6',
            'maritial'              => 'required|numeric|min:1|max:3',
            'children'              => 'required|numeric|min:0|max:11',
            'smoking'               => 'required|numeric|min:1|max:2',
            'alcohol'               => 'required|numeric|min:1|max:2',
            'english'               => 'required|numeric|min:1|max:5',
            'prefferFrom'           => 'numeric|min:16|max:99',
            'prefferTo'             => 'numeric|min:16|max:99',
            'email'                 => 'required|unique:users|email',
            'info'                  => 'required|min:350|max:3500',
            'firstLetterSubject'    => 'required|min:5|max:255',
            'firstLetter'           => 'required|min:800|max:3500',
            'forAdminName'          => 'required|min:2|max:35',
            'forAdminSurname'       => 'required|min:2|max:35',
            'forAdminFathersName'   => 'required|min:2|max:35',
            'forAdminPhoneNumber'   => 'required|min:2|max:35',
            'photos'                => [
                'bail',
                'required',
                'JSON', 
                new FilesJsonArray, 
                new FilesJsonMin(5), 
                new FilesJsonMax(10),
                new FilesJsonBase64,
                new FilesJsonMaxResolution(),
            ],
            'passport'              => [
                'bail',
                'required',
                'JSON', 
                new FilesJsonArray, 
                new FilesJsonMin(1), 
                new FilesJsonMax(1),
                new FilesJsonBase64,
                new FilesJsonMaxResolution(),
            ],
            'video'                 => [
                'bail',
                'nullable',
                'JSON', 
                new FilesJsonArray, 
                new FilesJsonMin(0), 
                new FilesJsonMax(1),
                new FilesJsonBase64,
            ],
        ], [
            // day
            'day.min' => $customErrors['min'],
            'day.numeric' => $customErrors['numeric'],
            'day.max' => $customErrors['max'],
            // month
            'month.min' => $customErrors['min'],
            'month.numeric' => $customErrors['numeric'],
            'month.max' => $customErrors['max'],    
            // hair
            'hair.min' => $customErrors['min'],
            'hair.numeric' => $customErrors['numeric'],
            'hair.max' => $customErrors['max'],
            // eyes
            'eyes.min' => $customErrors['min'],
            'eyes.numeric' => $customErrors['numeric'],
            'eyes.max' => $customErrors['max'],
            // religion
            'religion.min' => $customErrors['min'],
            'religion.numeric' => $customErrors['numeric'],
            'religion.max' => $customErrors['max'],
            // education
            'education.min' => $customErrors['min'],
            'education.numeric' => $customErrors['numeric'],
            'education.max' => $customErrors['max'],
            // maritial
            'maritial.min' => $customErrors['min'],
            'maritial.numeric' => $customErrors['numeric'],
            'maritial.max' => $customErrors['max'],
            // children
            'children.min' => $customErrors['min'],
            'children.numeric' => $customErrors['numeric'],
            'children.max' => $customErrors['max'],
            // smoking
            'smoking.min' => $customErrors['min'],
            'smoking.numeric' => $customErrors['numeric'],
            'smoking.max' => $customErrors['max'],
            // alcohol
            'alcohol.min' => $customErrors['min'],
            'alcohol.numeric' => $customErrors['numeric'],
            'alcohol.max' => $customErrors['max'],
            // english
            'english.min' => $customErrors['min'],
            'english.numeric' => $customErrors['numeric'],
            'english.max' => $customErrors['max'],
        ])->validate();
  

        //  Girl data
        $girlData = [
            'user' => NULL,
            'name' => $request->name,
            'birth' => $request->year.'-'.$request->month.'-'.$request->day,
            'location' => $request->location,
            'weight' => $request->weight,
            'height' => $request->height,
            'hair' => $request->hair,
            'eyes' => $request->eyes,
            'religion' => $request->religion,
            'education' => $request->education,
            'maritial' => $request->maritial,
            'children' => $request->children,
            'smoking' => $request->smoking,
            'alcohol' => $request->alcohol,
            'english' => $request->english,
            'prefferFrom' => $request->prefferFrom,
            'prefferTo' => $request->prefferTo,
            'info' => $request->info,
            'firstLetterSubject' => $request->firstLetterSubject,
            'firstLetter' => $request->firstLetter,
            'forAdminName' => $request->forAdminName,
            'forAdminSurname' => $request->forAdminSurname,
            'forAdminFathersName' => $request->forAdminFathersName,
            'forAdminPhoneNumber' => $request->forAdminPhoneNumber,
        ];

        // Files
        $photos = json_decode($request->photos);
        $passport = json_decode($request->passport);



        // Store
        try {

            DB::beginTransaction();

            //Prepare User
            $id = User::create(['email' => $request->email, 'password' => Hash::make(rand())])->id;
            //Prepare Girl
            $girlData['user'] = $id;
            $girlId = Girl::create($girlData)->id;

            //Save Photos            
            $photos = new Media($photos,$girlId);
            $photos->savePhotos();
            //Save Passport
            $passport = new Media($passport,$girlId);
            $passport->savePassport();            

            //Store to DB
            DB::commit();

         } catch (Exception $e) {
            // Rollback from DB
            DB::rollback();

            // Rollback files            
            $photos->rollback();
            $passport->rollback();
        }

        return redirect()->route('adminGirlIndex');
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
