<?php
require 'includes/snippet.php';
require 'includes/db-inc.php';
include "includes/header.php"; 

session_start();
$student = isset($_SESSION['student-name']) ? $_SESSION['student-name'] : null; // Check if session variable exists

if (isset($_POST['del'])) {
    $id = isset($_POST['del-btn']) ? trim($_POST['del-btn']) : null;

    if ($id) {
        $sql = "DELETE FROM student WHERE id = '$id'";
        $query = mysqli_query($conn, $sql);
        $error = false;
        if ($query) {
            $error = true;
        }
    }
}

if ($student) { // Ensure $student exists before proceeding
    // Fetch borrow records for the logged-in student
    $sql = "SELECT * FROM borrow WHERE memberName = '$student'";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $counter = 1;
    } else {
        echo "Error: No borrow records found for the student.";
    }
}
?>

<div class="container">
    <?php include "includes/nav2.php"; ?>
    <div class="alert alert-warning col-lg-7 col-md-12 col-sm-12 col-xs-12 col-lg-offset-2 col-md-offset-0 col-sm-offset-1 col-xs-offset-0" style="margin-top:70px">
        <span class="glyphicon glyphicon-book"></span>
        <strong>Fines</strong> Table
    </div>
</div>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <?php if (isset($error) && $error === true) { ?>
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>Record Deleted Successfully!</strong>
            </div>
            <?php } ?>
            <div class="row">
                <a><button class="btn btn-success col-lg-3 col-md-4 col-sm-11 col-xs-11 button" style="margin-left: 15px;margin-bottom: 5px"> Fines</button></a>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-right"></div><!-- /.col-lg-6 -->
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr> 
                    <th>ID</th>
                    <th>Member Name</th>
                    <th>Matric Number</th>
                    <th>Book Name</th>
                    <th>Borrow Date</th>
                    <th>Return Date</th>
                    <th>Overdue Charges</th>
                    <th>Action</th> <!-- Added column for Pay button -->
                </tr>    
            </thead>  
            <tbody> 
            <?php
                if ($query) {
                    while ($row = mysqli_fetch_assoc($query)) {
                        // Calculate overdue charges
                        $borrowDate = strtotime($row['borrowDate']);
                        $returnDate = strtotime($row['returnDate']);
                        $now = time(); // Current timestamp

                        // Calculate difference in days between now and return date
                        $diffDays = ($now - $returnDate) / (60 * 60 * 24); // Convert seconds to days

                        // Set fine (assuming 30 per day for overdue)
                        if ($diffDays > 0) {
                            $fine = 30 * $diffDays;
                        } else {
                            $fine = 0; // No fine if not overdue
                        }

                        // Format fine value to two decimal places
                        $fineFormatted = number_format($fine, 2);
            ?>
                <tr>
                    <td><?php echo $counter++; ?></td>
                    <td><?php echo $row['memberName']; ?></td>
                    <td><?php echo $row['matricNo']; ?></td>
                    <td><?php echo $row['bookName']; ?></td>
                    <td><?php echo date('Y-m-d', strtotime($row['borrowDate'])); ?></td>
                    <td>
                        <input type="date" name="returnDate" value="<?php echo date('Y-m-d', strtotime($row['returnDate'])); ?>" class="form-control" />
                    </td>
                    <td>
                        <?php echo $fineFormatted; ?>
                    </td>
                    <td>
                        <!-- Pay button -->
                        <?php if ($fine > 0) { ?>
                            <form action="pay-fine.php" method="post">
                                <input type="hidden" name="borrowId" value="<?php echo $row['borrowId']; ?>">
                                <button type="submit" class="btn btn-primary">Pay</button>
                            </form>
                        <?php } else { ?>
                            <span>No fine</span>
                        <?php } ?>
                    </td>
                </tr>
            <?php
                    }
                }
            ?>
            </tbody> 
        </table>
    </div>
</div>

<div class="mod modal fade" id="popUpWindow">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title"> Warning</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this book?</p>
            </div>
            <div class="modal-footer">
                <button class="col-lg-4 col-sm-4 col-xs-6 col-md-4 btn btn-warning pull-right"  style="margin-left: 10px" data-dismiss="modal">No</button>
                <button class="col-lg-4 col-sm-4 col-xs-6 col-md-4 btn btn-success pull-right"  data-dismiss="modal" data-toggle="modal" data-target="#info">Yes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="info">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title"> Warning</h3>
            </div>
            <div class="modal-body">
                <p>Book deleted <span class="glyphicon glyphicon-ok"></span></p>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>    
</body>
</html>
