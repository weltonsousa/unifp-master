<?php

namespace App\Http\Controllers;

use App\Message;
use App\Unidade;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::all();
        return view('users', ['users' => $users]);
    }

    public function edit()
    {
        return view('user-form-edit', ['user' => User::whereId(request('id'))->firstOrFail()]);
    }

    public function add()
    {
        $unidades = Unidade::all();
        return view('user-add', ['unidades' => $unidades]);
    }
    public function store()
    {
        if (request('id') == 0) {
            $this->validate(request(), [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required|confirmed',
            ]);
            DB::beginTransaction();
            $user = User::create([
                "name" => request('name'),
                "email" => request('email'),
                "unidade_id" => request('unidade'),
                "tipo_unidade" => request('tipo-unidade'),
                "password" => bcrypt(request('password')),
            ]);
            DB::commit();
        } else {

            $this->validate(request(), [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required|confirmed',
            ]);
            DB::beginTransaction();
            $user = User::whereId(request('id'))->firstOrFail();
            if ($user) {
                $user->name = request('name');
                $user->email = request('email');
                $user->unidade_id = request('unidade');
                $user->tipo_unidade = request('tipo-unidade');
                $user->password = bcrypt(request('password'));
                $user->save();
            }
            DB::commit();
        }
        return $this->index();
        //auth()->login($user);

    }

    public function delete()
    {
        $message = new Message();
        $message->message = "";
        $message->type = "";
        try {
            DB::beginTransaction();

            User::whereId(request("id"))->delete();

            DB::commit();
            $message->message = 'usuário excluído com sucesso';
            $message->type = "success";
        } catch (\Exception $e) {
            DB::rollback();
            $message->message = $e->getMessage();
            $message->type = "error";
        }
        return $this->index()->with(['message' => $message, 'users' => User::all()]);
    }
}
