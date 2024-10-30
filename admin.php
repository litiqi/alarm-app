<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý báo thức</title>
    <link rel="stylesheet" href="style/admin.css">
</head>
<body>
    <h1>Quản lý báo thức</h1>

    <!-- Form thêm/sửa báo thức -->
    <form id="timer-form">
        <input type="hidden" id="timer-id">
        <label for="time">Thời gian báo:</label>
        <input type="time" id="time" name="time" required>
        <br>
        <label for="song">Chọn bản nhạc:</label>
        <input type="file" id="song-upload" name="song" required>
        <br>
        <label>Nhắc lại vào các ngày:</label>
        <br>
        <label><input type="checkbox" value="Mon" id="Mon"> Thứ 2</label>
        <label><input type="checkbox" value="Tue" id="Tue"> Thứ 3</label>
        <label><input type="checkbox" value="Wed" id="Wed"> Thứ 4</label>
        <label><input type="checkbox" value="Thu" id="Thu"> Thứ 5</label>
        <label><input type="checkbox" value="Fri" id="Fri"> Thứ 6</label>
        <label><input type="checkbox" value="Sat" id="Sat"> Thứ 7</label>
        <label><input type="checkbox" value="Sun" id="Sun"> Chủ Nhật</label>
        <br><br>
        <button type="submit">Lưu</button>
    </form>

    <h2>Danh sách báo thức</h2>
    <ul id="timer-list"></ul>

    <script src="js/admin.js"></script>
</body>
</html>
