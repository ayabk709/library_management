<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
class IssueBooks {	
   
    private $issuedBookTable = 'issued_book';
	private $bookTable = 'book';
	private $userTable = 'user';
	private $conn;
	
	public function __construct($db){
        $this->conn = $db;
    }
	
	// function getUserEmails() {
	// 	$sql = "SELECT DISTINCT u.email
	// 			FROM user u
	// 			INNER JOIN issued_book i ON u.id = i.userid";
	
	// 	$stmt = $this->conn->prepare($sql);
	// 	$stmt->execute();
	// 	$result = $stmt->get_result();
	
	// 	$emails = [];
	// 	while ($row = $result->fetch_assoc()) {
	// 		$emails[] = $row['email'];
	// 	}
	
	// 	$stmt->close();
	
	// 	return $emails;
	// }
	
    function getUserEmails($issuebookid) {
        $sql = "SELECT DISTINCT u.email
                FROM user u
                INNER JOIN issued_book i ON u.id = i.userid
                WHERE i.issuebookid = ?";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $issuebookid);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $emails = [];
        while ($row = $result->fetch_assoc()) {
            $emails[] = $row['email'];
        }
    
        $stmt->close();
    
        return $emails;
    }
    
public function listIssuedBook()
{
    $sqlQuery = "SELECT issue_book.issuebookid, issue_book.issue_date_time, issue_book.expected_return_date, issue_book.return_date_time, issue_book.status, book.name AS book_name, book.isbn, user.first_name, user.last_name 
        FROM " . $this->issuedBookTable . " issue_book		    
        LEFT JOIN " . $this->bookTable . " book ON book.bookid = issue_book.bookid
        LEFT JOIN " . $this->userTable . " user ON user.id = issue_book.userid ";

    $searchValue = $_POST["search"]["value"];
    if (!empty($_POST["search"]["value"])) {
        $sqlQuery .= ' WHERE (issue_book.issuebookid LIKE "%' . $searchValue . '%" ';
        $sqlQuery .= '  OR book.name LIKE "%' . $searchValue . '%" ';
        $sqlQuery .= '  OR CONCAT(user.first_name, " ", user.last_name) LIKE "%' . $searchValue . '%" ';
        $sqlQuery .= ')';
    }

    if (!empty($_POST["order"])) {
        $sqlQuery .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
    } else {
        $sqlQuery .= 'ORDER BY issue_book.issuebookid DESC ';
    }

    if ($_POST["length"] != -1) {
        $sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
    }

    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    $stmtTotal = $this->conn->prepare($sqlQuery);
    $stmtTotal->execute();
    $allResult = $stmtTotal->get_result();
    $allRecords = $allResult->num_rows;

    $displayRecords = $result->num_rows;
    $records = array();
    $count = 1;
    while ($issueBook = $result->fetch_assoc()) {
        $rows = array();
        $rows[] = $count;
        $rows[] = ucfirst($issueBook['book_name']);
        $rows[] = ucfirst($issueBook['isbn']);
        $rows[] = ucfirst($issueBook['first_name']) . " " . ucfirst($issueBook['last_name']);
        $rows[] = ucfirst($issueBook['issue_date_time']);
        $rows[] = ucfirst($issueBook['expected_return_date']);
        $rows[] = ucfirst($issueBook['return_date_time']);
        $rows[] = $issueBook['status'];

        // Calculate the fine amount based on expected return date and return date
        $expectedReturnDate = new DateTime($issueBook['expected_return_date']);
        $returnDate = new DateTime($issueBook['return_date_time']);
        $fineAmount = 0;
        if ($returnDate > $expectedReturnDate) {
            $daysLate = $returnDate->diff($expectedReturnDate)->days;
            $fineAmount = $daysLate * 5; // Set your fine rate here
        }
        $rows[] = $fineAmount;

        $rows[] = '<button type="button" name="update" id="' . $issueBook["issuebookid"] . '" class="btn btn-warning btn-xs update"><span class="glyphicon glyphicon-edit" title="Edit">Edit</span></button>';
        $rows[] = '<button type="button" name="delete" id="' . $issueBook["issuebookid"] . '" class="btn btn-danger btn-xs delete" ><span class="glyphicon glyphicon-remove" title="Delete">Delete</span></button>';
        $records[] = $rows;
        $currentDate = new DateTime();

        // Calculate the date 2 days before the expected return date
        $notificationDate = new DateTime($issueBook['expected_return_date']);
        $notificationDate->modify('-2 days');

        // Check if the current date is equal to or greater than the notification date
        if ($currentDate >= $notificationDate) {
            // Retrieve the email addresses of all users
            $userEmails = $this->getUserEmails($issueBook['issuebookid']);


            foreach ($userEmails as $email) {
                // Send notification message to each user
                $mail = new PHPMailer(true);
                
                try {
                    // SMTP configuration
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'aya.bekkach@gmail.com';
                    $mail->Password   = 'tmsqqdbskgeiewtt';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port       = 587;
                    $mail->setFrom('aya.bekkach@gmail.com');
                    $mail->addAddress($email);  // Use each email address

                    // Email content
                    $mail->isHTML(true);
                    $mail->Subject = 'Reminder: Return Book';
                    $mail->Body = 'Cher utilisateur, veuillez vous rappeler de retourner le livre "'. $issueBook['book_name'] . '"  avant la date de retour prévue. (' . $issueBook['expected_return_date'] . '). Thank you :)) .';
                    $mail->Body = 'Cher utilisateur, veuillez vous rappeler de retourner le livre "'. $issueBook['book_name'] . '"  avant la date de retour prévue. (' . $issueBook['expected_return_date'] . '). Thank you :)) .';
                    // Send the email
                    $mail->send();

                    // Update the notification status in the database
                    // ...
                } catch (Exception $e) {
                    echo 'Email could not be sent. Error: ' . $mail->ErrorInfo;
                }
            }
        }
        
        $count++;
    }

    $output = array(
        "draw" => intval($_POST["draw"]),
        "iTotalRecords" => $displayRecords,
        "iTotalDisplayRecords" => $allRecords,
        "data" => $records
    );

    echo json_encode($output);
}

	
	public function insert(){
		
		if($this->book && $_SESSION["userid"]) {

			$stmt = $this->conn->prepare("
				INSERT INTO ".$this->issuedBookTable."(`bookid`, `userid`, `expected_return_date`, `return_date_time`, `status`)
				VALUES(?, ?, ?, ?, ?)");
		
			$this->book = htmlspecialchars(strip_tags($this->book));
			$this->users = htmlspecialchars(strip_tags($this->users));
			$this->expected_return_date = htmlspecialchars(strip_tags($this->expected_return_date));
			$this->return_date = htmlspecialchars(strip_tags($this->return_date));
			$this->status = htmlspecialchars(strip_tags($this->status));			
			
			$stmt->bind_param("iisss", $this->book, $this->users, $this->expected_return_date, $this->return_date, $this->status);
			
			if($stmt->execute()){
				return true;
			}		
		}
	}
	
	public function update(){
		
		if($this->issuebookid && $this->book && $_SESSION["userid"]) {			
	
			$stmt = $this->conn->prepare("
				UPDATE ".$this->issuedBookTable." 
				SET bookid = ?, userid = ?, expected_return_date = ?, return_date_time = ?, status = ?
				WHERE issuebookid = ?");
	 
			$this->book = htmlspecialchars(strip_tags($this->book));
			$this->users = htmlspecialchars(strip_tags($this->users));
			$this->expected_return_date = htmlspecialchars(strip_tags($this->expected_return_date));
			$this->return_date = htmlspecialchars(strip_tags($this->return_date));
			$this->status = htmlspecialchars(strip_tags($this->status));
			
			$stmt->bind_param("iisssi", $this->book, $this->users, $this->expected_return_date, $this->return_date, $this->status, $this->issuebookid);
			
			if($stmt->execute()){				
				return true;
			}			
		}	
	}	
	
	public function delete(){
		if($this->issuebookid && $_SESSION["userid"]) {			

			$stmt = $this->conn->prepare("
				DELETE FROM ".$this->issuedBookTable." 
				WHERE issuebookid = ?");

			$this->issuebookid = htmlspecialchars(strip_tags($this->issuebookid));

			$stmt->bind_param("i", $this->issuebookid);

			if($stmt->execute()){				
				return true;
			}
		}
	}
	
	public function getIssueBookDetails(){
		if($this->issuebookid && $_SESSION["userid"]) {

			$sqlQuery = "SELECT issue_book.issuebookid, issue_book.issue_date_time, issue_book.expected_return_date, issue_book.return_date_time, issue_book.status, issue_book.bookid, issue_book.userid, book.name AS book_name
			FROM ".$this->issuedBookTable." issue_book		    
			LEFT JOIN ".$this->bookTable." book ON book.bookid = issue_book.bookid
			LEFT JOIN ".$this->userTable." user ON user.id = issue_book.userid
			WHERE issue_book.issuebookid = ?";			
					
			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->bind_param("i", $this->issuebookid);	
			$stmt->execute();
			$result = $stmt->get_result();				
			$records = array();		
			while ($issueBook = $result->fetch_assoc()) { 				
				$rows = array();	
				$rows['issuebookid'] = $issueBook['issuebookid'];				
				$rows['bookid'] = $issueBook['bookid'];	
				$rows['book_name'] = $issueBook['book_name'];				
				$rows['status'] = $issueBook['status'];
				$rows['userid'] = $issueBook['userid'];
				$rows['expected_return_date'] = date ('Y-m-d\TH:i:s', strtotime($issueBook['expected_return_date']));
				$rows['return_date_time'] = date ('Y-m-d\TH:i:s', strtotime($issueBook['return_date_time']));				
				$records[] = $rows;
			}		
			$output = array(			
				"data"	=> 	$records
			);
			echo json_encode($output);
		}
	}	
	// Get the current date

}



?>



