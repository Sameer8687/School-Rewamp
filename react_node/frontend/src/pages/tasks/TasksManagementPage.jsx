import React, { useState, useEffect } from 'react';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import taskService from '../../services/taskService.js';
import {
  FiCheckSquare,
  FiPlus,
  FiCalendar,
  FiTrash2,
  FiCheck,
  FiClock,
  FiRefreshCw,
  FiAlertCircle,
} from 'react-icons/fi';

export default function TasksManagementPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [loading, setLoading] = useState(true);
  const [tasks, setTasks] = useState([]);
  const [statusFilter, setStatusFilter] = useState('all');

  const [isModalOpen, setIsModalOpen] = useState(false);
  const [formData, setFormData] = useState({
    title: '',
    start_date: new Date().toISOString().slice(0, 10),
    reminder_date: new Date(Date.now() + 86400000).toISOString().slice(0, 10),
    status: 'pending',
  });

  const loadTasks = async () => {
    setLoading(true);
    try {
      const res = await taskService.getTasks(statusFilter);
      if (res.data?.success) {
        setTasks(res.data.tasks || []);
      }
    } catch (err) {
      console.error('Failed to load tasks:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadTasks();
  }, [statusFilter]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!formData.title) return alert('Please enter a task title');
    try {
      const res = await taskService.createTask(formData);
      if (res.data?.success) {
        setIsModalOpen(false);
        setFormData({
          title: '',
          start_date: new Date().toISOString().slice(0, 10),
          reminder_date: new Date(Date.now() + 86400000).toISOString().slice(0, 10),
          status: 'pending',
        });
        loadTasks();
      }
    } catch (err) {
      alert(err.response?.data?.message || 'Failed to create task');
    }
  };

  const handleToggleStatus = async (task) => {
    const nextStatus = task.status === 'complete' ? 'pending' : 'complete';
    try {
      await taskService.updateStatus(task.id, nextStatus);
      loadTasks();
    } catch (err) {
      alert('Failed to update task status');
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('Delete this task?')) return;
    try {
      await taskService.deleteTask(id);
      loadTasks();
    } catch (err) {
      alert('Failed to delete task');
    }
  };

  return (
    <div className="flex h-screen bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} onToggle={() => setSidebarOpen(!sidebarOpen)} />
      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav onToggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
        <main className="p-4 md:p-6 lg:p-8 space-y-6">
          {/* Header */}
          <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80">
            <div className="flex items-center gap-4">
              <div className="w-12 h-12 rounded-xl bg-gradient-to-tr from-[#1976d2] to-[#1565c0] flex items-center justify-center text-white shadow-md">
                <FiCheckSquare size={24} />
              </div>
              <div>
                <h1 className="text-2xl font-bold text-slate-900 tracking-tight">
                  Tasks & Operations Manager
                </h1>
                <p className="text-xs text-slate-500">
                  Track administrative follow-ups, academic deadlines, reminders & assignments
                </p>
              </div>
            </div>

            <div className="flex items-center gap-2.5">
              <button
                onClick={() => setIsModalOpen(true)}
                className="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-[#1976d2] hover:bg-[#1565c0] rounded-lg shadow-sm transition-all cursor-pointer"
              >
                <FiPlus size={15} />
                <span>Create Task</span>
              </button>
            </div>
          </div>

          {/* Status Filters */}
          <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between gap-3">
            <div className="flex items-center gap-1.5 bg-slate-100 p-1 rounded-lg">
              {[
                { id: 'all', label: 'All Tasks' },
                { id: 'pending', label: 'Pending' },
                { id: 'complete', label: 'Completed' },
                { id: 'close', label: 'Closed' },
              ].map((sub) => (
                <button
                  key={sub.id}
                  onClick={() => setStatusFilter(sub.id)}
                  className={`px-3 py-1 text-xs rounded-md font-medium transition-colors cursor-pointer ${
                    statusFilter === sub.id
                      ? 'bg-white text-[#1976d2] font-bold shadow-xs'
                      : 'text-slate-600 hover:text-slate-900'
                  }`}
                >
                  {sub.label}
                </button>
              ))}
            </div>

            <div className="text-xs text-slate-500 font-medium">
              {tasks.length} task{tasks.length === 1 ? '' : 's'} found
            </div>
          </div>

          {/* Tasks List */}
          <div className="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            {loading ? (
              <div className="py-20 flex flex-col items-center justify-center text-slate-400">
                <FiRefreshCw className="animate-spin mb-3 text-[#1976d2]" size={28} />
                <p className="text-sm font-medium">Loading tasks...</p>
              </div>
            ) : tasks.length === 0 ? (
              <div className="py-16 text-center text-slate-400 text-xs">
                No tasks found in this view.
              </div>
            ) : (
              <div className="divide-y divide-slate-100">
                {tasks.map((task) => (
                  <div
                    key={task.id}
                    className="p-4 hover:bg-slate-50/80 transition-colors flex items-center justify-between gap-4 text-xs"
                  >
                    <div className="flex items-center gap-3">
                      <button
                        onClick={() => handleToggleStatus(task)}
                        className={`w-6 h-6 rounded-lg flex items-center justify-center border transition-colors cursor-pointer ${
                          task.status === 'complete'
                            ? 'bg-emerald-600 border-emerald-600 text-white'
                            : 'border-slate-300 hover:border-[#1976d2] text-transparent'
                        }`}
                        title="Mark Completed"
                      >
                        <FiCheck size={14} />
                      </button>

                      <div>
                        <div
                          className={`font-semibold text-sm ${
                            task.status === 'complete' ? 'line-through text-slate-400' : 'text-slate-900'
                          }`}
                        >
                          {task.title}
                        </div>
                        <div className="flex items-center gap-3 text-slate-400 text-[11px] mt-0.5">
                          <span className="flex items-center gap-1">
                            <FiCalendar size={11} />
                            Start: {task.start_date ? task.start_date.slice(0, 10) : '-'}
                          </span>
                          <span className="flex items-center gap-1">
                            <FiClock size={11} />
                            Reminder: {task.reminder_date ? task.reminder_date.slice(0, 10) : '-'}
                          </span>
                        </div>
                      </div>
                    </div>

                    <div className="flex items-center gap-2.5">
                      <span
                        className={`px-2 py-0.5 rounded-full text-[10px] font-bold uppercase ${
                          task.status === 'complete'
                            ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                            : task.status === 'close'
                            ? 'bg-slate-100 text-slate-600 border border-slate-200'
                            : 'bg-amber-50 text-amber-700 border border-amber-200'
                        }`}
                      >
                        {task.status}
                      </span>
                      <button
                        onClick={() => handleDelete(task.id)}
                        className="p-1.5 text-slate-400 hover:text-rose-600 rounded-md transition-colors cursor-pointer"
                        title="Delete Task"
                      >
                        <FiTrash2 size={14} />
                      </button>
                    </div>
                  </div>
                ))}
              </div>
            )}
          </div>
        </main>
      </div>

      {/* Create Task Modal */}
      {isModalOpen && (
        <div className="fixed inset-0 bg-black/50 z-[100] flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div className="flex items-center justify-between border-b border-slate-200 pb-3">
              <h3 className="font-bold text-slate-900 text-base">Create New Task</h3>
              <button
                onClick={() => setIsModalOpen(false)}
                className="text-slate-400 hover:text-slate-700 text-lg cursor-pointer"
              >
                ✕
              </button>
            </div>

            <form onSubmit={handleSubmit} className="space-y-3 text-xs">
              <div>
                <label className="block font-bold text-slate-700 mb-1">
                  Task Title <span className="text-rose-500">*</span>:
                </label>
                <input
                  type="text"
                  required
                  value={formData.title}
                  onChange={(e) => setFormData({ ...formData, title: e.target.value })}
                  placeholder="e.g. Audit Term 1 Fees Register"
                  className="w-full p-2 border border-slate-200 rounded-lg"
                />
              </div>

              <div className="grid grid-cols-2 gap-2.5">
                <div>
                  <label className="block font-bold text-slate-700 mb-1">Start Date:</label>
                  <input
                    type="date"
                    required
                    value={formData.start_date}
                    onChange={(e) => setFormData({ ...formData, start_date: e.target.value })}
                    className="w-full p-2 border border-slate-200 rounded-lg"
                  />
                </div>
                <div>
                  <label className="block font-bold text-slate-700 mb-1">Reminder Date:</label>
                  <input
                    type="date"
                    required
                    value={formData.reminder_date}
                    onChange={(e) => setFormData({ ...formData, reminder_date: e.target.value })}
                    className="w-full p-2 border border-slate-200 rounded-lg"
                  />
                </div>
              </div>

              <div className="flex items-center justify-end gap-2 pt-3 border-t border-slate-200">
                <button
                  type="button"
                  onClick={() => setIsModalOpen(false)}
                  className="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg cursor-pointer"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  className="px-4 py-2 bg-[#1976d2] hover:bg-[#1565c0] text-white font-semibold rounded-lg cursor-pointer"
                >
                  Create Task
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
