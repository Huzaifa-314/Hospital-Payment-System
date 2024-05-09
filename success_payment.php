<?php 
session_start();
include_once 'include/db_con.php';

// Check if user is logged in
if(!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit;
}

if(isset($_POST["val_id"]))
{
    // Get all necessary information from the POST request
    $val_id = urlencode($_POST['val_id']);
    $status = $_POST['status'];
    $tran_date = $_POST['tran_date'];
    $tran_id = $_POST['tran_id'];
    $amount = $_POST['amount'];
    $store_amount = $_POST['store_amount'];
    $bank_tran_id = $_POST['bank_tran_id'];
    $card_type = $_POST['card_type'];

    // Get BillID from the GET parameter
    $BillID = $_GET['BillID'];

    // Insert payment information into the database
    $query = "INSERT INTO Payment (BillID, PaymentAmount, PaymentDate, PaymentMethod, PaymentStatus, TransactionDate, TransactionID, StoreAmount, BankTransactionID, CardType) 
            VALUES ('$BillID', '$amount', NOW(), 'Credit Card', 'Completed', '$tran_date', '$tran_id', '$store_amount', '$bank_tran_id', '$card_type')";
    $result = mysqli_query($conn, $query);

    // Check if the insertion was successful
    if ($result) {
        // Update bill table status to "Paid"
        $update_query = "UPDATE Bill SET BillStatus = 'Paid' WHERE BillID = '$BillID'";
        $update_result = mysqli_query($conn, $update_query);

        if ($update_result) {
            // Payment and update successful
            $message = "Your payment has been confirmed. Transaction ID: $tran_id";
        } else {
            // Payment successful, but failed to update bill status
            $message = "Failed to update bill status.";
        }
    } else {
        // Payment failed
        $message = "Failed to store payment information in the database.";
    }
}

// Fetch bill details for the invoice
$bill_query = "SELECT * FROM Bill WHERE BillID = '$BillID'";
$bill_result = mysqli_query($conn, $bill_query);
$bill_row = mysqli_fetch_assoc($bill_result);

// Calculate total amount
$total_amount = $bill_row['BillAmount'];

// Include header
include 'include/header.php'; 
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-success" role="alert">
                <i class="fas fa-check-circle mr-2"></i> <?php echo $message; ?>
            </div>
            <h4>Invoice</h4>
            <p><strong>Transaction ID:</strong> <?php echo $tran_id; ?></p>
            <p><strong>Transaction Date:</strong> <?php echo $tran_date; ?></p>
            <p><strong>Total Amount:</strong> $<?php echo $total_amount; ?></p>
            <!-- Add more invoice details as needed -->
            <a href="" onclick="window.print()" class="btn btn-primary">Download Receipt</a>
        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?>
