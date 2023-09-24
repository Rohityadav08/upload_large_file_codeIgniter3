<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LargeFileUpload extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('file');
    }

    public function index() {
        $this->load->view('large_file_upload_form');
    }

    public function upload() {
        $chunkDir = 'chunks'; // Directory to store uploaded chunks
        $targetDir = 'uploads'; // Directory to store the final assembled file

        if (!file_exists($chunkDir)) {
            mkdir($chunkDir, 0777, true);
        }

        if (!empty($_FILES['file'])) {
            $chunkNumber = $_POST['resumableChunkNumber'];
            $totalChunks = $_POST['resumableTotalChunks'];
            $originalFileName = $_POST['resumableIdentifier'];
            $originalfilename = $_POST['resumableFilename'];
            $chunkFile = "{$chunkDir}/{$originalFileName}.part{$chunkNumber}";
            if (move_uploaded_file($_FILES['file']['tmp_name'], $chunkFile)) {
                echo "Chunk {$chunkNumber} of {$totalChunks} uploaded successfully.";

                if (count(glob("{$chunkDir}/{$originalFileName}.part*")) == $totalChunks) {
                    // All chunks have been uploaded, reassemble the file
                    $assembledFile = fopen("{$targetDir}/{$originalfilename}", 'ab');

                    for ($i = 1; $i <= $totalChunks; $i++) {
                        $chunkFile = "{$chunkDir}/{$originalFileName}.part{$i}";
                        $chunkData = file_get_contents($chunkFile);
                        fwrite($assembledFile, $chunkData);
                        unlink($chunkFile); // Remove the temporary chunk
                    }

                    fclose($assembledFile);
                    echo "File '{$originalFileName}' has been successfully assembled.";
                }
            } else {
                echo "Error uploading chunk {$chunkNumber}.";
            }
        }
    }
}
