<?php

if ($argc <= 1) {
  die("No action indicated. Please use the flag -h or --help to access the help menu\n");
}

/* Actions
add
update
delete
list
list all done
list all todo
list all in-progress
mark done
mark in-progress
mark todo
*/
$options = getopt("h", ["help"]);
$help = isset($options["h"]) || isset($options["help"]);

if ($help) {
  echo "Task Tracker CLI Help Menu\n";
  echo "Usage: php task_tracker_cli.php [action] [parameters]\n\n";
  echo "Actions:\n";
  echo "  add [task id] [task description] - Add a new task\n";
  echo "  update [task id] [new description] - Update an existing task\n";
  echo "  delete [task id] - Delete a task\n";
  echo "  list [done|in-progress|todo] - List tasks based on status\n";
  echo "  mark [task id] [done|in-progress|todo] - Change the status of a task\n";
  exit(0);
}


$file = './tasks.json';

if (file_exists($file)) {
  // Get and decode file contents
  $json_contents = file_get_contents($file);
  $data = json_decode($json_contents, true);
  if (!is_array($data)) {
      $data = [];
  }
} else {
  // Initialize an empty array if the file doesn't exist
  $data = [];
}

$command = $argv[1];

switch ($command) {
  case 'add':
    $id = $argv[2];
    $description = $argv[3];

    // Validate inputs
    if (empty($id) || empty($description)) {
      die("Please provide a valid task ID and description\n");
    }
    if (!is_numeric($id)) {
      die("Task ID must be a numeric value\n");
    }
    if (isset($data[$id])) {
      die("Task with ID {$id} already exists\n");
    }

    // Add new task
    $newTask = array(
        'description' => $description,
        'status' => 'todo',
        'createdAt' => date('Y-m-d H:i:s'),
        'updatedAt' => date('Y-m-d H:i:s')
      );
    $data[$id] = $newTask;
    $json_data = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($file, $json_data);
    echo "Task added successfully: (ID: $id)\n";
    break;


  case 'update':
    $id = $argv[2];
    $newDescription = $argv[3];

    // Validate inputs
    if (empty($newDescription)) {
      die("Please provide a new description\n");
    }
    if (isset($data[$id])) {
      $data[$id]["description"] = $newDescription;
      $data[$id]["updatedAt"] = date('Y-m-d H:i:s');
    } else {
      die("Task with ID {$id} has not been created\n");
    }
    $json_data = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($file, $json_data);
    echo "Task updated successfully: (ID: $id)\n";
    break;

  case 'delete':
    $id = $argv[2];
    if (isset($data[$id])) {
      unset($data[$id]);
    } else {
      die("Task with ID {$id} does not exist\n");
    }
    $json_data = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($file, $json_data);
    echo "Task {$id} deleted\n";
    break;

  case 'list':
    if (empty($data)) {
      die("There are no tasks to display\n");
    } 
    elseif ($argv[2]) {
      $listOptions = ["done", "pending", "todo"];
      if (in_array($argv[2], $listOptions)) {
        echo "---------------- Task List ----------------\n";
        foreach ($data as $id => $task) {
          if ($task["status"] == $argv[2]){
            echo "ID: " . $id . "\n";
            echo "Description: " . $task['description'] . "\n";
            echo "Status: " . $task['status'] . "\n";
            echo "-----------------------\n";
          }
        }
      }
      else {
        die("Invalid list option. Please use 'done', 'in-progress', or 'todo'\n");
      }
    }
    else {
      // print_r($data);
      echo "---------------- Task List ----------------\n";
      foreach ($data as $id => $task) {
        echo "ID: " . $id . "\n";
        echo "Description: " . $task['description'] . "\n";
        echo "Status: " . $task['status'] . "\n";
        echo "-----------------------\n";
      }
    }
    break;

  case 'mark':
    $id = $argv[2];
    $newStatus = $argv[3];
    if ($newStatus != 'done' && $newStatus != 'in-progress' && $newStatus != 'todo') {
      die("Invalid status. Please use 'done', 'in-progress', or 'todo'\n");
    }
    if (isset($data[$id])) {
      $data[$id]["status"] = $newStatus;
      $data[$id]["updatedAt"] = date('Y-m-d H:i:s');
    } else {
      die("Task with ID {$id} does not exist \n");
    }
    $json_data = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($file, $json_data);
    echo "Task with ID {$id} has been marked as {$newStatus} \n";
    break;
  
  default:
    die("No action indicated. Please use the flag -h or --help to access the help menu\n");
    break;
}