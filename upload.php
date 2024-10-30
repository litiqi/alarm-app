<?php
$target_dir = "assets/uploads/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Kiểm tra định dạng file
if($fileType != "mp3" && $fileType != "wav" ) {
    echo json_encode(["message" => "Chỉ cho phép các file định dạng MP3 hoặc WAV."]);
    $uploadOk = 0;
}

if ($uploadOk == 0) {
    echo json_encode(["message" => "File không được upload."]);
// Nếu mọi thứ đều OK, lưu file vào thư mục assets/uploads/
} else {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo json_encode(["message" => "Upload thành công!", "file_path" => $target_file]);
    } else {
        echo json_encode(["message" => "Đã xảy ra lỗi khi upload file."]);
    }
}
?>
