<!DOCTYPE html>
<html lang="en">
<head>
<title>Welcome</title>
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
    <img src="https://lh3.googleusercontent.com/rag88C25KUnvW8tbJkXIaKM5xeys3E8e1TP-IhpX-dlg_5TcxnU15DIGxEkkhKCvySfz3icAq3pKAbFF-T8n9Y4=w16383" alt="Logo">
</div>

<div class="gap"></div>

<form action="qayirim_welcome.php" method="POST"> <!-- Add form tag here -->
    <label for="role">Choose your role:</label>
    <select name="role">
        <option value="VolDon">Volunteer/Donator</option>
        <option value="CharOrg">Charity organization</option>
    </select>
    <input type="submit" name="submitRole" value="Submit"> <!-- Submit button for the form -->
</form>

<?php
if (isset($_POST['role'])) {
    switch ($_POST['role']) {
        case 'VolDon':
            echo '<div id="VolDon" style="display: block">
                <form action="qayirim_welcome.php" method="POST">
                    <p> Username: <input type="text" name="usernamevd"></p>
                    <p> Email: <input type="email" name="email"><br></p>
                    <p> <input type="submit" name="submitvd" value="Submit"/></p>
                </form>
            </div>';
            break;
        case 'CharOrg':
            echo '<div id="CharOrg" style="display: block">
                <form action="qayirim_welcome.php" method="POST">
                    <p> Username: <input type="text" name="usernameco"></p>
                    <p> Password: <input type="password" name="passwordco"><br></p>
                    <p> <input type="submit" name="submitco" value="Submit"/></p>
                </form>
            </div>';
            break;
        default:
            echo "Error in switch";
    }
} else {
    echo "Role not set<br>";
}

if (isset($_POST["submitvd"]) or isset($_POST["submitco"])) {
    $con = mysqli_connect('localhost', 'root', '', 'qayirim');
    if (!$con) {
        die('Error: ' . mysqli_connect_error());
    } else {
        if (isset($_POST["submitvd"])) {
            $uservd = $_POST["usernamevd"];
            $emailvd = $_POST["email"];
			$sql = "SELECT namevoldon, emailvoldon FROM voldon WHERE namevoldon = ?";
			$stmt = $con->prepare($sql);
			$stmt->bind_param('s', $uservd);
			$stmt->execute();
			$result = $stmt->get_result();

			if ($result->num_rows == 1) {
				echo 'user exists';
			}
			else {
			$sql = "INSERT INTO voldon (namevoldon, emailvoldon) VALUES ('$uservd', '$emailvd')";
			$result = mysqli_query($con, $sql);
			if($result){
				echo 'user has been added';
			}
			header("Location: qayirim_voldon.php");
				exit();
			}
		}
		elseif (isset($_POST["submitco"])) {
            $userco = $_POST["usernameco"];
            $passco = $_POST["passwordco"];
            $sql = "SELECT usernamechar, passwordchar FROM charities WHERE usernamechar = ?";
			$stmt = $con->prepare($sql);
			$stmt->bind_param('s', $userco);
			$stmt->execute();
			$result = $stmt->get_result();

			if ($result->num_rows == 1) {
				// User found, verify password
					$user = $result->fetch_assoc();
					$stored_password = $user['passwordchar'];
				// Assuming $password is the password input value
				if ($passco == $stored_password) {
						// Password matches
						echo "Login successful!<br>";
						header("Location: qayirim_activity.php");
						exit();
				} else {
					// Password does not match
						echo "Incorrect password.<br>";
					}
			} else {
				// User not found
				echo "User not found.<br>";
				}
			$stmt->close();
		}	
    }
 	
   
}
?>

</body>
</html>
