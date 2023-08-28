<?php
function CalculateDueDate($submissionTime, $leadTime) {
    // Define business hours
    $businessHoursStart = 9; // 9am
    $businessHoursEnd = 17; // 5pm

    // Validate $submissionTime format
    $submissionDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $submissionTime);
    if (!$submissionDateTime) {
        throw new InvalidArgumentException("Érvénytelen beküldési időformátum. Használja az „Y-m-d H:i:s” formátumot.");
    }

    // Validate $leadTime
    if (!is_int($leadTime) || $leadTime <= 0) {
        throw new InvalidArgumentException("Az átfutási időnek pozitív egész számnak kell lennie.");
    }

    // Check if the submission time is outside business hours
    if ($submissionDateTime->format('H') < $businessHoursStart) {
        // If submitted before 9am, set the submission time to 9am
        $submissionDateTime->setTime($businessHoursStart, 0);
    } elseif ($submissionDateTime->format('H') >= $businessHoursEnd) {
        // If submitted at or after 5pm, set the submission time to 9am of the next day
        $submissionDateTime->add(new DateInterval('P1D')); // Add 1 day
        $submissionDateTime->setTime($businessHoursStart, 0);
    }

    // Calculate due date considering business hours and excluding weekends
    while ($leadTime > 0) {
        // Calculate the time remaining in the current day's business hours
        $currentHour = $submissionDateTime->format('H');
        $hoursRemaining = min($businessHoursEnd - $currentHour, $leadTime);

        // Add the remaining hours to the submission time
        $submissionDateTime->add(new DateInterval("PT{$hoursRemaining}H"));

        // Update lead time
        $leadTime -= $hoursRemaining;

        // If lead time is still remaining, move to the next business day
        if ($leadTime > 0) {
            // Check if the next day is a weekend (Saturday or Sunday)
            $submissionDateTime->add(new DateInterval('P1D')); // Add 1 day
            $nextDayOfWeek = $submissionDateTime->format('N'); // 1 (Monday) to 7 (Sunday)

            // Skip weekends by moving to the next Monday if it's a weekend
            while ($nextDayOfWeek >= 6) {
                $submissionDateTime->add(new DateInterval('P1D')); // Add 1 day
                $nextDayOfWeek = $submissionDateTime->format('N');
            }

            $submissionDateTime->setTime($businessHoursStart, 0);
        }
    }

    // Format the due date as desired (e.g., 'Y-m-d H:i:s')
    $dueDate = $submissionDateTime->format('Y-m-d H:i:s');

    return $dueDate;
}

// Example usage:
try {
    $submissionTime = '2023-08-30 16:00:00'; // Replace with your submission time
    $leadTime = 24; // Replace with your lead time in hours

    $dueDate = CalculateDueDate($submissionTime, $leadTime);
    echo "Határidő: $dueDate";
} catch (InvalidArgumentException $e) {
    echo "Hiba: " . $e->getMessage();
}

?>
