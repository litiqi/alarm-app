document.getElementById('timer-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const time = document.getElementById('time').value;
    const songFile = document.getElementById('song-upload').files[0];
    const repeatDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'].filter(day => document.getElementById(day).checked);

    // Upload file nhạc trước
    const formData = new FormData();
    formData.append('file', songFile);

    fetch('upload.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.file_path) {
            // Sau khi upload file thành công, gửi dữ liệu báo thức
            const timerData = {
                time: time,
                song: data.file_path,
                repeat_days: repeatDays
            };

            fetch('alarms.php?action=set-timer', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(timerData)
            }).then(response => response.json())
              .then(data => {
                  alert(data.message);
                  document.getElementById('timer-form').reset();
              });
        }
    })
    .catch(error => {
        console.error('Lỗi khi upload file:', error);
    });
});
