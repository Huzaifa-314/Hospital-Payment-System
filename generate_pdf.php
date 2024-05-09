<?php
// Include Dompdf library via CDN
require_once 'https://cdnjs.cloudflare.com/ajax/libs/dompdf/0.8.6/autoload.inc.php';

use Dompdf\Dompdf;

// Create a new Dompdf instance
$dompdf = new Dompdf();

// Load HTML content from the receipt template file
$html = file_get_contents('receipt_template.html');

// Replace PHP variables in the HTML template with actual values
$html = str_replace(
    ['<?php echo $tran_id; ?>', '<?php echo $tran_date; ?>', '<?php echo $amount; ?>', '<?php echo $card_type; ?>'],
    [$tran_id, $tran_date, $amount, $card_type],
    $html
);

// Load HTML content into Dompdf
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render PDF (output to variable)
$dompdf->render();

// Output PDF as a downloadable file
$dompdf->stream('receipt.pdf');
?>
