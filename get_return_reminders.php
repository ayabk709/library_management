<?php
include_once 'config/Database.php';
include_once 'class/IssueBooks.php';

// Create a database connection
$database = new Database();
$conn = $database->getConnection();

// Write the SQL query
$sql = "SELECT issuebookid, bookid, userid, expected_return_date, return_date_time
        FROM issued_book
        WHERE DATEDIFF(CURDATE(), expected_return_date) >= -2";

// Execute the query
$result = mysqli_query($conn, $sql);

if ($result) {
    $returnReminders = array();
    while ($row = mysqli_fetch_assoc($result)) {
        // Retrieve the return_date_time from the database query result
        $returnDateTime = $row['return_date_time'];

        // Retrieve the user details (assuming you have a users table)
        $userId = $row['userid'];
        $userQuery = "SELECT first_name, last_name FROM user WHERE id = $userId";
        $userResult = mysqli_query($conn, $userQuery);
        $userRow = mysqli_fetch_assoc($userResult);
        $firstName = $userRow['first_name'];
        $lastName = $userRow['last_name'];

        // Format the return_date_time as needed (e.g., convert to a specific date format)

        // Add the return reminder to the array
        $returnReminders[] = array(
            'return_date_time' => $returnDateTime,
            'first_name' => $firstName,
            'last_name' => $lastName,
            // Include other relevant data for the return reminder
        );
    }

    // Return the return reminders as JSON response
    echo json_encode($returnReminders);
} else {
    // Handle the query error
    echo "Failed to retrieve return reminders: " . mysqli_error($conn);
}
?>
