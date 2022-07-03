<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;

class TasklistsController extends Controller
{
    public function index()
    {
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
        
            // 認証済みユーザを取得
            $user = \Auth::user();
            // ユーザの投稿の一覧を作成日時の降順で取得
            // （後のChapterで他ユーザの投稿も取得するように変更しますが、現時点ではこのユーザの投稿のみ取得します）
            $tasks = $user->tasklists()->orderBy('created_at', 'desc')->paginate(10);

            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }
        
         // メッセージ一覧ビューでそれを表示
        return view('tasklists.index', [
            'tasks' => $tasks,
        ]);
    

        // Welcomeビューでそれらを表示
        return view('welcome', $data);
    }
    
    
    
    // getでmessages/createにアクセスされた場合の「新規登録画面表示処理」
    public function create()
    {
        $task = new Task;

        // メッセージ作成ビューを表示
        return view('tasklists.create', [
            'task' => $task,
        ]);
    }
    
     // postでmessages/にアクセスされた場合の「新規登録処理」
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',   // 追加
            'content' => 'required|max:255',
        ]);

        // メッセージを作成
        $task = new Task;
        $task->status = $request->status;    // 追加
        $task->content = $request->content;
        $task->user_id = \Auth::user()->id;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
        
    }


    // getでmessages/idにアクセスされた場合の「取得表示処理」
    public function show($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
            // メッセージ詳細ビューでそれを表示
            return view('tasklists.show', [
                'task' => $task,
            ]);
        }
        
        // トップページへリダイレクトさせる
        return redirect('/');
    }
    
     // getでmessages/id/editにアクセスされた場合の「更新画面表示処理」
    public function edit($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);

        if (\Auth::id() === $task->user_id) {
            // メッセージ編集ビューでそれを表示
            return view('tasklists.edit', [
                'task' => $task,
            ]);
        }
        
        // トップページへリダイレクトさせる
        return redirect('/');
    }
    
    // putまたはpatchでmessages/idにアクセスされた場合の「更新処理」
    public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',   // 追加
            'content' => 'required|max:255',
        ]);

        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);

        if (\Auth::id() === $task->user_id) {
            // メッセージを更新
            $task->status = $request->status;    // 追加
            $task->content = $request->content;
            $task->save();
        }

        // トップページへリダイレクトさせる
        return redirect('/');
    }
    
        
    public function destroy($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);


        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }

        // トップページへリダイレクトさせる
        return redirect('/');
    }
}