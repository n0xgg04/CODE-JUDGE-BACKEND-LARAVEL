<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Compiler extends Controller
{

    public function compiler(Request $request)
    {
        $post = $request->validate([
            'lang' => 'required',
            'source_code' => 'required'
        ]);
        return $this->runCode($post['lang'],$post['source_code']);
    }

    public function runCode($lang,$code){
        $codeId = md5(now().Str::random(35));
        if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/storage/code/'.$codeId.'/')){
            mkdir($_SERVER['DOCUMENT_ROOT'].'/storage/code/'.$codeId.'/',0777,true);
            file_put_contents($_SERVER['DOCUMENT_ROOT'].'/storage/code/'.$codeId.'/main.'.$lang,$code);
            chmod($_SERVER['DOCUMENT_ROOT'].'/storage/code/'.$codeId.'/main.'.$lang, 0777);
            try {
                switch($lang){
                    case 'c':
                        $result = exec("zsh ./script.sh gcc {$_SERVER['DOCUMENT_ROOT']}/storage/code/{$codeId}/main.{$lang} -o {$_SERVER['DOCUMENT_ROOT']}/storage/code/{$codeId}/main",$com);
                        if(count($com)==1){
                            $result = exec("zsh {$_SERVER['DOCUMENT_ROOT']}/storage/main 2>&1",$res);
                            if(count($res)==1) return response()->input([
                                "time" => $com[0],
                                "output" => $res[0]
                            ],200);
                        }
                    return response()->json(['result' => 'error', 'message'=> $com,'lang_sent' => $lang,'path' => $_SERVER['DOCUMENT_ROOT']],200);
                    break;

                    default:
                        return response()->json(['result' => 'error', 'message'=>'lang_not_found','lang_sent' => $lang],200);
                        break;

                }
                }catch(\Exception $ie){
                    return $ie->getMessage();
                }
        }
       // $result = shell_exec('./scripts.sh '.$lang.' '.$);
        return null;
    }
}
