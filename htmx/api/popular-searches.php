<?php
$filePath = __DIR__ . '/../public/data/popular-searches.html';
if (file_exists($filePath)) {
    echo file_get_contents($filePath);
} else {
    echo '<div class="alert alert-error">Popular searches not found</div>';
}
?>