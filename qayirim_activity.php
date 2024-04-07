<!DOCTYPE html>
<html lang="en">
<head>
<title>Dashboard</title>
<style>
body {
    background-color: lightyellow;
}
.header {
    background-color: orange;
    padding: 5px;
    width: 100%;
    height: 130px;
}
#VolDon {
    background-color: lightyellow;
    padding: 5px;
    width: 100%;
}
#CharOrg {
    background-color: lightyellow;
    padding: 5px;
    width: 100%;
}
.gap {
    background-color: lightyellow;
    width: 100%;
    height: 100px;
}
</style>
</head>
<body>

<div class="header">
    <img src="qayirim.png" alt="Logo">
</div>

<div class="gap"></div>

<form method="POST" action="qayirim_activity.php">
	<h3>Load an announcement</h3>
    <label for="ann_tag">Announcement Tag:</label><br>
    <input type="radio" id="tag_fund" name="ann_tag" value="Fund">
    <label for="tag_fund">Fundraising</label><br>
    <input type="radio" id="tag_vol" name="ann_tag" value="Vol">
    <label for="tag_vol">Volunteering</label><br><br>

    <label for="ann_date">Date of Announcement:</label>
    <input type="date" id="ann_date" name="ann_date" required><br><br>

    <label for="ann_duration">Duration (in days):</label>
    <input type="number" id="ann_duration" name="ann_duration" min="1" required><br><br>

    <label for="ann_description">Announcement Description:</label><br>
    <textarea id="ann_description" name="ann_description" rows="4" cols="50" required></textarea><br><br>

    <input type="submit" name="submit" value="Submit">
</form>



<?php
// Create connection
$con = mysqli_connect('localhost', 'root', '', 'qayirim');

// Check connection
if (!$con) {
    die('Connection failed: ' . mysqli_connect_error());
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $ann_tag = $_POST['ann_tag'];
    $ann_date = $_POST['ann_date'];
    $ann_duration = $_POST['ann_duration'];
    $ann_description = $_POST['ann_description'];

    // Prepare INSERT query
    $sql = "INSERT INTO announcements (ann_tag, ann_date, ann_duration, ann_description) 
            VALUES ('$ann_tag', '$ann_date', $ann_duration, '$ann_description')";
	
    // Execute query
    if (mysqli_query($con, $sql)) {
        echo "Announcement added successfully";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

// Close connection
mysqli_close($con);
?>

</body>
</html>
