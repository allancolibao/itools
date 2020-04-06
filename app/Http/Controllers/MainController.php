<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;
use Validator;
use Response;
use Carbon\Carbon;
use App\Listings;
use App\Formlist;
use App\F11;
use App\Log;
use Hash;
use DB;



class MainController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


   /**
     * Show the home page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('blades.home');
    }


    /**
     * Search function
     * 
     * 
     */
    public function search(Request $request)
    {

        $rules = array
        (
            'key' => 'required|min:9|max:12'
        );
    
        $validator = Validator::make ( $request->all(), $rules);
        if ($validator->fails())
        return redirect('/home')->with('error', 'Please re-enter the eacode [ 9-12 digits required ], Thank you');
        
        else 
        {
        $key = $request->get('key');

        $lists = Listings::where('eacode','LIKE','%'.$key.'%')->get();

        return view('blades.area',compact('lists'));
        }   
    }

    /**
     * Show the listings page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function listings($eacode)
    {
        $lists = Listings::where('eacode','=', $eacode)->get();

        return view('blades.area',compact('lists'));
    }



    /**
     * Show the households page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function household($eacode, $hcn, $shsn, $hh)
    {
        return view('blades.household', compact('eacode', 'hcn', 'shsn', 'hh'));
    }


    /**
    * Show the individuals page.
    *
    * @return \Illuminate\Contracts\Support\Renderable
    */
    public function individuals($eacode, $hcn, $shsn)
    {
        $indivs = F11::where('eacode','LIKE','%'.$eacode.'%')
                     ->where('hcn','LIKE','%'.$hcn.'%' )
                     ->where('shsn','LIKE','%'.$shsn.'%' )
                     ->get();

       return view('blades.individuals', compact('indivs','eacode', 'hcn', 'shsn'));
    }


     /**
    * Show the exact individual page.
    *
    * @return \Illuminate\Contracts\Support\Renderable
    */
    public function individual($eacode, $hcn, $shsn, $memcode, $surname, $givenname)
    {
        $indiv = F11::where('eacode','LIKE','%'.$eacode.'%')
                     ->where('hcn','LIKE','%'.$hcn.'%' )
                     ->where('shsn','LIKE','%'.$shsn.'%' )
                     ->where('MEMBER_CODE','LIKE','%'.$memcode.'%' )
                     ->get();

       return view('blades.individual', compact('indiv','eacode', 'hcn', 'shsn','memcode','surname','givenname'));
    }


    /**
     * Update the household based on the conditions.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function updateHousehold(Request $request, $eacode, $hcn, $shsn, $hh)
    {
        $listing = DB::connection('sqlite2')->table('localarea_listings');

        $rules = array(

            'eacode' => Rule::unique('sqlite2.'.$listing->from)->where(function ($query) use ($request) {
                        return $query
                        ->where('eacode','=', $request->get("eacode"))
                        ->where('hcn','=', $request->get("hcn"))
                        ->where('shsn','=', $request->get("shsn"));
                })
            );

        $validator = Validator::make ( $request->all(), $rules);
        if ($validator->fails()) {

            $rules = array(

                'eacode' => Rule::unique('sqlite2.f11')->where(function ($query) use ($request) {
                            return $query
                            ->where('eacode','=', $request->get("eacode"))
                            ->where('hcn','=', $request->get("hcn"))
                            ->where('shsn','=', $request->get("shsn"));
                    })
                );
                
    
            $validator = Validator::make ( $request->all(), $rules);
            if ($validator->fails()) {

                return redirect()->back()->with('error', 'Unable to update [ Households ], Have an existing members!');

            } else {

                // Getting the table
                $forms = Formlist::where('f_type','!=','0')->get();

                // Getting the tect user input
                $updateHousehold = [
                    'eacode' => $request->get('eacode'),
                    'hcn' => $request->get('hcn'),
                    'shsn' => $request->get('shsn'),
                ];

                // Gettting the local researcher username
                $lr_username = F11::select('username')
                                ->where('eacode', $request['eacode'])
                                ->distinct()
                                ->get();

                // Getting the date
                $today = Carbon::now()->toDateString();

                // Getting the authenticated user
                $tech_user = Auth::user()->username;

                // Updating the tables
                foreach ($forms as $form){

                $update_household = DB::connection('sqlite2')
                                    ->table($form->f_name)
                                    ->where('eacode','=', $eacode)
                                    ->where('hcn','=', $hcn)
                                    ->where('shsn','=', $shsn)
                                    ->update($updateHousehold);

                if( ($update_household) && ($request['eacode'] != $eacode || $request['hcn'] != $hcn || $request['shsn']  != $shsn)){

                    Log::create([
                        'log_cat' => '1',
                        'performed_by' => $tech_user,
                        'old_info' => $eacode.' , '.$hcn.' , '.$shsn,
                        'new_info' => $request['eacode'].' , '.$request['hcn'].' , '.$request['shsn'],
                        'on_table' => $form->f_name,
                        'from_researcher' => $lr_username[0]['username'],
                        'action_taken' => $tech_user.' changed EACODE: '.$eacode.' , HCN: '.$hcn.' , SHSN: '.$shsn.' to EACODE: '.$request['eacode'].', HCN: '.$request['hcn'].', SHSN: '.$request['shsn'].' in '.$form->f_name.' on '.$lr_username[0]['username'].' dated '.$today.'.',
                    ]);

                    }
                }
                return redirect()->route('listings', ['eacode'=> $eacode ])->with('success', 'Data successfully updated [ Households ], Except the localarea listings!');
                }

        } else {

            // Getting the table
            $forms = Formlist::all();

            // Getting the tect user input
            $updateHousehold = [
                'eacode' => $request->get('eacode'),
                'hcn' => $request->get('hcn'),
                'shsn' => $request->get('shsn'),
            ];

            // Gettting the local researcher username
            $lr_username = F11::select('username')
                                ->where('eacode', $request['eacode'])
                                ->distinct()
                                ->get();

             // Getting the date
            $today = Carbon::now()->toDateString();

            // Getting the authenticated user
            $tech_user = Auth::user()->username;

            // Updating the tables
            foreach ($forms as $form){

            $update_household = DB::connection('sqlite2')
                    ->table($form->f_name)
                    ->where('eacode','=', $eacode)
                    ->where('hcn','=', $hcn)
                    ->where('shsn','=', $shsn)
                    ->update($updateHousehold);
            
            if(($update_household) && ($request['eacode'] != $eacode || $request['hcn'] != $hcn || $request['shsn']  != $shsn)){

                Log::create([
                    'log_cat' => '1',
                    'performed_by' => $tech_user,
                    'old_info' => $eacode.' , '.$hcn.' , '.$shsn,
                    'new_info' => $request['eacode'].' , '.$request['hcn'].' , '.$request['shsn'],
                    'on_table' => $form->f_name,
                    'from_researcher' => $lr_username[0]['username'],
                    'action_taken' => $tech_user.' changed '.$hh.' from EACODE: '.$eacode.' , HCN: '.$hcn.' , SHSN: '.$shsn.' to EACODE: '.$request['eacode'].', HCN: '.$request['hcn'].', SHSN: '.$request['shsn'].' in '.$form->f_name.' on '.$lr_username[0]['username'].' dated '.$today.'.',
                ]);

                }
            }

            return redirect()->route('listings', ['eacode'=> $eacode ])->with('success', 'Data successfully updated [ Households ], Please check the eDCS!');
            
        }
    }



    /**
     * Update the individuals based on the conditions.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function updateIndividuals(Request $request, $eacode, $hcn, $shsn, $memcode, $surname, $givenname)
    {
        $rules = array(

            'MEMBER_CODE' => Rule::unique('sqlite2.f11')->where(function ($query) use ($request) {
                        return $query
                        ->where('eacode','=', $request->get("eacode"))
                        ->where('hcn','=', $request->get("hcn"))
                        ->where('shsn','=', $request->get("shsn"))
                        ->where('MEMBER_CODE','=', $request->get("MEMBER_CODE"));
                })
            );

        $validator = Validator::make ( $request->all(), $rules);
        if ($validator->fails()) {

            return redirect()->back()->with('error', 'Unable to update [ Individuals ], Duplicate member!');

        } else {

            // Getting the indiv tables
            $forms = Formlist::where('f_type','=','2')->get();

            // Getting the tect user input
            $updateIndividuals = [
                'eacode' => $request->get('eacode'),
                'hcn' => $request->get('hcn'),
                'shsn' => $request->get('shsn'),
                'MEMBER_CODE' => $request->get('MEMBER_CODE'),
            ];

            // Gettting the local researcher username
            $lr_username = F11::select('username')
                                ->where('eacode', $request['eacode'])
                                ->distinct()
                                ->get();

            // Getting the date
            $today = Carbon::now()->toDateString();

            // Getting the authenticated user
            $tech_user = Auth::user()->username;

            // Updating the tables
            foreach ($forms as $form){

               $update_inviduals = DB::connection('sqlite2')
                    ->table($form->f_name)
                    ->where('eacode','=', $eacode)
                    ->where('hcn','=', $hcn)
                    ->where('shsn','=', $shsn)
                    ->where('MEMBER_CODE','=', $memcode)
                    ->update($updateIndividuals);
            
            if(($update_inviduals) && ($request['eacode'] != $eacode || $request['hcn'] != $hcn || $request['shsn']  != $shsn || $request['MEMBER_CODE'] != $memcode)){

                Log::create([
                    'log_cat' => '2',
                    'performed_by' => $tech_user,
                    'old_info' => $eacode.' , '.$hcn.' , '.$shsn.' , '.$memcode,
                    'new_info' => $request['eacode'].' , '.$request['hcn'].' , '.$request['shsn'].' , '.$request['MEMBER_CODE'],
                    'on_table' => $form->f_name,
                    'from_researcher' => $lr_username[0]['username'],
                    'action_taken' => $tech_user.' changed MEMBER: '.$surname.' '.$givenname.' from EACODE: '.$eacode.' , HCN: '.$hcn.' , SHSN: '.$shsn.' , MEMBER CODE: '.$memcode.' to EACODE: '.$request['eacode'].', HCN: '.$request['hcn'].', SHSN: '.$request['shsn'].' , MEMBER CODE: '.$request['MEMBER_CODE'].' in '.$form->f_name.' on '.$lr_username[0]['username'].' dated '.$today.'.'
                ]);

                }

            }

            return redirect()->route('individuals', ['eacode'=> $eacode, 'hcn'=> $hcn, 'shsn'=> $shsn ])->with('success', 'Data successfully updated [ Individuals ], Please check the eDCS!');
        }
    }


    /**
     *  Getting the household need to tag as replacement.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function tickReplacement(Request $request, $eacode, $hcn, $shsn, $hh)
    {
        $replacement = DB::connection('sqlite2')
                        ->table('localarea_listings')
                        ->where('eacode','=', $eacode)
                        ->where('hcn','=', $hcn)
                        ->where('shsn','=', $shsn)
                        ->first();

        return view('blades.replacement', compact('replacement','eacode','hcn','shsn', 'hh'));
    }


    /**
     *  Update the  replacement information.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function saveReplacement(Request $request, $eacode, $hcn, $shsn, $hh)
    {    
        if(($request['REF_DATE'] === NULL && $request['DATE_EDIT'] != NULL) || ($request['REF_DATE'] != NULL && $request['DATE_EDIT'] === NULL) ){
            return redirect()->back()->with('error', 'Something went wrong, Please double check!');
        } else {  

        $saveReplacement = [
            'REF_DATE' => $request->get('REF_DATE'),
            'DATE_EDIT' => $request->get('DATE_EDIT')
        ];

         $tag_replacement =  DB::connection('sqlite2')
                            ->table('localarea_listings')
                            ->where('eacode','=', $eacode)
                            ->where('hcn','=', $hcn)
                            ->where('shsn','=', $shsn)
                            ->update($saveReplacement);

        $lr_username = F11::select('username')
                            ->where('eacode', $eacode)
                            ->distinct()
                            ->get();

        $today = Carbon::now()->toDateString();
        $tech_user = Auth::user()->username;

        if( $request['REF_DATE'] != NULL && $request['DATE_EDIT'] != NULL){

            Log::create([
                'log_cat' => '3',
                'performed_by' => $tech_user,
                'old_info' => '',
                'new_info' => $request['DATE_EDIT'],
                'from_researcher' => $lr_username[0]['username'],
                'on_table' => 'localarea_listings',
                'action_taken' => $tech_user.' tagged '.$hh.' with EACODE: '.$eacode.' , HCN: '.$hcn.' , SHSN: '.$shsn.' as replacement to HCN/SHSN: '.$request['DATE_EDIT'].' on '.$lr_username[0]['username'].' dated '.$today.'.',
            ]);

        } elseif ($request['REF_DATE'] === NULL && $request['DATE_EDIT'] === NULL) {

            Log::create([
                'log_cat' => '3',
                'performed_by' => $tech_user,
                'old_info' => '',
                'new_info' => '',
                'from_researcher' => $lr_username[0]['username'],
                'on_table' => 'localarea_listings',
                'action_taken' => $tech_user.' untagged '.$hh.' with EACODE: '.$eacode.' , HCN: '.$hcn.' , SHSN: '.$shsn.' as replacement  on '.$lr_username[0]['username'].' dated '.$today.'.',
            ]);
        }
        
        return redirect()->route('listings', ['eacode'=> $eacode ])->with('success', 'Data successfully updated [ Replacement ], Please check the eDCS!');
        }
    }

    
     /**
     *  Getting the household want to delete on listings.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function todeleteListings(Request $request, $eacode, $hcn, $shsn, $hh)
    {

        return view('blades.delete', compact('eacode','hcn','shsn', 'hh'));

    }

     /**
     *  Delete household on listings.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function delete(Request $request, $eacode, $hcn, $shsn, $hh)
    {   
       
        if(Hash::check(request('password'), Auth::user()->password))
        {
           $delete_listings = DB::connection('sqlite2')
                            ->table('localarea_listings')
                            ->where('eacode','=', $eacode)
                            ->where('hcn','=', $hcn)
                            ->where('shsn','=', $shsn)
                            ->delete();

            $lr_username = F11::select('username')
                            ->where('eacode', $eacode)
                            ->distinct()
                            ->get();

            $today = Carbon::now()->toDateString();
            $tech_user = Auth::user()->username;

            if($delete_listings){

                Log::create([
                    'log_cat' => '4',
                    'performed_by' => $tech_user,
                    'old_info' => $eacode.' , '.$hcn.' , '.$shsn.' , '.$hh,
                    'new_info' => '',
                    'from_researcher' => $lr_username[0]['username'],
                    'on_table' => 'localarea_listings',
                    'action_taken' => $tech_user.' deleted '.$hh.' with EACODE: '.$eacode.' , HCN: '.$hcn.' , SHSN: '.$shsn.' in localarea listings on '.$lr_username[0]['username'].' dated '.$today.'.',
                ]);

            }

            return redirect()->route('listings', ['eacode'=> $eacode ])->with('success', 'Data successfully deleted [ Listings ], Please check the eDCS!');
        } else {
            return redirect()->back()->with('error', 'Unable to delete [ Household ], Incorrect password!');
        }
    }


     /**
     *  Getting the household want to delete on all tables (except listings)
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function todeleteAllTables(Request $request, $eacode, $hcn, $shsn, $hh)
    {

        return view('blades.delete-all-tables', compact('eacode','hcn','shsn', 'hh'));

    }



    /**
     *  Delete on all tables (except listings)
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function deleteAllTables(Request $request, $eacode, $hcn, $shsn, $hh)
    {   
     
        if(Hash::check(request('password'), Auth::user()->password))
        {       
                // Getting the table
                $forms = Formlist::where('f_type','!=','0')->get();

                // Gettting the local researcher username
                $lr_username = F11::select('username')
                            ->where('eacode', $eacode)
                            ->distinct()
                            ->get();

                 // Getting the date
                $today = Carbon::now()->toDateString();

                // Getting the authenticated user
                $tech_user = Auth::user()->username;

                // Deleting the data
                foreach ($forms as $form){

                $delete_all_tables = DB::connection('sqlite2')
                                    ->table($form->f_name)
                                    ->where('eacode','=', $eacode)
                                    ->where('hcn','=', $hcn)
                                    ->where('shsn','=', $shsn)
                                    ->delete();

                if($delete_all_tables){

                    Log::create([
                        'log_cat' => '5',
                        'performed_by' => $tech_user,
                        'old_info' => $eacode.' , '.$hcn.' , '.$shsn.' , '.$hh,
                        'new_info' => '',
                        'on_table' => $form->f_name,
                        'from_researcher' => $lr_username[0]['username'],
                        'action_taken' => $tech_user.' deleted '.$hh.' with EACODE: '.$eacode.' , HCN: '.$hcn.' , SHSN: '.$shsn.' in '.$form->f_name.' on '.$lr_username[0]['username'].' dated '.$today.'.',
                    ]);
    
                    }
                } 
            return redirect()->route('listings', ['eacode'=> $eacode ])->with('success', 'Data successfully deleted [ All tables ], Please check the eDCS!');
        } else {
            return redirect()->back()->with('error', 'Unable to delete [ All tables ], Incorrect password!');
        }
    }


    /**
     *  Getting the individual want to delete on all individual tables
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function todeleteIndividual(Request $request, $eacode, $hcn, $shsn, $memcode, $surname, $givenname)
    {

        return view('blades.to-delete-indiv', compact('eacode','hcn','shsn', 'memcode', 'surname', 'givenname'));

    }


    /**
     *  Delete the individual on all individual tables
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function deleteIndividual(Request $request, $eacode, $hcn, $shsn, $memcode, $surname, $givenname)
    {
        if(Hash::check(request('password'), Auth::user()->password))
        {       
                // Getting the table
                $forms = Formlist::where('f_type','=','2')->get();

                // Gettting the local researcher username
                $lr_username = F11::select('username')
                            ->where('eacode', $eacode)
                            ->distinct()
                            ->get();

                 // Getting the date
                $today = Carbon::now()->toDateString();

                // Getting the authenticated user
                $tech_user = Auth::user()->username;

                // Deleting the data
                foreach ($forms as $form){

                    $delete_all_tables = DB::connection('sqlite2')
                                        ->table($form->f_name)
                                        ->where('eacode','=', $eacode)
                                        ->where('hcn','=', $hcn)
                                        ->where('shsn','=', $shsn)
                                        ->where('MEMBER_CODE','=', $memcode)
                                        ->delete();
                    
                    if($delete_all_tables){

                            Log::create([
                                'log_cat' => '6',
                                'performed_by' => $tech_user,
                                'old_info' => $eacode.' , '.$hcn.' , '.$shsn.' , '.$memcode.' , '.$surname.', '.$givenname,
                                'new_info' => '',
                                'on_table' => $form->f_name,
                                'from_researcher' => $lr_username[0]['username'],
                                'action_taken' => $tech_user.' deleted '.$surname.', '.$givenname.' with EACODE: '.$eacode.' , HCN: '.$hcn.' , SHSN: '.$shsn.', MEMBER NO: '.$memcode.' in '.$form->f_name.' on '.$lr_username[0]['username'].' dated '.$today.'.',
                            ]);
            
                    }
                }  
            return redirect()->route('individuals', ['eacode'=> $eacode, 'hcn'=> $hcn, 'shsn'=> $shsn ])->with('success', 'Data successfully deleted [ Individuals ], Please check the eDCS!');
        } else {
            return redirect()->back()->with('error', 'Unable to delete [ Individual ], Incorrect password!');
        }
    }

}
