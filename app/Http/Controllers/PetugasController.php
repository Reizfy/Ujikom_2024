<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Petugas;
use App\Models\User;

class PetugasController extends Controller
{
    public function index()
    {
        $petugas = Petugas::select('id', 'nama_petugas', 'email', 'phone', 'password')->get();

        if (request()->expectsJson()) {
            return response()->json($petugas);
        }

        return redirect('/management/petugas')->with('petugas', $petugas);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_petugas' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'password' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $petugas = new Petugas;
            $petugas->nama_petugas = $request->nama_petugas;
            $petugas->email = $request->email;
            $petugas->phone = $request->phone;
            $petugas->password = $request->password;
            $petugas->save();

            $user = new User;
            $user->name = $request->nama_petugas;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->role = "Petugas";
            $user->save();

            DB::commit();

            session()->flash('success', 'Tambah Petugas Sukses');

        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error','Tambah Petugas Gagal');
        }

        if (request()->expectsJson()) {
            return response()->json($petugas);
        }
        return redirect('/management/petugas');
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        $petugas = Petugas::findOrFail($id);
        $user = User::where('email', $petugas->email)->first();
        $petugas->delete();
        $user->delete();

        return response()->json(['message' => 'Petugas berhasil dihapus']);
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $petugas = Petugas::select('id', 'nama_petugas', 'email', 'phone', 'password')->get();
        $petugas = Petugas::findOrFail($id);

        if (request()->expectsJson()) {
            return response()->json($petugas);
        }

        return view('management.petugas.edit')->with('petugas', $petugas);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_petugas' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'password' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $petugas = Petugas::findOrFail($id);
            $petugas->nama_petugas = $request->nama_petugas;
            $petugas->email = $request->email;
            $petugas->phone = $request->phone;
            $petugas->password = $request->password;
            $petugas->save();

            $user = User::where('email', $request->email)->first();
            $user->name = $request->nama_petugas;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->role = "Petugas";
            $user->save();

            DB::commit();

            session()->flash('success', 'Update Petugas Sukses');
        }
        catch (\Throwable $th) {
            DB::rollBack();

            session()->flash('error', 'Update Petugas Gagal');
        }

        if (request()->expectsJson()) {
            return response()->json($petugas);
        }

        return redirect('/management/petugas');
    }
}
