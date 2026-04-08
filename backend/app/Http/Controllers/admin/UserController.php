<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ClassModel;
use App\Models\MasterStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $adminSearch   = $request->input('admin_search');
        $studentSearch = $request->input('student_search');
        $nisSearch     = $request->input('nis_search');

        $admins = User::where('Role', 'admin')
            ->when($adminSearch, fn($q) => $q->where(function ($q) use ($adminSearch) {
                $q->where('FullName', 'like', "%{$adminSearch}%")
                  ->orWhere('email', 'like', "%{$adminSearch}%");
            }))
            ->paginate(5, ['*'], 'admin_page');

        $students = User::where('Role', 'student')
            ->with('class')
            ->when($studentSearch, fn($q) => $q->where(function ($q) use ($studentSearch) {
                $q->where('FullName', 'like', "%{$studentSearch}%")
                  ->orWhere('email', 'like', "%{$studentSearch}%");
            }))
            ->paginate(5, ['*'], 'student_page');

        $masterStudents = MasterStudent::query()
            ->when($nisSearch, fn($q) => $q->where(function ($q) use ($nisSearch) {
                $q->where('FullName', 'like', "%{$nisSearch}%")
                  ->orWhere('NIS', 'like', "%{$nisSearch}%");
            }))
            ->orderByDesc('id')
            ->paginate(5, ['*'], 'nis_page');

        return view('admin.user.index', compact('admins', 'students', 'masterStudents'));
    }

    public function destroyAdmin(User $user)
    {
        if ((int) $user->getKey() === (int) Auth::id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }
        $user->delete();
        return back()->with('success', 'Pengelola berhasil dihapus.');
    }

    public function destroyStudent(User $user)
    {
        $user->detailClasses()->update(['StudentId' => null]);
        $user->delete();
        return back()->with('success', 'Siswa berhasil dihapus.');
    }

    public function createAdmin()
    {
        return view('admin.user.create-admin');
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'FullName' => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'NISNUPTK' => 'required|integer',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'FullName'  => $request->FullName,
            'email'     => $request->email,
            'NISNUPTK'  => $request->NISNUPTK,
            'password'  => Hash::make($request->password),
            'Role'      => 'admin',
        ]);

        return redirect()->route('admin.user.index')
            ->with('success', 'Pengelola berhasil ditambahkan.');
    }

    public function createStudent()
    {
        $classes = ClassModel::all();
        return view('admin.user.create-student', compact('classes'));
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'FullName' => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'NISNUPTK' => 'required|integer',
            'ClassId'  => 'nullable|exists:classes,id',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'FullName'  => $request->FullName,
            'email'     => $request->email,
            'NISNUPTK'  => $request->NISNUPTK,
            'ClassId'   => $request->ClassId,
            'password'  => Hash::make($request->password),
            'Role'      => 'student',
        ]);

        return redirect()->route('admin.user.index')
            ->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function createNis()
    {
        return view('admin.user.create-nis');
    }

    public function storeNis(Request $request)
    {
        $request->validate([
            'FullName' => 'required|string|max:255',
            'NIS'      => 'required|integer|unique:master_students,NIS',
        ]);

        MasterStudent::create([
            'FullName' => $request->FullName,
            'NIS'      => $request->NIS,
        ]);

        return redirect()->route('admin.user.index')
            ->with('success', 'Data NIS berhasil ditambahkan.');
    }

    public function destroyNis(MasterStudent $masterStudent)
    {
        $isUsedByUser = User::query()
            ->where('NISNUPTK', $masterStudent->NIS)
            ->exists();

        if ($isUsedByUser) {
            return back()->with('error', 'Data NIS tidak bisa dihapus karena sudah digunakan akun siswa.');
        }

        $masterStudent->delete();

        return back()->with('success', 'Data NIS berhasil dihapus.');
    }
}
