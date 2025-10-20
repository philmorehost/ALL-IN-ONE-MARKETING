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
            // Autoload the job handler class
            $handler_file = __DIR__ . '/' . str_replace('App\\', '', $job['handler']) . '.php';
            $handler_file = str_replace('/', DIRECTORY_SEPARATOR, $handler_file); // Adjust for OS

            if (file_exists($handler_file)) {
                require_once $handler_file;

                $handler_class = $job['handler'];
                if (class_exists($handler_class)) {
                    $handler = new $handler_class();
                    $payload = json_decode($job['payload'], true);
                    $handler->handle($payload);

                    // On success, delete the job
                    $deleteStmt = $pdo->prepare("DELETE FROM jobs WHERE id = ?");
                    $deleteStmt->execute([$job['id']]);
                    echo "Job ID: {$job['id']} completed successfully." . PHP_EOL;
                } else {
                    throw new Exception("Job handler class not found: {$handler_class}");
                }
            } else {
                throw new Exception("Job handler file not found: {$handler_file}");
            }

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

process_jobs();
