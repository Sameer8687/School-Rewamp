import TaskModel from '../models/Task.model.js';
import { success, failure } from '../utils/helpers.js';

export async function getTasks(req, res) {
  try {
    const status = req.query.status || 'all';
    const tasks = await TaskModel.getAllTasks(status);
    return success(res, { tasks, count: tasks.length });
  } catch (error) {
    console.error('Error in getTasks:', error);
    return failure(res, error.message || 'Failed to fetch tasks', 500);
  }
}

export async function createTask(req, res) {
  try {
    const title = req.body.title || req.body.task_title || req.body.task_name;
    const start_date = req.body.start_date || req.body.due_date || new Date().toISOString().slice(0, 10);
    const end_date = req.body.end_date || req.body.due_date || start_date;
    const priority = req.body.priority || 'Medium';
    const status = req.body.status || 'Pending';

    if (!title) {
      return failure(res, 'Title is required.', 400);
    }

    const taskId = await TaskModel.createTask({
      ...req.body,
      title,
      start_date,
      end_date,
      priority,
      status,
      created_by: req.user?.id || 1,
    });
    return success(res, { taskId, message: 'Task created successfully.' }, 201);
  } catch (error) {
    console.error('Error in createTask:', error);
    return failure(res, error.message || 'Failed to create task', 500);
  }
}

export async function updateStatus(req, res) {
  try {
    const { id } = req.params;
    const { status } = req.body;
    if (!status) {
      return failure(res, 'Status is required.', 400);
    }
    await TaskModel.updateTaskStatus(id, status);
    return success(res, { message: 'Task status updated.' });
  } catch (error) {
    console.error('Error in updateStatus:', error);
    return failure(res, error.message || 'Failed to update task status', 500);
  }
}

export async function deleteTask(req, res) {
  try {
    const { id } = req.params;
    await TaskModel.deleteTask(id);
    return success(res, { message: 'Task deleted successfully.' });
  } catch (error) {
    console.error('Error in deleteTask:', error);
    return failure(res, error.message || 'Failed to delete task', 500);
  }
}
