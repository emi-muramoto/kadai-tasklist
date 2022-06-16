<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;    // 追加

class TasksController extends Controller
{
    // getでmessages/にアクセスされた場合の「一覧表示処理」
   public function index()
    {
        // メッセージ一覧を取得
        $tasks = Task::paginate(25);

        // メッセージ一覧ビューでそれを表示
        return view('tasks.index', [
            'tasks' => $tasks,
        ]);
    }


    // getでmessages/createにアクセスされた場合の「新規登録画面表示処理」
    public function create()
    {
        $task= new Tasks;

        // メッセージ作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    
    // postでmessages/にアクセスされた場合の「新規登録処理」
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'title' => 'required|max:255',   // 追加
            'content' => 'required|max:255',
        ]);

        // メッセージを作成
        $task = new task;
        $task->title = $request->title;    // 追加
        $task->content = $request->content;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
    }


    // getでmessages/idにアクセスされた場合の「取得表示処理」
    public function show($id)
    {
        // idの値でメッセージを検索して取得
        $task = task::findOrFail($id);

        // メッセージ詳細ビューでそれを表示
        return view('tasks.show', [
            'task' => $task,
        ]);
    }


    // getでmessages/id/editにアクセスされた場合の「更新画面表示処理」
    public function edit($id)
    {
        // idの値でメッセージを検索して取得
        $task = task::findOrFail($id);

        // メッセージ編集ビューでそれを表示
        return view('tasks.edit', [
            'task' => $task,
        ]);
    }
        
  // putまたはpatchでmessages/idにアクセスされた場合の「更新処理」
  public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'title' => 'required|max:255',   // 追加
            'content' => 'required|max:255',
        ]);

        // idの値でメッセージを検索して取得
        $task = task::findOrFail($id);
        // メッセージを更新
        $task->title = $request->title;    // 追加
        $task->content = $request->content;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
    }
    
    
   // deleteでmessages/idにアクセスされた場合の「削除処理」
    public function destroy($id)
    {
        // idの値でメッセージを検索して取得
        $task = task::findOrFail($id);
        // メッセージを削除
        $task->delete();

        // トップページへリダイレクトさせる
        return redirect('/');
    }
}
