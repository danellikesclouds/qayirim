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
        .announcements {
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="header">
    <img src="https://lh3.googleusercontent.com/rag88C25KUnvW8tbJkXIaKM5xeys3E8e1TP-IhpX-dlg_5TcxnU15DIGxEkkhKCvySfz3icAq3pKAbFF-T8n9Y4=w16383" alt="Logo">
</div>

<div class="gap"></div>

<div class="announcements">
    <h2>Available Announcements</h2>

    <?php
    // Create connection to the database
    $con = mysqli_connect('localhost', 'root', '', 'qayirim');

    // Check connection
    if (!$con) {
        die('Connection failed: ' . mysqli_connect_error());
    }

    // Retrieve announcements from the database
    $sql = "SELECT * FROM announcements";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Display the table of announcements
        echo "<table border='1' width=100%>";
        echo "<tr>\n";
        echo "    <th>AnnID</th>";
        echo "    <th>Ann_tag</th>";
        echo "    <th>Ann_date</th>";
        echo "    <th>Ann_duration</th>";
        echo "    <th>Ann_description</th>";
        echo "</tr>\n";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "    <td>{$row['AnnID']}</td>";
            echo "    <td>{$row['Ann_tag']}</td>";
            echo "    <td>{$row['Ann_date']}</td>";
            echo "    <td>{$row['Ann_duration']}</td>";
            echo "    <td>{$row['Ann_description']}</td>";
            echo "</tr>";
        }

        echo "</table>\n";
		
		mysqli_data_seek($result, 0);
        
        // Display the dropdown select options
        echo '<form method="POST" action="qayirim_voldon.php" style="padding: 20px;">';
        echo '<label for="selected_ann">Select an Announcement ID:</label><br>';
        echo '<select id="selected_ann" name="selected_ann" required>';
        mysqli_data_seek($result, 0);
		while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row['AnnID'] . '">' . $row['AnnID'] . '</option>';
        }
        echo '</select><br><br>';
        echo '<input type="submit" name="submit" value="Submit">';
        echo '</form>';
    } else {
        echo "No announcements found.";
    }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure 'selected_ann' key exists in $_POST array before using it
    $selected_ann_id = isset($_POST['selected_ann']) ? $_POST['selected_ann'] : null;

    if ($selected_ann_id !== null) {
        // Create connection to the database
        $con = mysqli_connect('localhost', 'root', '', 'qayirim');

        // Check connection
        if (!$con) {
            die('Connection failed: ' . mysqli_connect_error());
        }

        // Prepare and execute query to retrieve ann_tag based on AnnID
        $sql = "SELECT ann_tag FROM announcements WHERE AnnID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('i', $selected_ann_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            echo "Announcement Tag: " . $row['ann_tag'];

            if ($row['ann_tag'] == 'Vol') {
				echo '<form method="POST" action="qayirim_voldon.php">';
                echo '    <h2>Volunteer Application Form</h2>';
                echo '    <label for="volname">Your Name:</label>';
                echo '    <input type="text" name="volname" required><br>';
                echo '    <label for="voldate">Date of Volunteering:</label>';
                echo '    <input type="date" name="voldate" required><br>';
                echo '    <label for="suggestion">Your Suggestions:</label><br>';
                echo '    <textarea name="suggestion" required></textarea><br>';
                echo '    <input type="submit" name="submitvol" value="Submit">';
                echo '</form>';

            } elseif ($row['ann_tag'] == 'Fund') {
				echo '<form method="POST" action="qayirim_voldon.php">';
                echo '    <h2>Donation Form</h2>';
                echo '    <label for="donname">Your Name:</label>';
                echo '    <input type="text" name="donname" required><br>';
                echo '    <label for="dondate">Date of Donation:</label>';
                echo '    <input type="date" name="dondate" required><br>';
                echo '    <label for="donamount">Write amount of donation(in tenge):</label><br>';
				echo '    <input type="number" name="donamount" min="100" required><br>';
                echo '    <input type="submit" name="submitdon" value="Submit">';
                echo '</form>';

            }
        }
		else {
            echo "Announcement not found.";
		}

        // Close connection
				
    } else {
        echo "Error: Announcement ID not provided.<br>";
    }
}

				if(isset($_POST['submitvol'])){
                    // Check if expected form fields are set in $_POST array
                    if (isset($_POST['volname'], $_POST['voldate'], $_POST['suggestion'])) {
						$con = mysqli_connect('localhost', 'root', '', 'qayirim');
						if (!$con) {
							die('Connection failed: ' . mysqli_connect_error());
						}
						
                        $volname = $_POST['volname'];
                        $voldate = $_POST['voldate'];
                        $suggestion = $_POST['suggestion'];

                        // Prepare SQL statement to insert data into the volapp table
                        $sql = "INSERT INTO volapp (volname, voldate, suggestion) VALUES (?, ?, ?)";
                        $stmt = $con->prepare($sql);
						if (!$stmt) {
							die('Prepare failed: ' . $con->error);
						}
						
                        $stmt->bind_param('sss', $volname, $voldate, $suggestion);

                        // Execute the prepared statement
                        if ($stmt->execute()) {
                            echo "Volunteer application submitted successfully";
                        } else {
                            echo "Error: " . $stmt->error;
                        }
						$stmt->close();
						mysqli_close($con);

                        
                    }
					else {
                        echo "Error: Required form fields are missing in the submitted data.";
                    }
                }
				elseif(isset($_POST['submitdon'])){
                    // Check if expected form fields are set in $_POST array
                    if (isset($_POST['donname'], $_POST['dondate'], $_POST['donamount'])) {
						$con = mysqli_connect('localhost', 'root', '', 'qayirim');
						if (!$con) {
							die('Connection failed: ' . mysqli_connect_error());
						}
						
                        $donname = $_POST['donname'];
                        $dondate = $_POST['dondate'];
                        $donamount = $_POST['donamount'];

                        // Prepare SQL statement to insert data into the volapp table
                        $sql = "INSERT INTO donations (donator_name, donation_date, donation_amount) VALUES (?, ?, ?)";
                        $stmt = $con->prepare($sql);
						if (!$stmt) {
							die('Prepare failed: ' . $con->error);
						}
						
                        $stmt->bind_param('ssi', $donname, $dondate, $donamount);

                        // Execute the prepared statement
                        if ($stmt->execute()) {
                            echo "Donation made successfully";
                        } else {
                            echo "Error: " . $stmt->error;
                        }
						$stmt->close();
						mysqli_close($con);

                        
                    }
					else {
                        echo "Error: Required form fields are missing in the submitted data.";
                    }
                }
				else{
					echo "Press submit.<br>";
				}


    ?>

</div>

</body>
</html>
