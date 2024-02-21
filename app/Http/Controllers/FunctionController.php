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


        $data['errors'] = array(
            1 => ['Error: Enter a valid Title', 'danger'],
            2 => ['Error: Enter a valid Web URL', 'danger'],
        );
        if(!empty($query['err'])){
            $data['err'] = $query['err'];
        }

        $data['notifs'] = array(
            1 => ['Website added successfully', 'info'],
            2 => ['Website modified successfully', 'info'],
            3 => ['Website removed', 'info'],
        );
        if(!empty($query['nt'])){
            $data['nt'] = $query['nt'];
        }

        $data['sites'] = DB::table('sitelist')
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();

        return view('home', $data);
    }


    public function addSite(Request $request){
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
    }

    public function editSite(Request $request){
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
            ->where('id', $input['edit-id'])
            ->update([
                'name' => $input['webtitle'],
                'url' => $input['weburl'],
                'wordpress_active' => $input['wordpress'],
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);

        return redirect('?nt=2');
    }

    public function deleteSite(Request $request){
        $query = $request->query();

        DB::table('sitelist')
            ->where('id', $query['id'])
            ->delete();

        return redirect('?nt=3');
    }
}
