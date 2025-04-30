<?php
include 'db_connect.php';

// Random Ads Fetch करें
$result = mysqli_query($conn, "SELECT * FROM ads WHERE status='Approved' ORDER BY RAND() LIMIT 3");
?>

<div class="ads-section">
    <h2>📢 Sponsored Ads</h2>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="ad-box">
            <a href="<?php echo $row['target_url']; ?>" target="_blank">
                <img src="ads/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>">
                <p><?php echo $row['title']; ?></p>
            </a>
        </div>
    <?php } ?>
</div>
