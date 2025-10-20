<?php
// This script is intended to be run by a cron job every minute.
require_once __DIR__ . '/functions.php';

function process_jobs() {
    $pdo = pdo();

    // 1. Fetch due jobs
    $stmt = $pdo->prepare("SELECT * FROM jobs WHERE run_at <= NOW() AND locked_at IS NULL AND failed_at IS NULL ORDER BY id ASC LIMIT 10");
    $stmt->execute();
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($jobs)) {
        echo "No jobs to process." . PHP_EOL;
        return;
    }

    foreach ($jobs as $job) {
        // 2. Lock the job
        $lockStmt = $pdo->prepare("UPDATE jobs SET locked_at = NOW() WHERE id = ? AND locked_at IS NULL");
        $locked = $lockStmt->execute([$job['id']]);

        if (!$locked || $lockStmt->rowCount() === 0) {
            // Job was locked by another process
            continue;
        }

        try {
            // 3. Process the job
            echo "Processing job ID: {$job['id']}" . PHP_EOL;
            // Placeholder for job handler logic
            // e.g., if (class_exists($job['handler'])) { $handler = new $job['handler'](); $handler->handle(json_decode($job['payload'], true)); }

            // For now, we'll just simulate a successful job
            sleep(1); // Simulate work

            // 4. On success, delete the job
            $deleteStmt = $pdo->prepare("DELETE FROM jobs WHERE id = ?");
            $deleteStmt->execute([$job['id']]);
            echo "Job ID: {$job['id']} completed successfully." . PHP_EOL;

        } catch (Exception $e) {
            // 5. On failure, log the error and update
            $error = $e->getMessage();
            echo "Job ID: {$job['id']} failed: {$error}" . PHP_EOL;
            $failStmt = $pdo->prepare("UPDATE jobs SET locked_at = NULL, failed_at = NOW(), error = ?, attempts = attempts + 1 WHERE id = ?");
            $failStmt->execute([$error, $job['id']]);
        }
    }
}

process_jobs();
