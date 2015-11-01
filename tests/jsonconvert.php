<?php

/**
 * This file converts JSON downloads that contain headers etc into files that contain only JSON.
 * This is to make mocking easier in PSR-7.
 *
 * You shouldn't have to use this, ever, because the most up to date downloaded payloads will be in the repo.
 */

$dir = 'Mocks';

$firstDir = new RecursiveDirectoryIterator($dir);
$mockDir = new RecursiveIteratorIterator($firstDir,
    RecursiveIteratorIterator::SELF_FIRST);

/** @var SplFileInfo $child */
foreach ($mockDir as $child) {
    $name = $child->getBasename();

    if (in_array($name, ['.', '..'])) {
        continue;
    }

    echo "\n{$name}:\n";

    if ($child->isFile()) {
        echo "- is a file\n";
    } else {
        echo "- is a folder, and will be skipped.\n";
        continue;
    }

    if ($child->getExtension() == 'json') {
        echo "- has a valid extension.\n";
    } else {
        echo "- is not a JSON file and won't be processed.\n";
        continue;
    }

    if (strpos($name, '-fixed') !== false) {
        unlink($child->getRealPath());
        echo "- has been deleted as part of an old experiment.\n";
        continue;
    }

    $oldVersion = str_replace('.json', '-old.json', $child->getRealPath());
    if (file_exists($oldVersion)) {
        echo "- already has an old version, so looks like it's been processed.\n";
        continue;
    }

    echo "- will be processed.\n";

    $line = tailCustom($child->getRealPath(), 1);
    echo "------------------\n";
    echo "Fetched last line.\n";
    if (json_decode($line, true)) {
        echo "Line is valid JSON.\n";
        $newPath = str_replace('.json', '-old.json',
            $child->getRealPath());

        copy($child->getRealPath(), $newPath);
        echo "Copied old file to " . $newPath . "\n";

        file_put_contents($child->getRealPath(), $line);
        echo "Replaced contents of original file with JSON-only content.\n";

    } else {
        echo "Line is not a valid JSON string. Skipping.\n";
    }

    echo "\n\n";
}

function tailCustom($filepath, $lines = 1, $adaptive = true)
{
    // Open file
    $f = @fopen($filepath, "rb");
    if ($f === false) {
        return false;
    }
    // Sets buffer size
    if (!$adaptive) {
        $buffer = 4096;
    } else {
        $buffer = ($lines < 2 ? 64 : ($lines < 10 ? 512 : 4096));
    }
    // Jump to last character
    fseek($f, -1, SEEK_END);
    // Read it and adjust line number if necessary
    // (Otherwise the result would be wrong if file doesn't end with a blank line)
    if (fread($f, 1) != "\n") {
        $lines -= 1;
    }

    // Start reading
    $output = '';
    $chunk = '';
    // While we would like more
    while (ftell($f) > 0 && $lines >= 0) {
        // Figure out how far back we should jump
        $seek = min(ftell($f), $buffer);
        // Do the jump (backwards, relative to where we are)
        fseek($f, -$seek, SEEK_CUR);
        // Read a chunk and prepend it to our output
        $output = ($chunk = fread($f, $seek)) . $output;
        // Jump back to where we started reading
        fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);
        // Decrease our line counter
        $lines -= substr_count($chunk, "\n");
    }
    // While we have too many lines
    // (Because of buffer size we might have read too many)
    while ($lines++ < 0) {
        // Find first newline and remove all text before that
        $output = substr($output, strpos($output, "\n") + 1);
    }
    // Close file and return
    fclose($f);

    return trim($output);
}