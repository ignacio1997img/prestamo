<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
// use Intervention\Image;
use Intervention\Image\ImageManagerStatic as Image;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function image($file, $id, $type){
        // return $type;
        Storage::makeDirectory($type.'/'.date('F').date('Y'));
        $base_name = $id.'@'.Str::random(40);

        // return $base_name;
        
        // imagen normal
        $filename = $base_name.'.'.$file->getClientOriginalExtension();
        $image_resize = Image::make($file->getRealPath())->orientate();
        $image_resize->resize(1200, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        
        $path =  $type.'/'.date('F').date('Y').'/'.$filename;
        $image_resize->save(public_path('../storage/app/public/'.$path));
        $imagen = $path;

        // imagen mediana
        $filename_medium = $base_name.'_medium.'.$file->getClientOriginalExtension();
        $image_resize = Image::make($file->getRealPath())->orientate();
        $image_resize->resize(650, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $path_medium = $type.'/'.date('F').date('Y').'/'.$filename_medium;
        $image_resize->save(public_path('../storage/app/public/'.$path_medium));
        // return 11;


        // imagen pequeña
        $filename_small = $base_name.'_small.'.$file->getClientOriginalExtension();
        $image_resize = Image::make($file->getRealPath())->orientate();
        $image_resize->resize(260, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $path_small = $type.'/'.date('F').date('Y').'/'.$filename_small;
        $image_resize->save(public_path('../storage/app/public/'.$path_small));



        // imagen Recortada
        $filename_cropped = $base_name.'_cropped.'.$file->getClientOriginalExtension();
        $image_resize = Image::make($file->getRealPath())->orientate();
        $image_resize->resize(300, 250, function ($constraint) {
            $constraint->aspectRatio();
        });
        $path_cropped = $type.'/'.date('F').date('Y').'/'.$filename_cropped;
        $image_resize->save(public_path('../storage/app/public/'.$path_cropped));

        return $imagen;
    }

    public function file($file, $id, $type)
    {
        $newFileName = $id.'@'.Str::random(20).time().'.'.$file->getClientOriginalExtension();
                            
        $dir = $type.'/'.date('F').date('Y');
                            
        Storage::makeDirectory($dir);

        Storage::disk('public')->put($dir.'/'.$newFileName, file_get_contents($file));                    
                    $video = $dir.'/'.$newFileName;
                    // $ok->update(['video' => $video]);
        return $video;
    }
}
