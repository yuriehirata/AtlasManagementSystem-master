<?php

namespace App\Http\Controllers\Authenticated\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Gate;
use App\Models\Users\User;
use App\Models\Users\Subjects;
use App\Searchs\DisplayUsers;
use App\Searchs\SearchResultFactories;


class UsersController extends Controller
{
public function register(Request $request)
{
    $validatedData = $request->validate([
        'over_name' => 'required|string|max:10',
        'under_name' => 'required|string|max:10',
        'over_name_kana' => 'required|string|max:30|regex:/^[ァ-ヶー]+$/u',
        'under_name_kana' => 'required|string|max:30|regex:/^[ァ-ヶー]+$/u',
        'mail_address' => 'required|email|max:100|unique:users',
        'sex' => 'required|in:男性,女性,その他',
        'old_year' => 'required|date|before_or_equal:today',
        'role' => 'required|in:講師(国語),講師(数学),教師(英語),生徒',
        'password' => 'required|string|min:8|max:30|confirmed',
    ]);

    // バリデーションに失敗した場合は、リダイレクトしてエラーメッセージを表示
    return redirect()->back()->withErrors($validatedData);

    $user = User::create([
        'over_name' => $validatedData['over_name'],
        'under_name' => $validatedData['under_name'],
        'over_name_kana' => $validatedData['over_name_kana'],
        'under_name_kana' => $validatedData['under_name_kana'],
        'mail_address' => $validatedData['mail_address'],
        'sex' => $validatedData['sex'],
        'old_year' => $validatedData['old_year'],
        'role' => $validatedData['role'],
        'password' => bcrypt($validatedData['password']),
    ]);

    return redirect()->route('user.profile', ['id' => $user->id]);
}

    public function showUsers(Request $request){
        $keyword = $request->keyword;
        $category = $request->category;
        $updown = $request->updown;
        $gender = $request->sex;
        $role = $request->role;
        $subjects = null;// ここで検索時の科目を受け取る
        $userFactory = new SearchResultFactories();
        $users = $userFactory->initializeUsers($keyword, $category, $updown, $gender, $role, $subjects);
        $subjects = Subjects::all();
        return view('authenticated.users.search', compact('users', 'subjects'));
    }

    public function userProfile($id){
        $user = User::with('subjects')->findOrFail($id);
        $subject_lists = Subjects::all();
        return view('authenticated.users.profile', compact('user', 'subject_lists'));
    }

    public function userEdit(Request $request){
        $user = User::findOrFail($request->user_id);
        $user->subjects()->sync($request->subjects);
        return redirect()->route('user.profile', ['id' => $request->user_id]);
    }
}
