# Task Tracker CLI
A simple command-line interface (CLI) application to manage tasks using PHP. This application allows you to add, list, update, and delete tasks stored in a JSON file.

## Features
- Add new tasks
- Update existing tasks
- Delete tasks
- List tasks by status (todo, in-progress, done)

## Usage
```bash
php task_tracker_cli.php [action] [parameters]
```

## Actions
- `add [task id] [task description]`
- `update [task id] [new description]`
- `delete [task id]`
- `list *[done|in-progress|todo]`
- `mark [task id] [done|in-progress|todo]`

- `-h or --help ` - Display help information

\* - Optional parameter

This project was built for learning purposes, following the instructions on [Task Tracker CLI](https://roadmap.sh/projects/task-tracker) on [roadmap.sh](https://roadmap.sh).