<?php

class DownloadController {
    private $downloadModel;
    
    public function __construct() {
        $this->downloadModel = new Download();
    }
    
    public function download() {
        $token = $_GET['token'] ?? null;
        
        if (!$token) {
            die('Invalid download token');
        }
        
        $download = $this->downloadModel->findByToken($token);
        
        if (!$download) {
            die('Download not found');
        }
        
        // Validate download
        if (!$this->downloadModel->isValid($download)) {
            die('Download link expired or usage limit exceeded');
        }
        
        // Verify user ownership (if logged in)
        if (isLoggedIn() && $download['user_id'] != $_SESSION['user_id']) {
            die('Unauthorized access');
        }
        
        // Increment usage count
        $this->downloadModel->incrementUsedCount($download['id']);
        
        // Log download
        $this->downloadModel->logDownload(
            $download['id'],
            $download['user_id'],
            $download['product_id']
        );
        
        // Stream file
        $filepath = STORAGE_SECURE_PATH . '/product_files/' . $download['filename'];
        
        if (!file_exists($filepath)) {
            die('File not found on server');
        }
        
        // Security: Never expose real filesystem paths
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $download['original_filename'] . '"');
        header('Content-Length: ' . filesize($filepath));
        header('Pragma: no-cache');
        header('Expires: 0');
        
        readfile($filepath);
        exit;
    }
}
