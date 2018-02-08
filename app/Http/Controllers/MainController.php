<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Background;
use App\Phrase;
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



    public function savePhrase(Request $request){
         $this->validate($request, [
            'background_id'=>'required|integer|exists:backgrounds,id',
            'user_sid'=>'required|exists:users,user_sid',
            'content'=>'required|string|max:255',
            'fonts'=>'required|string|max:255',
            'color_text'=>'required|string|max:17',
            'file'=>'required|file', 

        ]);
        $objUser=User::where('user_sid','=',$request->user_sid)->firstOrFail(); 
        $image = $request->file('file');
        $name = uniqid('PHR-'). "." .$image->getClientOriginalExtension();
        Storage::disk('phrases')->put($name,File::get($image));
        $objPhrase=new Phrase();
        $objPhrase->background_id=$request->background_id;
        $objPhrase->user_id=$objUser->id;
        $objPhrase->content=$request->content;
        $objPhrase->fonts=$request->fonts;
        $objPhrase->color_text=$request->color_text;
        $objPhrase->url_phrase=$name;
         $objPhrase->approved=0;
        $objPhrase->save();
            return $this->success('proceso ejecutado correctamente', 200);
    }

    public function getPhrases($type,Request $request){
        if($type!='0' && $type!='1' && $type!='2'){
              throw new \Exception("Parametro no valido");
        }else{
             $data=Phrase::join('backgrounds','backgrounds.id','=','phrases.background_id')->
                join('users','users.id','=','phrases.user_id')
                ->where('users.user_sid','=',$request->user_sid);

            if($type=='0' || $type=='1'){
               $data=$data->where('phrases.approved','=',$type);
            }

            return $data->select(['phrases.id','users.name','users.avatar','backgrounds.id as background_id','backgrounds.name as background_name','phrases.content','phrases.fonts','phrases.created_at','phrases.color_text','phrases.url_phrase','phrases.approved'])->get()->toArray();
        }
    }
    public function getPhrase($id){
        $result=[];
        if($id=='all'){
            $result=Phrase::select('id','url_phrase')->get()->toArray();
        }else{
            $result=Phrase::findOrFail($id)->url_phrase;
            return $this->getFile('phrases',$result);
        }
            return $this->success($result, 200);

    }

    public function approvedPhrase($id,Request $request){
        $objUser=User::where('id','=',$request->user_id)->where('rol','=','admin')->first(); 
if($objUser==null){
            throw new \Exception("Usuario no tiene rol apropiado para realizar esta operación", 401);

}
        
         $objPhrase=Phrase::findOrFail($id);
         $objPhrase->approved='1';
         $objPhrase->user_approved=$objUser->id;
         $objPhrase->date_approved=date('Y-m-d h:i:s');
         $objPhrase->save();
         return $this->success('proceso ejecutado correctamente', 200);
    }
}
