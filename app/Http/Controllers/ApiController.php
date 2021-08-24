<?php

namespace App\Http\Controllers;

use App\Models\Registros;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;

header('Content-type: application/json; charset=UTF-8');
class ApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $fillable = ['type', 'message','is_identified', 'whistleblower_name','whistleblower_birth', 'created_at', 'deleted'];

    public function __construct()
    {
        //
    }

    public function index()
    {
        if (empty($_GET['type']) || !isset($_GET['deleted'])) {
            $array = array(
                "status" => "N",
                "msg" => "Não deixe nenhum campo em branco!"
            );
            return json_encode($array, JSON_PRETTY_PRINT);
        }

        $deleted = $_GET['deleted'];
        $type = $_GET['type'];

        if ($deleted != "0" && $deleted != "1") {
            $array = array(
                "status" => "N",
                "msg" => "Tipo de deleted inválido!"
            );
            return json_encode($array, JSON_PRETTY_PRINT);
        }

        if ($type != "denuncia" && $type != "sugestao" && $type != "duvida") {
            $array = array(
                "status" => "N",
                "msg" => "Tipo de denuncia inválido!"
            );
            return json_encode($array, JSON_PRETTY_PRINT);
        }
        $query = Registros::where('type', $type)->where('deleted' , $deleted);
        if($query->count() >= 1){
            foreach($query->get() as $result) {
                if ($result->deleted == "0") {
                    $delet = "false";
                } else {
                    $delet = "true";
                }
                $registro[] = array(
                    "id" => $result->id,
                    "type" => $result->type,
                    "message" => $result->message,
                    "is_identified" => $result->is_identified,
                    "whistleblower_name" => $result->whistleblower_name,
                    "whistleblower_birth" => $result->whistleblower_birth,
                    "created_at" => $result->created_at,
                    "deleted" => $delet
                );
            }
            return json_encode($registro, JSON_PRETTY_PRINT);
            // Tentei fazer uma view para retornar de forma mais bonita, muito bruto.
             //return View::make("return")->with("json", json_encode($registro));
        } else {
            $array = array(
                "status" => "N",
                "msg" => "Nenhum registro encontrado com esse filtro!"
            );
            return json_encode($array, JSON_PRETTY_PRINT);
        }

    }

    public function get($id){

        $query = Registros::where('id', $id);
        if($query->count() >= 1) {
            $result = $query->get();
            $registro[] = array(
                "id" => $result[0]->id,
                "type" => $result[0]->type,
                "message" => $result[0]->message,
                "is_identified" => $result[0]->is_identified,
                "whistleblower_name" => $result[0]->whistleblower_name,
                "whistleblower_birth" => $result[0]->whistleblower_birth,
                "created_at" => $result[0]->created_at,
                "deleted" => $result[0]->deleted
            );
            return json_encode($registro, JSON_PRETTY_PRINT);
            // Tentei fazer uma view para retornar de forma mais bonita, muito bruto.
            //return View::make("return")->with("json", json_encode($registro));
        } else {
            $array = array(
                "status" => "N",
                "msg" => "Nenhum registro encontrado com esse filtro!"
            );
            return json_encode($array, JSON_PRETTY_PRINT);
        }
    }

    public function post(Request $request){
        $valor = $request->all();
        var_dump($valor);
        $validator = \Validator::make($valor, [
            'type' => ['required', 'string', Rule::in(['duvida', 'sugestao','denuncia']),],
            'message' => 'required|string',
            'is_identified' => 'required|int',
            'whistleblower_name' => 'string',
            'whistleblower_birth' => 'string',
            'deleted' => 'required|int',
        ]);
        if ($validator->fails()) {
            return $validator->errors();
        }

        $today = date("Y-m-d H:i:s");

        $post = new Registros;

        $post->type = $request->type;
        $post->message = $request->message;
        $post->is_identified = $request->is_identified;
        $post->deleted = $request->deleted;
        $post->created_at = $today;
        if(isset($request->whistleblower_name)) {
            $post->whistleblower_name = $request->whistleblower_name;
        }
        if(isset($request->whistleblower_birth)) {
            $post->whistleblower_birth = $request->whistleblower_birth;
        }
        $post->save();
        $array = array(
            "status" => "Y",
            "msg" => "POST inserido com sucesso!"
        );
        return json_encode($array, JSON_PRETTY_PRINT);
    }


    public function delete($id){
        $query = Registros::where('id', $id);
        if($query->delete()){
            $array = array(
                "status" => "Y",
                "msg" => "ID: $id deletado com sucesso!"
            );
        } else {
            $array = array(
                "status" => "N",
                "msg" => "ID: $id nao encontrado!"
            );
        }
        return json_encode($array, JSON_PRETTY_PRINT);
        // Tentei fazer uma view para retornar de forma mais bonita, muito bruto.
        //return View::make("return")->with("json", json_encode($array));
    }

    public function update($id, Request $request){
        if (Registros::where('id', $id)->count()) {
            $valor = $request->all();
            $validator = \Validator::make($valor, [
                'type' => ['required', 'string', Rule::in(['duvida', 'sugestao','denuncia']),],
                'message' => 'required|string',
                'is_identified' => 'required|int',
                'whistleblower_name' => 'string',
                'whistleblower_birth' => 'string',
                'deleted' => 'required|int',
            ]);
            if ($validator->fails()) {
                return $validator->errors();
            }
            try {
                Registros::where('id', $id)->update($request->all());
                $response = array(
                    "status" => "Y",
                    "msg" => "Campos atualizados!"
                );
                return json_encode($response, JSON_PRETTY_PRINT);
                // Tentei fazer uma view para retornar de forma mais bonita, muito bruto.
                //return View::make("return")->with("json", json_encode($response));
            } catch(QueryException $ex){
                $response = array(
                    "status" => "N",
                    "msg" => "Erro ao atualizar o id: $id!"
                );
                return json_encode($response, JSON_PRETTY_PRINT);
            }
        }else {
            $response = array(
                "status" => "N",
                "msg" => "Não há nenhum registro com esse ID!"
            );
        }
        return json_encode($response, JSON_PRETTY_PRINT);
    }
}
