<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>To-Do List</title>
    
    <link rel="icon" type="image/png" href="<?= base_url('favicon.ico') ?>">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLMD5TzLz/tVb7f03K/e1O4uBvYQ/J5D8vA2G/B2vJ6V0R2kU1R3h/R7A8m2H7F7A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
        /* --- Root and Base Styles (Dark Theme) --- */
        :root {
            --bg-dark: #1f2937;       /* Gray-800 - Main Background */
            --card-dark: #374151;     /* Gray-700 - Card Background */
            --border-dark: #4b5563;   /* Gray-600 - Border/Separator */
            --accent-color: #10b981;  /* Emerald-500 - Primary/Success */
            --accent-light: #6ee7b7;  /* Emerald-300 */
            --text-light: #f9fafb;    /* Gray-50 - Main Text */
            --text-muted: #9ca3af;    /* Gray-400 - Muted Text */
            --delete-color: #f87171;  /* Red-400 */
            --shadow-dark: 0 10px 25px rgba(0, 0, 0, 0.4);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-light);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 70px 20px;
        }

        /* --- Main Card Container --- */
        .app-container {
            background-color: var(--card-dark);
            width: 100%;
            max-width: 550px;
            padding: 40px;
            border-radius: 12px;
            box-shadow: var(--shadow-dark);
            border: 1px solid var(--border-dark);
        }

        h1 {
            text-align: center;
            font-size: 34px;
            font-weight: 700;
            color: var(--accent-color);
            margin-bottom: 30px;
            letter-spacing: -0.5px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--border-dark);
        }

        /* --- Task Input Form --- */
        .task-form {
            display: flex;
            margin-bottom: 35px;
            border-radius: 8px;
            overflow: hidden;
        }

        .task-input {
            flex-grow: 1;
            padding: 15px;
            border: 1px solid var(--border-dark);
            background-color: var(--bg-dark);
            color: var(--text-light);
            font-size: 16px;
            transition: border-color 0.3s;
        }
        .task-input::placeholder {
            color: var(--text-muted);
        }
        .task-input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 1px var(--accent-color);
        }

        .add-button {
            padding: 15px 22px;
            border: none;
            background-color: var(--accent-color);
            color: var(--bg-dark);
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .add-button:hover {
            background-color: var(--accent-light);
        }
        
        /* --- Task List --- */
        .task-list {
            list-style: none;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .task-item {
            background-color: var(--border-dark);
            padding: 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            font-size: 16px;
            transition: all 0.3s ease-in-out;
            border-left: 4px solid var(--text-muted);
        }
        .task-item:hover {
            background-color: #4b5563; /* Slightly lighter on hover */
        }

        .completed {
            background-color: #374151; /* Card dark */
            border-left-color: var(--accent-color);
        }
        
        /* --- Task Content and Checkbox --- */
        .task-content {
            flex-grow: 1;
            margin-left: 15px;
            font-weight: 500;
            color: var(--text-light);
        }
        .completed .task-content {
            text-decoration: line-through;
            color: var(--text-muted);
            font-weight: 400;
        }

        .task-checkbox {
            cursor: pointer;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--text-muted);
            border-radius: 4px;
            color: transparent; /* Hide check icon by default */
            transition: all 0.2s;
        }
        .completed .task-checkbox {
            border-color: var(--accent-color);
            background-color: var(--accent-color);
            color: var(--bg-dark);
        }
        .task-checkbox:hover {
            opacity: 0.7;
        }

        /* --- Action Buttons (Delete) --- */
        .task-actions {
            flex-shrink: 0;
            margin-left: 15px;
        }

        .delete-btn {
            color: var(--delete-color);
            padding: 8px;
            border-radius: 50%;
            transition: background-color 0.2s, color 0.2s;
            font-size: 18px;
        }
        .delete-btn:hover {
            background-color: var(--delete-color);
            color: var(--bg-dark);
        }
        
        .empty-state {
            text-align: center;
            padding: 30px;
            color: var(--text-muted); 
            font-style: italic;
            border: 1px dashed var(--border-dark);
            border-radius: 8px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="app-container">
        <h1><i class="fas fa-moon"></i> Task List</h1>

        <form action="<?= url_to('TaskController::create') ?>" method="post" class="task-form">
            <input type="text" name="title" placeholder="Add a new task..." required class="task-input">
            <button type="submit" class="add-button">
                <i class="fas fa-plus"></i> ADD
            </button>
        </form>

        <ul class="task-list">
            <?php if (empty($tasks)): ?>
                <li class="empty-state">
                    <i class="fas fa-inbox"></i> Nothing to do yet. Enjoy the darkness!
                </li>
            <?php else: ?>
                <?php foreach ($tasks as $task): ?>
                    <li class="task-item <?= $task['is_completed'] ? 'completed' : '' ?>">
                        
                        <a href="<?= url_to('TaskController::toggle', $task['id']) ?>"
                           class="task-checkbox"
                           title="<?= $task['is_completed'] ? 'Mark as Pending' : 'Mark as Complete' ?>">
                           <i class="fas fa-check"></i>
                        </a>
                        
                        <span class="task-content"><?= esc($task['title']) ?></span>
                        
                        <div class="task-actions">
                            <a href="<?= url_to('TaskController::delete', $task['id']) ?>" 
                               class="delete-btn delete-task-link"
                               title="Delete Task"> 
                                <i class="fas fa-times-circle"></i>
                            </a>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Confirmation for the delete action
            const deleteLinks = document.querySelectorAll('.delete-task-link');

            deleteLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    if (!confirm('Are you sure you want to permanently delete this task?')) {
                        event.preventDefault();
                    }
                });
            });
        });
    </script>

</body>
</html>