<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;
use Response;
use App\F11;
use App\Listings;


class MainController extends Controller
{
   /**
     * Show the home page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('index');
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
        return redirect('/')->with('error', 'Please re-enter the eacode [ 9-12 digits required ], Thank you');
        
        else 
        {
        $key = $request->get('key');

        $lists = Listings::where('eacode','LIKE','%'.$key.'%')->get();

        return view('area',compact('lists'));
        }   
    }

    /**
     * Show the household page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function household($eacode, $hcn, $shsn)
    {
        return view('household', compact('eacode', 'hcn', 'shsn'));
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

       return view('individuals', compact('indivs','eacode', 'hcn', 'shsn'));
    }

     /**
    * Show the individual page.
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

       return view('individual', compact('indiv','eacode', 'hcn', 'shsn','memcode','surname','givenname'));
    }

}
