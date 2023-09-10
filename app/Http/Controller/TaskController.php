<?php

namespace App\Http\Controller;

use App\ContohBootcamp\Services\TaskService;
use App\Helpers\MongoModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller {
	private TaskService $taskService;
	public function __construct() {
		$this->taskService = new TaskService();
	}

	public function showTasks()
	{
		$tasks = $this->taskService->getTasks();
		return response()->json($tasks);
	}

	public function createTask(Request $request)
	{
		$request->validate([
			'title'=>'required|string|min:3',
			'description'=>'required|string'
		]);

		$data = [
			'title'=>$request->post('title'),
			'description'=>$request->post('description')
		];

		$dataSaved = [
			'title'=>$data['title'],
			'description'=>$data['description'],
			'assigned'=>null,
			'subtasks'=> [],
			'created_at'=>time()
		];

		$id = $this->taskService->addTask($dataSaved);
		$task = $this->taskService->getById($id);

		return response()->json($task);
	}


	public function updateTask(Request $request)
	{
		$request->validate([
			'task_id'=>'required|string',
			'title'=>'string',
			'description'=>'string',
			'assigned'=>'string',
			'subtasks'=>'array',
		]);

		$taskId = $request->post('task_id');
		$formData = $request->only('title', 'description', 'assigned', 'subtasks');
		$task = $this->taskService->getById($taskId);

		$this->taskService->updateTask($task, $formData);

		$task = $this->taskService->getById($taskId);

		return response()->json($task);
	}


	// TODO: deleteTask()
	public function deleteTask(Request $request)
	{
		$request->validate([
			'task_id'=>'required'
		]);

		$taskId = $request->task_id;

		$existTask = $this->taskService->deleteById($taskId);
		
		if ($existTask == true) {
			return response()->json([
				"message"=> "Task with ID ".$taskId." has been deleted."
			]);
		} else {
			return response()->json([
				"message"=> "There is no task with ID ".$taskId."."
			], 401);
		}
	}

	// TODO: assignTask()
	public function assignTask(Request $request)
	{
		$request->validate([
			'task_id'=>'required',
			'assigned'=>'required'
		]);

		$taskId = $request->get('task_id');
		$assigned = $request->only('assigned');

		$existTask = $this->taskService->getById($taskId);

		if(!$existTask)
		{
			return response()->json([
				"message"=> "There is no task with ID ".$taskId."."
			], 401);
		} else {
			$this->taskService->assignTask($existTask, $assigned);
			$id = $this->taskService->getById($taskId);
			return response()->json($id);
		}
	}

	// TODO: unassignTask()
	public function unassignTask(Request $request)
	{
		$request->validate([
			'task_id'=>'required'
		]);

		$taskId = $request->post('task_id');

		$existTask = $this->taskService->getById($taskId);

		if(!$existTask)
		{
			return response()->json([
				"message"=> "Task ".$taskId." tidak ada"
			], 401);
		} else {
			$this->taskService->unassignTask($existTask);
			$id = $this->taskService->getById($taskId);
			return response()->json($id);
		}
	}

	// TODO: createSubtask()
	public function createSubtask(Request $request)
	{
		$request->validate([
			'task_id'=>'required',
			'title'=>'required|string',
			'description'=>'required|string'
		]);

		$taskId = $request->post('task_id');
		$title = $request->post('title');
		$description = $request->post('description');

		$existTask = $this->taskService->getById($taskId);

		if(!$existTask)
		{
			return response()->json([
				"message"=> "Task ".$taskId." tidak ada"
			], 401);
		} else {
			$this->taskService->addSubtask($taskId, $title, $description);
			$task = $this->taskService->getById($taskId);
			return response()->json($task);
		}
	}

	// TODO deleteSubTask()
	public function deleteSubtask(Request $request)
	{
		$request->validate([
			'task_id'=>'required',
			'subtask_id'=>'required'
		]);

		$taskId = $request->post('task_id');
		$subtaskId = $request->post('subtask_id');

		$existTask = $this->taskService->getById($taskId);

		if(!$existTask)
		{
			return response()->json([
				"message"=> "Task ".$taskId." tidak ada"
			], 401);
		} else {
			$this->taskService->deleteSubtask($taskId, $subtaskId);
			$task = $this->taskService->getById($taskId);
			return response()->json($task);
		}
	}
}