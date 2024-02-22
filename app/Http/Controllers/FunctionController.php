<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

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
            4 => ['List Updated', 'info'],
        );
        if(!empty($query['nt'])){
            $data['nt'] = $query['nt'];
        }

        $data['order'] = array(
            0 => ["Default", 'id'],
            1 => ["Name", 'name'],
            2 => ["Date", 'created_at'],
            3 => ["Status", 'active'],
        );
        $data['or'] = 0;
        if(!empty($query['or'])){
            $data['or'] = $query['or'];
        }

        $data['srt'] = 'asc';
        if(!empty($query['srt'])){
            $data['srt'] = $query['srt'];
        }

        $sites = DB::table('sitelist');

        $sites->orderBy($data['order'][$data['or']][1], $data['srt']);

        $data['sites'] = $sites
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

    public function checkSiteStatus(Request $request){
        $sites = DB::table('sitelist')->get();

        foreach($sites as $site){
            if($site->tracking == 1){
                $res = Http::get($site->url);

                if($res->successful()){
                    $rawHtml = $res->body();

                    $cleanHtml = strip_tags($rawHtml);
                    $cleanHtml = preg_replace('/\s+/', ' ', $cleanHtml);

                    $shortenedHtml = substr($cleanHtml, 0, 200);

                    $status = 1;
                } else {
                    $status = 0;
                }


                $escapedUrl = escapeshellarg($site->url);
                $scriptPath = base_path('node_scripts/screenshot.js');
                $filename = 'screenshots/' . md5($site->url) . '_' . date('YmdHis') . '.png';
                $storagePath = storage_path('app/public/' . $filename);

                $directory = dirname($storagePath);
                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                $command = "node $scriptPath $escapedUrl $storagePath";

                exec($command, $output, $returnvar);

                if($returnvar === 0){
                    $publicpath = Storage::url($filename);
                } else {
                    $publicpath = asset('img/Untitled-1 cov.png');
                }

                DB::table('sitelist')
                    ->where('id', $site->id)
                    ->update([
                        'active' => $status,
                        'screenshot' => $publicpath,
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ]);
            }
        }

        return redirect('?nt=4');
    }
}
