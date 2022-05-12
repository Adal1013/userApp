<?php

namespace App\Http\Controllers;

use \App\Http\Request;
use \App\Models\User;

class UserController
{
    public function index(Request $request)
    {
        $model = new User();
        $users = $model->get();
        return json(['users' => $users]);
    }

    public function show(Request $request, $params)
    {
        $model = new User();
        $user = $model->where("id", "=", $params['id'])->first();
        return json(['user' => $user]);
    }

    public function store(Request $request)
    {
        $model = new User();
        $id = $model->insert($request->all());
        $message = $id > 0 ? 'Registro creado exitosamente' : 'No se pudo crear el registro';
        return json(['message' => $message]);
    }

    public function update(Request $request, $params)
    {
        $model = new User();
        $userUpdated = $model->where("id", "=", $params["id"])->update($request->all());
        $message = $userUpdated > 0 ? 'Registro actualizado exitosamente' : 'No se pudo actualizar el registro';
        return json(['message' => $message]);
    }

    public function destroy(Request $request, $params)
    {
        $model = new User();
        $destroyUser = $model->where("id", "=", $params["id"])->delete();
        $message = $destroyUser > 0 ? 'Registro eliminado exitosamente' : 'No se pudo eliminar el registro';
        return json(['message' => $message]);
    }
}
