<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Titles;
use App\Models\Todos;
use Illuminate\Support\Facades\Auth;

class ToDosController extends Controller{
    
    public function index(Request $request){

        $auth = Auth::user();
        $userId = $auth->id;
        $toDoList = [];
        $titles = Titles::findByUserId($userId);
        foreach($titles as $t){  
            $list = [];      
            foreach($t->toDoList as $tl){
                $list[] = array(
                    "todo"       => $tl->todo,
                    "is_done"    => $tl->is_done,
                    "created_at" => date('F j, Y g:i A', strtotime($tl->created_at)),
                    "updated_at" => date('F j, Y g:i A', strtotime($tl->updated_at)),
                    "id"         => $tl->id
                );           
            }

            $toDoList[$t->name] = array(
                "created_at" => date('F j, Y g:i A', strtotime($t->created_at)),
                "updated_at" => date('F j, Y g:i A', strtotime($t->updated_at)),
                "id"         => $t->id,
                "list"       => $list
            );
        }
        
        return view('to-dos')->with([
            "toDoList" => $toDoList
        ]);
    }

    public function registerToDos(Request $request){
        $params = $request->all();

        $auth = Auth::user();
        $userId = $auth->id;

        if(isset($params['id']))
            $toDo = Titles::findOrNew($params['id']);
        else
            $toDo = new Titles();

        $toDo->name = $params['title'];
        $toDo->created_by = $userId;
        $toDo->save();

        
        foreach($params['todos'] as $t)
            if(!isset($t['id']))
                $toDo->toDoList()->create([
                    'title_id' => $toDo->id,
                    'todo' 	=> $t['todo']
                ]);

        $toDo->save();

        foreach($params['todos'] as $t)
            if(isset($t['id'])){
                $todos = Todos::find($t['id']);
                $todos->todo = $t['todo'];
                $todos->is_done = $t['is_done'];
                $todos->save();
            }

        $response['success'] = true;
        return $response;
    }
}
