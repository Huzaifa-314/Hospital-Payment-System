<?php 
session_start();

// Check if user is not logged in or not an admin, redirect to login.php
if (!isset($_SESSION['UserID']) || $_SESSION['Role'] != 'Admin') {
    header("Location: login.php");
    exit;
}

$error = isset($_GET['error']) ? $_GET['error'] : '';
$success = isset($_GET['success']) ? $_GET['success'] : '';

// Include database connection
include_once 'include/db_con.php';

// Fetch all bill details
$sql_bills = "SELECT B.*, P.Name AS PatientName FROM Bill B
              INNER JOIN Patient P ON B.PatientID = P.PatientID";
$result_bills = mysqli_query($conn, $sql_bills);
?>

<?php include 'include/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h2 class="mb-4">Bills</h2>
            <div class="card">
                <div class="card-header">Bill Details</div>
                <div class="card-body">
                    <?php if (!empty($error)) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error; ?>
                        </div>
                    <?php } ?>
                    <?php if (!empty($success)) { ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $success; ?>
                        </div>
                    <?php } ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Patient Name</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result_bills) > 0) {
                                $count = 1;
                                while ($row = mysqli_fetch_assoc($result_bills)) { ?>
                                    <tr>
                                        <td><?php echo $count; ?></td>
                                        <td><?php echo $row['PatientName']; ?></td>
                                        <td><?php echo $row['BillAmount']; ?></td>
                                        <td><?php echo $row['BillDate']; ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo ($row['BillStatus'] == 'Paid') ? 'success' : 'warning'; ?>">
                                                <?php echo $row['BillStatus']; ?>
                                            </span>
                                        </td>
                                        <td class="d-flex align-items-center">
                                            <a href="edit_bill.php?id=<?php echo $row['BillID']; ?>" class="m-1 btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="m-1 btn btn-sm btn-danger deleteBtn" data-toggle="modal" data-target="#deleteModal_<?php echo $row['BillID']; ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal_<?php echo $row['BillID']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel_<?php echo $row['BillID']; ?>" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel_<?php echo $row['BillID']; ?>">Confirm Deletion</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete this record?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <a href="delete_bill.php?id=<?php echo $row['BillID']; ?>" class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                            <?php
                                    $count++;
                                }
                            } else { ?>
                                <tr>
                                    <td colspan="6">No bill details available.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?>
