<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class FunctionController extends Controller
{
    public function index(Request $request){
        $data = array();
        $query = $request->query();

        $data['sites'] = DB::table('sitelist')
            ->get()
            ->toArray();

        return view('home', $data);
    }


    public function addSite(Request $request){
        $data = array();
        $input = $request->input();

        if(empty($input['webtitle'])){
            return redirect('?err=1');
            die();
        }

        if (!filter_var($input['weburl'], FILTER_VALIDATE_URL)) {
            return redirect('?err=2');
            die();
        }


        DB::table('sitelist')
            ->insert([
                'name' => $input['webtitle'],
                'url' => $input['weburl'],
                'wordpress_active' => $input['wordpress'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);

        return redirect('?nt=1');

        dd($input);
    }
}
