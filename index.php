<?php
// Default tweet data
$defaultTweetData = [
    'username' => 'elonmusk',
    'displayName' => 'Elon Musk',
    'verified' => true,
    'tweetText' => 'Just bought Twitter for $44 billion. Time to make some changes! 🚀',
    'timestamp' => '2h',
    'likes' => '127K',
    'retweets' => '45K',
    'replies' => '8.2K',
    'avatar' => 'https://pbs.twimg.com/profile_images/1683325380441128960/yRsRRjGO_400x400.jpg'
];

// Handle form submission
if ($_POST) {
    $tweetData = [
        'username' => $_POST['username'] ?? $defaultTweetData['username'],
        'displayName' => $_POST['displayName'] ?? $defaultTweetData['displayName'],
        'verified' => isset($_POST['verified']),
        'tweetText' => $_POST['tweetText'] ?? $defaultTweetData['tweetText'],
        'timestamp' => $_POST['timestamp'] ?? $defaultTweetData['timestamp'],
        'likes' => $_POST['likes'] ?? $defaultTweetData['likes'],
        'retweets' => $_POST['retweets'] ?? $defaultTweetData['retweets'],
        'replies' => $_POST['replies'] ?? $defaultTweetData['replies'],
        'avatar' => $_POST['avatar'] ?? $defaultTweetData['avatar']
    ];
    
    // Handle image upload
    $uploadedImage = null;
    if (isset($_FILES['postImage']) && $_FILES['postImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = time() . '_' . $_FILES['postImage']['name'];
        $uploadPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['postImage']['tmp_name'], $uploadPath)) {
            $uploadedImage = $uploadPath;
        }
    }
} else {
    $tweetData = $defaultTweetData;
    $uploadedImage = null;
}

function formatNumber($num) {
    if (strpos($num, 'K') !== false || strpos($num, 'M') !== false) {
        return $num;
    }
    $number = intval($num);
    if ($number >= 1000000) {
        return round($number / 1000000, 1) . 'M';
    }
    if ($number >= 1000) {
        return round($number / 1000, 1) . 'K';
    }
    return $number;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fake Tweet Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .tweet-container {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            min-width: 500px;
        }
        
        .tweet-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .verified-badge {
            color: #1d9bf0;
            font-size: 20px;
            margin-left: 4px;
        }
        
        .tweet-username {
            color: #536471;
            margin-left: 8px;
        }
        
        .tweet-content {
            font-size: 18px;
            line-height: 1.4;
            color: #14171a;
            margin: 12px 0;
        }
        
        .tweet-image {
            max-width: 100%;
            border-radius: 16px;
            margin: 12px 0;
            max-height: 400px;
            object-fit: cover;
        }
        
        .tweet-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 16px;
            max-width: 400px;
            color: #536471;
        }
        
        .tweet-action {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: color 0.2s;
        }
        
        .tweet-action:hover {
            color: #1d9bf0;
        }
        
        .form-container {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            height: fit-content;
        }
        
        .disclaimer {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 16px;
            margin-top: 20px;
        }
        
        .tip-box {
            background-color: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 20px;
        }
        
        .image-preview {
            position: relative;
            margin-top: 10px;
        }
        
        .image-preview img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .remove-image {
            position: absolute;
            top: 8px;
            right: 8px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .remove-image:hover {
            background: #c82333;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <h1 class="text-center mb-5 fw-bold">🐦 Fake Tweet Generator (For Prank Only)</h1>
            </div>
        </div>
        
        <div class="row g-4">
            <!-- Left Column - Form -->
            <div class="col-lg-6">
                <div class="form-container">
                    <h2 class="mb-4 fw-semibold">Tweet Settings</h2>
                    
                    <form method="POST" enctype="multipart/form-data" id="tweetForm">
                        <div class="mb-3">
                            <label for="username" class="form-label fw-medium">Username</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?= htmlspecialchars($tweetData['username']) ?>" 
                                   placeholder="elonmusk" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="displayName" class="form-label fw-medium">Display Name</label>
                            <input type="text" class="form-control" id="displayName" name="displayName" 
                                   value="<?= htmlspecialchars($tweetData['displayName']) ?>" 
                                   placeholder="Elon Musk" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="avatar" class="form-label fw-medium">Avatar URL</label>
                            <input type="url" class="form-control" id="avatar" name="avatar" 
                                   value="<?= htmlspecialchars($tweetData['avatar']) ?>" 
                                   placeholder="https://example.com/avatar.jpg" required>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="verified" name="verified" 
                                       <?= $tweetData['verified'] ? 'checked' : '' ?>>
                                <label class="form-check-label fw-medium" for="verified">
                                    Verified Account
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tweetText" class="form-label fw-medium">Tweet Text</label>
                            <textarea class="form-control" id="tweetText" name="tweetText" rows="4" 
                                      placeholder="What's happening?" required><?= htmlspecialchars($tweetData['tweetText']) ?></textarea>
                        </div>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="timestamp" class="form-label fw-medium">Timestamp</label>
                                <input type="text" class="form-control" id="timestamp" name="timestamp" 
                                       value="<?= htmlspecialchars($tweetData['timestamp']) ?>" 
                                       placeholder="2h" required>
                            </div>
                            <div class="col-6">
                                <label for="likes" class="form-label fw-medium">Likes</label>
                                <input type="text" class="form-control" id="likes" name="likes" 
                                       value="<?= htmlspecialchars($tweetData['likes']) ?>" 
                                       placeholder="127K" required>
                            </div>
                        </div>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="retweets" class="form-label fw-medium">Retweets</label>
                                <input type="text" class="form-control" id="retweets" name="retweets" 
                                       value="<?= htmlspecialchars($tweetData['retweets']) ?>" 
                                       placeholder="45K" required>
                            </div>
                            <div class="col-6">
                                <label for="replies" class="form-label fw-medium">Replies</label>
                                <input type="text" class="form-control" id="replies" name="replies" 
                                       value="<?= htmlspecialchars($tweetData['replies']) ?>" 
                                       placeholder="8.2K" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="postImage" class="form-label fw-medium">Post Image (Optional)</label>
                            <input type="file" class="form-control" id="postImage" name="postImage" 
                                   accept="image/*" onchange="previewImage(this)">
                            
                            <?php if ($uploadedImage): ?>
                                <div class="image-preview" id="imagePreview">
                                    <img src="<?= htmlspecialchars($uploadedImage) ?>" alt="Post preview">
                                    <button type="button" class="remove-image" onclick="removeImage()">×</button>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-sync-alt me-2"></i>Update Tweet
                        </button>
                        
                        <div class="disclaimer">
                            <p class="mb-0 text-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Disclaimer:</strong> This tool is for entertainment purposes only. 
                                Creating fake social media posts to deceive others may be harmful and is not recommended.
                            </p>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Right Column - Tweet Preview -->
            <div class="col-lg-6">
                <div class="form-container">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="fw-semibold mb-0">Live Preview</h2>
                        <button type="button" class="btn btn-primary" onclick="downloadScreenshot()">
                            <i class="fas fa-download me-2"></i>Download PNG
                        </button>
                    </div>
                    
                    <div class="tip-box">
                        <p class="mb-0">
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>Tip:</strong> If the download button doesn't work, you can use:
                            <br>• Windows: Print Screen key or Windows + Shift + S
                            <br>• Mac: Command + Shift + 4
                            <br>• Or right-click the tweet and "Save as image"
                        </p>
                    </div>
                    
                    <!-- Tweet Preview -->
                    <div class="tweet-container" id="tweetContainer">
                        <div class="d-flex">
                            <img src="<?= htmlspecialchars($tweetData['avatar']) ?>" 
                                 alt="Avatar" class="tweet-avatar me-3"
                                 onerror="this.src='https://via.placeholder.com/48/1DA1F2/ffffff?text=?'">
                            
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center flex-wrap">
                                    <span class="fw-bold text-dark"><?= htmlspecialchars($tweetData['displayName']) ?></span>
                                    
                                    <?php if ($tweetData['verified']): ?>
                                        <i class="fas fa-check-circle verified-badge"></i>
                                    <?php endif; ?>
                                    
                                    <span class="tweet-username">@<?= htmlspecialchars($tweetData['username']) ?></span>
                                    <span class="tweet-username">·</span>
                                    <span class="tweet-username"><?= htmlspecialchars($tweetData['timestamp']) ?></span>
                                </div>
                                
                                <div class="tweet-content">
                                    <?= nl2br(htmlspecialchars($tweetData['tweetText'])) ?>
                                </div>
                                
                                <?php if ($uploadedImage): ?>
                                    <img src="<?= htmlspecialchars($uploadedImage) ?>" 
                                         alt="Tweet image" class="tweet-image">
                                <?php endif; ?>
                                
                                <div class="tweet-actions">
                                    <div class="tweet-action">
                                        <i class="far fa-comment"></i>
                                        <span><?= formatNumber($tweetData['replies']) ?></span>
                                    </div>
                                    <div class="tweet-action">
                                        <i class="fas fa-retweet"></i>
                                        <span><?= formatNumber($tweetData['retweets']) ?></span>
                                    </div>
                                    <div class="tweet-action">
                                        <i class="far fa-heart"></i>
                                        <span><?= formatNumber($tweetData['likes']) ?></span>
                                    </div>
                                    <div class="tweet-action">
                                        <i class="far fa-share-square"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    if (preview) {
                        preview.remove();
                    }
                    
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'image-preview';
                    previewDiv.id = 'imagePreview';
                    previewDiv.innerHTML = `
                        <img src="${e.target.result}" alt="Post preview">
                        <button type="button" class="remove-image" onclick="removeImage()">×</button>
                    `;
                    input.parentNode.appendChild(previewDiv);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function removeImage() {
            const preview = document.getElementById('imagePreview');
            const fileInput = document.getElementById('postImage');
            if (preview) {
                preview.remove();
            }
            if (fileInput) {
                fileInput.value = '';
            }
        }
        
        async function downloadScreenshot() {
            const tweetContainer = document.getElementById('tweetContainer');
            
            try {
                const canvas = await html2canvas(tweetContainer, {
                    backgroundColor: '#ffffff',
                    scale: 2,
                    useCORS: true
                });
                
                const link = document.createElement('a');
                link.download = `tweet-${new Date().getTime()}.png`;
                link.href = canvas.toDataURL();
                link.click();
            } catch (error) {
                console.error('Error generating screenshot:', error);
                alert('Screenshot failed. Please try using Print Screen button or your browser\'s screenshot tool.');
            }
        }
        
        // Auto-submit form for real-time updates (optional)
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('tweetForm');
            const inputs = form.querySelectorAll('input, textarea, select');
            
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    // Optional: Auto-submit for real-time updates
                    // form.submit();
                });
            });
        });
    </script>
</body>
</html>
