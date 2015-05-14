<?php

class TareasController extends BaseController {

  public function saveTarea()
  {
    $path = public_path(). '/adjuntados/' . '/' . date("Y")  . '/' . date("m") . '/' . date("d") . '/';
    if (!file_exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
    }
    try {
      if (Input::hasFile('filePdf'))
      {
          $file = Input::file('filePdf')->move($path, time() . '-' . Input::file('filePdf')->getClientOriginalName() ); 
      }
      //$todos = Input::all();
      $user_id         = Input::get('user_id');
      $folio           = Input::get('folio');
      $fechaRespuesta  = Input::get('fecha_respuesta');
      $asunto          = Input::get('asunto');
      $areaSolicitante = Input::get('area_generadora');
        
      $rules = array(
            'user_id' => 'numeric',
            'folio' => 'numeric|required',
            'fecha_respuesta' => 'date',
            'asunto' => 'string',
            'areaSolicitante' => 'string'
        );
    
      $messages = array(
          'required' => 'El campo es obligatorio.',
          'numeric' => 'El campo debe ser un número.',
          'max' => 'El campo :attribute no puede tener más de :min carácteres.'
      );
     
      $validation = Validator::make(Input::all(), $rules, $messages);
     
      if ($validation->fails())
      {
        
          return Redirect::to('dash')->withErrors($validation)->withInput();
   
      }else{

          $newTarea = Tarea::create(Input::all());
          //Input::file('filePdf')->move(public_path(),'NuevoNombre');
          $id = Input::get('user_id');
          $user = User::find($id);
          //var_dump($user->email);
          Mail::send('emails.newTarea', array('first_name'=>$user->first_name), function($message) use ($user){
            $message->to($user->email, $user->first_name.' '.$user->last_name)->subject('Nueva tarea Asignada');
            $message->attach( public_path(). '/adjuntados/' . '/' . date("Y")  . '/' . date("m") . '/' . date("d") . '/'.time() . '-' . Input::file('filePdf')->getClientOriginalName());
          });
          return Redirect::back();
      }
      
    } catch (Exception $e) {
      return Response::json(array(
          'error' =>  $e
        ));
    }
  }

  public function getTasksSuperAdmin()
  {
    $tasks = DB::table('tareas')
      ->join('users', 'user_id', '=', 'users.id')
      ->get(['tareas.id', 'folio','area_generadora', 'asunto', 'fecha_respuesta', 'user_id', 'estatus', 'oficio_referencia', 'users.first_name']);
    return Response::json(array(
      'tasks' =>  $tasks
    ));
  }

  public function getTasks()
  {
    $tasks = DB::table('tareas')
      ->join('users', 'user_id', '=', 'users.id')
      ->where('user_id', Auth::user()->id)->get(['tareas.id', 'folio','area_generadora', 'asunto', 'estatus', 'fecha_respuesta', 'user_id', 'estatus', 'oficio_referencia', 'users.first_name']);
    return Response::json(array(
      'tasks' =>  $tasks
    ));
  }

  public function getTaskDetailsById($id)
  {
    $tasks = DB::table('tareas')->join('users', 'user_id', '=', 'users.id')
    ->where('tareas.id', $id)
    ->get(['tareas.id', 'folio','area_generadora', 'asunto', 'fecha_recepcion', 'fecha_respuesta', 'nombre_titular', 'ubicacion_topografica', 'user_id', 'estatus', 'oficio_referencia', 'users.first_name', 'admin_id']);
    return Response::json(array(
      'tasks' =>  $tasks
    ));
  }

  public function uploadpdf()
  {
    $path = public_path(). '/adjuntados/' . '/' . date("Y")  . '/' . date("m") . '/' . date("d") . '/';
    if (!file_exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
    }
    if (Input::hasFile('filePdf'))
    {
        $file = Input::file('filePdf')->move($path, time() . '-' . Input::file('filePdf')->getClientOriginalName() ); 
    }
    return Redirect::back();
  }

  public function cleanDD()
  {
    $path = public_path(). '/files/';
    File::deleteDirectory($path,false);
    return Redirect::back();
  }

  public function deleteTask($id){
    //var_dump($id);      
      $tarea = Tarea::find($id);
      $tarea->delete();
  }

  public function updateTask($id){
    $path = public_path(). '/respuestas/' . '/' . date("Y")  . '/' . date("m") . '/' . date("d") . '/';
    if (!file_exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
    }
    /*$data = Input::all();
    $task = Tarea::find($id);
    $task->fill($data);
    $task->save();
    $id = Input::get('admin_id');
    $user = User::find($id);
    return Response::json(array(
          'error' =>  $user
        ));*/
    
    try {
      if (Input::hasFile('filePdf'))
      {
          $file = Input::file('filePdf')->move($path, time() . '-' . Input::file('filePdf')->getClientOriginalName() ); 
      }
      $data = Input::all();
      $task = Tarea::find($id);
      $task->fill($data);
      $task->save();
      $id = Input::get('admin_id');
      $user = User::find($id);

      $folio = $task->folio;
      $asunto = $task->asunto;
      $oficio_referencia = $task->oficio_referencia; 
      $fecha_respuesta = $task->fecha_respuesta;
      $area_generadora = $task->area_generadora;   
      $info = array
          (
          'folio'             =>  $folio,
          'asunto'            =>  $asunto,
          'oficio_referencia' =>  $oficio_referencia,
          'fecha_respuesta'   =>  $fecha_respuesta,
          'area_generadora'   =>  $area_generadora
          );

      Mail::send('emails.updateTarea', $info, function($message) use ($user){
        $message->to($user->email, $user->first_name.' '.$user->last_name)->subject('Tarea Actualizada');
        $message->attach( public_path(). '/respuestas/' . '/' . date("Y")  . '/' . date("m") . '/' . date("d") . '/'.time() . '-' . Input::file('filePdf')->getClientOriginalName());
      });
      
      return Redirect::back();
    } catch (Exception $e) {
      //return $e;
      return Response::json(array(
          'error' =>  $e
        ));
    }
      
  }

  public function sendRejectTask(){
    $task = Tarea::find($_POST['id']);
    $folio = $task->folio;
    $asunto = $task->asunto;
    $oficio_referencia = $task->oficio_referencia; 
    $fecha_respuesta = $task->fecha_respuesta;
    $area_generadora = $task->area_generadora;   
    $data = array
        (
        'folio'             =>  $folio,
        'comentarios'       =>  $_POST['comentarios'],
        'asunto'            =>  $asunto,
        'oficio_referencia' =>  $oficio_referencia,
        'fecha_respuesta'   =>  $fecha_respuesta,
        'area_generadora'   =>  $area_generadora
        );
    try {
      if(Request::ajax())
      {
        Mail::send('emails.rejectTarea', $data, function($message)
          {
            $message->to(Auth::user()->email)->subject('Tarea Rechazada');
          });
      }
      return View::make('auth/dash');
    } catch (Exception $e) {
        return Response::json(array(
          'error' =>  $e
        ));
    }
  }

}