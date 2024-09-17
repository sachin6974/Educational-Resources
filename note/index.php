<?php
@include("config.php");

if (isset($_POST['submit'])) {
    // Get form data
    $studentName = $_POST['studentName'];
    $studentId = $_POST['studentId'];
    $studentBatch = $_POST['studentBatch'];
    $studentDept = $_POST['studentDept'];
    $subjectSelect = $_POST['subjectSelect'];

    // File upload handling
    $fileName = $_FILES['fileInput']['name'];
    $fileTmpName = $_FILES['fileInput']['tmp_name'];
    $fileDestination = 'uploads/' . $fileName;

    // Check if the studentId already exists in the database
    $checkStudentQuery = "SELECT * FROM student_details WHERE id = '$studentId'";
    $result = $conn->query($checkStudentQuery);

    if ($result->num_rows > 0) {
        echo "This student ID already exists!";
    } else {
        if (move_uploaded_file($fileTmpName, $fileDestination)) {
            // Prepare the SQL query to insert data into the database
            $sql = "INSERT INTO student_details (student, id, batch, department, subject, file) 
                    VALUES ('$studentName', '$studentId', '$studentBatch', '$studentDept', '$subjectSelect', '$fileDestination')";

            // Execute the query
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully!";
                // Redirect to the same page to prevent form resubmission
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Error uploading file!";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Educational Resources</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header Section -->
    <header>
        <img src="pic1.png" alt="Website Logo" class="logo">
        <h1>Educational Resources</h1>
    </header>

    <!-- Main Content -->
    <main>
        <section class="student-info">
            <h2>Enter Student Information</h2>
            
            <form action="index.php" method="POST" enctype="multipart/form-data">
                <label for="studentName">Student Name:</label>
                <input type="text" id="studentName" name="studentName" placeholder="Enter name" required>
                
                <label for="studentId">Student ID:</label>
                <input type="text" id="studentId" name="studentId" placeholder="Enter ID" required>
                
                <label for="studentBatch">Student Batch:</label>
                <input type="text" id="studentBatch" name="studentBatch" placeholder="Enter Batch" required>
                
                <label for="studentDept">Student Department:</label>
                <input type="text" id="studentDept" name="studentDept" placeholder="Enter Department" required>
                
                <label for="subjectSelect">Select Subject:</label>
                <select id="subjectSelect" name="subjectSelect">
                    <option value="DigitalLogicDesign">CSE-2277 - Digital Logic Design [A] (Theory)</option>
                    <option value="DigitalLogicDesignLab">CSE-2278 - Digital Logic Design Lab [A] (Lab)</option>
                    <option value="NumericalMethods">CSE-2339 - Numerical Methods [A] (Theory)</option>
                    <option value="SystemAnalysisAndDesign">CSE-2361 - System Analysis and Design [A] (Theory)</option>
                    <option value="SystemAnalysisAndDesignLab">CSE-2362 - System Analysis and Design Lab [A] (Lab)</option>
                    <option value="ComputerArchitecture">CSE-2367 - Computer Architecture [A] (Theory)</option>
                    <option value="ProjectII">ICT-3204 - Project II [A] (ProjectOrThesis)</option>
                </select>

                <label for="fileInput">Upload PDF:</label>
                <input type="file" id="fileInput" name="fileInput" required>
                
                <button type="submit" name="submit">Submit</button>
            </form>
        </section>

        <!-- Section to Display Submitted Data -->
        <section class="submitted-data">
            <h2>Submitted Student Details</h2>
            <?php
            // Fetch data from the database
            $sql = "SELECT * FROM student_details";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table border='1'>
                        <tr>
                            <th>Student Name</th>
                            <th>Student ID</th>
                            <th>Batch</th>
                            <th>Department</th>
                            <th>Subject</th>
                            <th>Uploaded File</th>
                        </tr>";
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["student"] . "</td>
                            <td>" . $row["id"] . "</td>
                            <td>" . $row["batch"] . "</td>
                            <td>" . $row["department"] . "</td>
                            <td>" . $row["subject"] . "</td>
                            <td><a href='" . $row["file"] . "' target='_blank'>View PDF</a></td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "No data found!";
            }

            // Close the connection after all operations
            $conn->close();
            ?>
        </section>
    </main>

    <!-- Footer Section -->
    <footer>
        <p>Made by Sachin, Suborna, and Shafin - 30th Batch CSE</p>
    </footer>

<style>
    /* Styling for the Submitted Data Section */
.submitted-data {
    margin: 20px auto;
    width: 90%;
    max-width: 1200px;
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.submitted-data h2 {
    text-align: center;
    font-size: 2rem;
    color: #333;
    margin-bottom: 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Styling for the table */
.submitted-data table {
    width: 100%;
    border-collapse: collapse;
    overflow: hidden;
}

.submitted-data th, .submitted-data td {
    padding: 15px;
    text-align: left;
    font-family: 'Arial', sans-serif;
}

.submitted-data th {
    background-color: #0066cc;
    color: #ffffff;
    font-size: 1.1rem;
}

.submitted-data td {
    background-color: #ffffff;
    border-bottom: 1px solid #dddddd;
    color: #555;
    font-size: 1rem;
}

/* Hover effect for table rows */
.submitted-data tr:hover td {
    background-color: #f1f1f1;
    transition: background-color 0.3s ease;
}

/* Styling for the 'View PDF' link */
.submitted-data a {
    color: #0066cc;
    text-decoration: none;
    font-weight: bold;
}

.submitted-data a:hover {
    color: #ff6600;
    text-decoration: underline;
    transition: color 0.3s ease;
}

/* Animations for the table when hovered */
.submitted-data tr {
    transition: transform 0.2s ease;
}

.submitted-data tr:hover {
    transform: scale(1.02);
}

/* Responsive adjustments for smaller screens */
@media (max-width: 768px) {
    .submitted-data th, .submitted-data td {
        font-size: 0.9rem;
        padding: 10px;
    }

    .submitted-data h2 {
        font-size: 1.5rem;
    }
}

</style>



</body>
</html>
