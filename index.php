<!doctype html>
<html lang="en">
<head>
    <!-- Meta tags for character set and viewport settings -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Calendar Event Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- CSS styles -->
    <style>
        body {
            background-color: #f0f0f0; /* Light gray background */
            padding-top: 50px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        .header-custom {
            background: linear-gradient(45deg, #4286f4, #4286f4); /* Light blue background */
            border-radius: 10px 10px 0 0;
            padding: 20px;
            text-align: center;
        }
        .header-custom h1 {
            margin: 0;
            font-size: 2.5em;
            font-weight: bold;
            color: white;
        }
        .card-custom {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 10px;
            margin-bottom: 30px;
            background-color: white;
        }
        .btn-custom {
            background: linear-gradient(45deg, #4286f4, #4286f4); /* Light blue background */
            border: none;
            color: white;
            font-weight: bold;
            font-size: 1.25em; /* Increase font size */
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            transition: background 0.3s ease; /* Only transition background color */
            padding: 10px 20px; /* Add padding for better visibility */
        }
        .btn-custom:hover {
            background: linear-gradient(45deg, #357ae8, #357ae8); /* Darker blue on hover */
        }
        .btn-custom:active {
            background: linear-gradient(45deg, #2c5dbb, #2c5dbb); /* Even darker blue on click */
            color: white; /* Ensure font color remains white on click */
        }
        .form-control-custom {
            border-radius: 30px;
            padding: 10px 20px;
        }
        .table-custom {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
        }
        .table-custom th, .table-custom td {
            vertical-align: middle;
        }
        .card-header-custom {
            background: linear-gradient(45deg, #4286f4, #4286f4); /* Light blue background */
            border-radius: 10px 10px 0 0;
            padding: 10px 20px;
            font-weight: bold;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-custom">
            <h1>Calendar Event Management System</h1>
        </div>
        
        <?php 
        // Include configuration file
        include_once 'config.php'; 
        
        // Retrieve post data from session
        $postData = ''; 
        if(!empty($_SESSION['postData'])){ 
            $postData = $_SESSION['postData']; 
            unset($_SESSION['postData']); 
        } 
        
        // Initialize status and status message
        $status = $statusMsg = ''; 
        if(!empty($_SESSION['status_response'])){ 
            $status_response = $_SESSION['status_response']; 
            $status = $status_response['status']; 
            $statusMsg = $status_response['status_msg']; 
            unset($_SESSION['status_response']); 
        } 

        // Handle clear event list form submission
        if (isset($_POST['clear'])) {
            $_SESSION['clear_events'] = true;
        } else {
            $_SESSION['clear_events'] = false;
        }

        // Fetch events from the database if not cleared
        if (!$_SESSION['clear_events']) {
            $result = $db->query("SELECT * FROM events ORDER BY date ASC");
        }
        ?>

        <!-- Status message -->
        <?php if(!empty($statusMsg)){ ?>
            <div class="alert alert-<?php echo $status; ?> mt-3"><?php echo $statusMsg; ?></div>
        <?php } ?>

        <!-- Add New Event form -->
        <div class="card card-custom mt-4">
            <div class="card-header-custom">
                <h3>Add New Event</h3>
            </div>
            <div class="card-body">
                <form method="post" action="addEvent.php" class="form">
                    <div class="mb-3">
                        <label for="title" class="form-label">Event Title</label>
                        <input type="text" class="form-control form-control-custom" id="title" name="title" value="<?php echo !empty($postData['title']) ? $postData['title'] : ''; ?>" required="">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Event Description</label>
                        <textarea name="description" class="form-control form-control-custom" id="description"><?php echo !empty($postData['description']) ? $postData['description'] : ''; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" name="location" class="form-control form-control-custom" id="location" value="<?php echo !empty($postData['location']) ? $postData['location'] : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" name="date" class="form-control form-control-custom" id="date" value="<?php echo !empty($postData['date']) ? $postData['date'] : ''; ?>" required="">
                    </div>
                    <div class="mb-3">
                        <label for="time_from" class="form-label">Start</label>
                        <input type="time" name="time_from" class="form-control form-control-custom" id="time_from" value="<?php echo !empty($postData['time_from']) ? $postData['time_from'] : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="time_to" class="form-label">End</label>
                        <input type="time" name="time_to" class="form-control form-control-custom" id="time_to" value="<?php echo !empty($postData['time_to']) ? $postData['time_to'] : ''; ?>">
                    </div>
                    <button type="submit" class="btn btn-custom w-100" name="submit">Add Event</button>
                </form>
            </div>
        </div>

        <!-- Event List section -->
        <div class="card card-custom mt-4">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <h3>Event List</h3>
                <!-- Clear Event List button -->
                <form method="post" action="" class="form-inline">
                    <button type="submit" class="btn btn-danger" name="clear">Clear Event List</button>
                </form>
            </div>
            <div class="card-body p-0">
                <!-- Event table -->
                <table class="table table-hover table-custom">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Location</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($result) && $result->num_rows > 0){ ?>
                            <?php while($row = $result->fetch_assoc()){ ?>
                                <tr>
                                    <td><?php echo $row['title']; ?></td>
                                    <td><?php echo $row['description']; ?></td>
                                    <td><?php echo $row['location']; ?></td>
                                    <td><?php echo $row['date']; ?></td>
                                    <td><?php echo $row['time_from']; ?></td>
                                    <td><?php echo $row['time_to']; ?></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr><td colspan="6" class="text-center">No events found.</td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
