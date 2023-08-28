<?php
function CalculateDueDate($submissionTime, $leadTime) {
    // Define business hours
    $businessHoursStart = 9; // 9am
    $businessHoursEnd = 17; // 5pm

    // Convert submission time to a DateTime object
    $submissionDateTime = new DateTime($submissionTime);

    // Check if the submission time is outside business hours
    if ($submissionDateTime->format('H') < $businessHoursStart) {
        // If submitted before 9am, set the submission time to 9am
        $submissionDateTime->setTime($businessHoursStart, 0);
    } elseif ($submissionDateTime->format('H') >= $businessHoursEnd) {
        // If submitted at or after 5pm, set the submission time to 9am of the next day
        $submissionDateTime->add(new DateInterval('P1D')); // Add 1 day
        $submissionDateTime->setTime($businessHoursStart, 0);
    }

    // Add lead time to submission time
    $submissionDateTime->add(new DateInterval("PT{$leadTime}H")); // Assuming leadTime is in hours

    // Format the due date as desired (e.g., 'Y-m-d H:i:s')
    $dueDate = $submissionDateTime->format('Y-m-d H:i:s');

    return $dueDate;
}

// Example usage:
$submissionTime = '2023-08-28 16:00:00'; // Replace with your submission time
$leadTime = 24; // Replace with your lead time in hours

$dueDate = CalculateDueDate($submissionTime, $leadTime);
echo "Due Date: $dueDate";
?>
