<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request){
        $tasks = Task::orderBy('due_date', 'asc')->paginate(5);

        if ($request->ajax()){
            return view('tasks.partials.task_list', compact('tasks'))->render();
        }

        return view('tasks.index', compact('tasks'));
    }

    public function home(){
        return view('tasks.home');
    }

    public function pendingList(Request $request){
        $tasks = Task::orderBy('due_date', 'asc')->where('status', 'pending')->paginate(5);

        if ($request->ajax()){
            return view('tasks.partials.task_list', compact('tasks'))->render();
        }

        return view('tasks.pending', compact('tasks'));
    }

    public function inProgressList(Request $request){ 
        $tasks = Task::orderBy('due_date', 'asc')->where('status', 'in_progress')->paginate(5);

        if ($request->ajax()){
            return view('tasks.partials.task_list', compact('tasks'))->render();
        }

        return view('tasks.in_progress', compact('tasks'));
    }

    public function completedList(Request $request){
        $tasks = Task::orderBy('due_date', 'asc')->where('status', 'completed')->paginate(5);

        if ($request->ajax()){
            return view('tasks.partials.task_list', compact('tasks'))->render();
        }

        return view('tasks.completed', compact('tasks'));
    }

    public function store(TaskRequest $request){
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => $request->status
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tarefa adicionada com sucesso!',
                'task' => $task,
            ]);
        }

        return redirect()->route('tasks.index')->with('success', 'Tarefa criada com sucesso!');
    }

    public function update(TaskRequest $request, $id){
        $task = Task::findOrFail($id);
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => $request->status
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tarefa atualizada com sucesso!',
                'task' => $task
            ]);
        }

        return redirect()->route('tasks.index')->with('success', 'Tarefa atualizada com sucesso!');
    }

    public function destroy($id){
        $task = Task::find($id);
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tarefa excluída com sucesso!'
        ]);
    }

    public function complete($id){
        $task = Task::findOrFail($id);
        $task->status = 'completed';
        $task->save();

        return response()->json([
            'success' => true,
            'message' => 'Tarefa marcada como concluída com sucesso!',
            'task' => $task
        ]);
    }
}