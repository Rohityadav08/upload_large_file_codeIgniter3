<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Large File Upload</title>
</head>
<body>
    <h1>Upload Form</h1>
    <form id="upload-form">
        <input type="file" id="file-input">
        <button type="button" id="start-upload">Start Upload</button>
    </form>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/resumable.js/1.0.3/resumable.min.js" integrity="sha512-OmtdY/NUD+0FF4ebU+B5sszC7gAomj26TfyUUq6191kbbtBZx0RJNqcpGg5mouTvUh7NI0cbU9PStfRl8uE/rw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        const resumable = new Resumable({
            target: '<?php echo base_url("LargeFileUpload/upload") ?>', // URL to your server-side script
            chunkSize: 1 * 1024 * 1024, // 2 MB chunks (adjust as needed)
            simultaneousUploads: 3, // Number of chunks to upload in parallel
            testChunks: false, // Disable chunk testing to save time (you can enable it for added reliability)
        });

        resumable.assignBrowse(document.getElementById('file-input'));

        document.getElementById('start-upload').addEventListener('click', function () {
            resumable.upload();
        });

        resumable.on('fileSuccess', function (file) {
            console.log('File successfully uploaded:', file.fileName);
        });

        resumable.on('fileError', function (file, message) {
            console.error('Error uploading file:', file.fileName, message);
        });
    </script>
</body>
</html>
