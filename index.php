<?php
function CalculateDueDate($submissionTime, $leadTime) {
    // Convert submission time to a DateTime object
    $submissionDateTime = new DateTime($submissionTime);

    // Add lead time to submission time
    $submissionDateTime->add(new DateInterval("PT{$leadTime}H")); // Assuming leadTime is in hours

    // Format the due date as desired (e.g., 'Y-m-d H:i:s')
    $dueDate = $submissionDateTime->format('Y-m-d H:i:s');

    return $dueDate;
}

// Example usage:
$submissionTime = '2023-08-28 10:00:00'; // Replace with your submission time
$leadTime = 24; // Replace with your lead time in hours

$dueDate = CalculateDueDate($submissionTime, $leadTime);
echo "Due Date: $dueDate";
?>
