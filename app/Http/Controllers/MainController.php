<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Background;
use Storage;
use  Illuminate\Support\Facades\File;

class MainController extends Controller
{
    public function createUser(Request $request){
        $this->validate($request, [
             'name'=>'required|max:50', 
             'user_sid'=>'required|max:250',
             'avatar'=>'required',
             'age'=>'required|between:0,110',
             'email' => 'required|email|unique:users', 
             'password' => 'required|min:6'
        ]);
        User::create($request->only(['name','user_sid','avatar','age','email','password']));
            return $this->success('proceso ejecutado correctamente', 200);
    }

     public function updateUser(Request $request){
        $this->validate($request, [
             'name'=>'required|max:50', 
             'user_sid'=>'required|max:250',
             'avatar'=>'required',
             'age'=>'required|between:0,110',
             'email' => 'required|email', 
             'password' => 'required|min:6'
        ]);
        $user=User::where('user_sid','=',$request->user_sid)->first();
        if($user!=null){
            $user->name=$request->name;
            $user->avatar=$request->avatar;
            $user->age=$request->age;
            $user->email=$request->email;
            $user->password=$request->password;
             $user->save();
                return $this->success('proceso ejecutado correctamente', 200);
        }
       
            return $this->error('Registro no encontrado', 404);
    }


    public function saveBackground(Request $request){
        $this->validate($request, [
             'file'=>'required|file', 
             'name'=>'required|unique:backgrounds'
        ]);
        $image = $request->file('file');
        $name = uniqid('BACK-'). "." .$image->getClientOriginalExtension();
        Storage::disk('background')->put($name,File::get($image));
        $objBackground=new Background();
        $objBackground->name=$request->name;
        $objBackground->url=$name;
        $objBackground->save();
            return $this->success('proceso ejecutado correctamente', 200);
    }

     public function getBackground($id){
        $result=[];
        if($id=='all'){
            $result=Background::select('id','name','url')->get()->toArray();
        }else{
            $result=Background::findOrFail($id)->url;
            return $this->getFile('background',$result);
        }
            return $this->success($result, 200);

    }

    private function getFile($module,$name){
        $url = storage_path() . "/app/".$module."/".$name;
        if (file_exists($url)) {
            return response()->download($url, null, [], null);
        }else{
            throw new \Exception("Imagen no encontrada");
        }
}
}
