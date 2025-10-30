<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\TaskModel;

class TaskController extends Controller
{
    /**
     * @var TaskModel
     * Holds an instance of the TaskModel for easy access throughout the controller.
     */
    protected $taskModel;

    /**
     * Constructor for the controller.
     * Initializes the TaskModel.
     */
    public function __construct()
    {
        // This is necessary to properly initialize CodeIgniter's Controller features
        // such as the $this->request property.
        // parent::__construct(); 
        
        $this->taskModel = new TaskModel();
    }

    // 1. Display the list of tasks (the homepage method)
    public function index()
    {
        $this->SendTestEmail();
    //     // Fetch all tasks, ordered by newest first
    //     $data = [
    //         'tasks' => $this->taskModel->orderBy('created_at', 'DESC')->findAll(),
    //         'title' => 'My Task List', // Keep the title data for the view
    //     ];

    //     return view('task_list', $data);
     }

    // 2. Handle adding a new task (POST request)
    public function create()
    {
        // Get the title from the POST request
        $title = $this->request->getPost('title');

        if ($title) {
            $this->taskModel->insert([
                'title' => $title,
                // is_completed defaults to 0 in the database
            ]);
        }

        // Redirect back to the main list
        return redirect()->to(base_url('/'));
    }

    // 3. Handle deleting a task
    public function delete($id = null)
    {
        // Use validation here in a real app, but for a simple fix, this is fine
        $this->taskModel->delete($id);

        // Redirect back
        return redirect()->to(base_url('/'));
    }

    // 4. Handle toggling the task status
    public function toggle($id = null)
    {
        // Find the task first
        $task = $this->taskModel->find($id);

        if ($task) {
            // Determine the new status (1 becomes 0, 0 becomes 1)
            $newStatus = $task['is_completed'] == 0 ? 1 : 1 - $task['is_completed'];

            // Update the status
            $this->taskModel->update($id, ['is_completed' => $newStatus]);
        }

        // Redirect back
        return redirect()->to(base_url('/'));
    }
private function SendTestEmail()
{
    $email = \Config\Services::email();

    $email->setTo('fikirshawul@gmail.com');
    $email->setSubject('Test Email from TODO App');
    $email->setMessage('This is a test email verifying your Gmail SMTP configuration.');

    if ($email->send()) {
        echo "✅ Email sent successfully!";
    } else {
        echo "❌ Failed to send email.<br>";
        echo $email->printDebugger(['headers']);
    }
}
    
}