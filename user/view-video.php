<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

date_default_timezone_set('Asia/Kolkata');
$currentTime = date('Y-m-d H:i:s');

// Delete expired video files
$expiredVideos = $dbh->prepare("SELECT video_path FROM tblvideo WHERE end_time < :currentTime");
$expiredVideos->bindParam(':currentTime', $currentTime, PDO::PARAM_STR);
$expiredVideos->execute();
$videos = $expiredVideos->fetchAll(PDO::FETCH_OBJ);

foreach ($videos as $v) {
    $filePath = $v->video_path;
    if (file_exists($filePath)) {
        unlink($filePath); // Delete the physical video file
    }
}

// Delete expired video records from DB
$deleteExpired = "DELETE FROM tblvideo WHERE end_time < :currentTime";
$deleteQuery = $dbh->prepare($deleteExpired);
$deleteQuery->bindParam(':currentTime', $currentTime, PDO::PARAM_STR);
$deleteQuery->execute();

// Fetch video by ID
$vid = intval($_GET['vid']);
$sql = "SELECT * FROM tblvideo WHERE id = :vid LIMIT 1";
$query = $dbh->prepare($sql);
$query->bindParam(':vid', $vid, PDO::PARAM_INT);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Video Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 1rem;
            background-color: #f2f2f2;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 1.5rem;
            text-align: center;
        }

        video {
            width: 100%;
            max-width: 800px;
            height: auto;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .back-button {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 24px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

        @media screen and (max-width: 480px) {
            h2 {
                font-size: 1.2rem;
                padding: 0 10px;
            }

            .back-button {
                padding: 10px 16px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

<?php
if ($result) {
    if ($currentTime < $result->start_time) {
        echo "<h2>Video will be available at: " . date("d M Y h:i A", strtotime($result->start_time)) . "</h2>";
    } elseif ($currentTime > $result->end_time) {
        echo "<h2>Video Showcase has ended.</h2>";
    } else {
        $videoPath = "../teacher/" . $result->video_path;
        if (file_exists($videoPath)) {
            echo "<h2>Watch Video:</h2>";
            echo '<video controls autoplay>
                <source src="' . $videoPath . '" type="video/mp4">
                Your browser does not support HTML5 video.
            </video>';
        } else {
            echo "<h2>Video file not found!</h2>";
        }
    }
} else {
    echo "<h2>Video not found or has been removed!</h2>";
}
?>
<br>
<a class="back-button" href="video.php">Back to Home</a>

</body>
</html>
