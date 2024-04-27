<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Member;

class MemberController extends Controller
{
    public function index() {
        $member = Member::select('id', 'nama_member', 'email', 'phone', 'alamat')->get();

        if (request()->expectsJson()) {
            return response()->json($member);
        }

        return redirect('/management/member')->with('member', $member);
    }

    public function create() {
        return view('management.member.create');
    }

    public function store(Request $request) {
        $request->validate([
            'nama_member' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'alamat' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $member = new Member;
            $member->nama_member = $request->nama_member;
            $member->email = $request->email;
            $member->phone = $request->phone;
            $member->alamat = $request->alamat;
            $member->save();

            DB::commit();

            session()->flash('success', 'Tambah Member Sukses');
        }
        catch (\Throwable $th) {
            DB::rollBack();

            session()->flash('error', 'Tambah Member Gagal');
        }


        if (request()->expectsJson()) {
            return response()->json($member);
        }
        return redirect('/management/member');
    }

    public function delete(Request $request) {
        $id = $request->input('id');
        $member = Member::findOrFail($id);
        $member->delete();

        return response()->json(['message' => 'Member Berhasil Dihapus']);
    }

    public function edit(Request $request) {
        $id = $request->id;
        $member = Member::select('id', 'nama_member', 'email', 'phone', 'alamat')->get();
        $member = Member::findOrFail($id);

        if (request()->expectsJson()) {
            return response()->json($member);
        }

        return view('management.member.edit')->with('member', $member);
    }

    public function update(Request $request, $id) {

            $request->validate([
                'nama_member' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'alamat' => 'required',
            ]);

            DB::beginTransaction();

            try {
                $member = Member::findOrFail($id);
                $member->nama_member = $request->nama_member;
                $member->email = $request->email;
                $member->phone = $request->phone;
                $member->alamat = $request->alamat;
                $member->save();

                DB::commit();

                session()->flash('success', 'Update Member Sukses');
            }
            catch (\Throwable $th) {
                DB::rollBack();

                session()->flash('error', 'Update Member Gagal');
            }


            if (request()->expectsJson()) {
                return response()->json($member);
            }

            session()->flash('success', 'Update Member Sukses');

            return redirect('/management/member');
    }
}
