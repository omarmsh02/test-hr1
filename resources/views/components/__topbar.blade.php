<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Employee Topbar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="user-id" content="{{ auth()->id() }}" />
    <style>
        .navbar-custom {
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 0.5rem 1rem;
        }
        .brand-container {
            display: flex;
            align-items: center;
        }
        .brand-icon {
            background-color: #4e73df;
            color: white;
            padding: 0.5rem;
            border-radius: 8px;
            margin-right: 0.75rem;
        }
        .brand-text {
            font-weight: 600;
            color: #2e384d;
        }
        .attendance-btn {
            border-radius: 6px;
            padding: 0.5rem 1rem;
            font-weight: 500;
        }
        .profile-container {
            display: flex;
            align-items: center;
            background-color: #f8f9fc;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }
        .profile-icon {
            color: #4e73df;
            font-size: 1.5rem;
            margin-right: 0.75rem;
        }
        .profile-name {
            font-weight: 600;
            color: #2e384d;
            margin-bottom: 0;
        }
        .profile-role {
            font-size: 0.75rem;
            color: #6c757d;
            margin-bottom: 0;
        }
        .time-display {
            font-weight: 600;
            color: #2e384d;
        }
        .date-display {
            font-size: 0.75rem;
            color: #6c757d;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
        <div class="brand-container">
            <div class="brand-icon">
                <i class="fas fa-users-cog"></i>
            </div>
            <span class="brand-text">WorkWise</span>
        </div>

        @if(auth()->check() && auth()->user()->role === 'employee')
        <div class="mx-auto d-flex align-items-center">
            <div class="btn-group">
                <button class="btn btn-success attendance-btn" id="checkInBtn" onclick="handleAttendance('checkIn')">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Check In
                </button>
                <button class="btn btn-danger attendance-btn" id="checkOutBtn" onclick="handleAttendance('checkOut')">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    Check Out
                </button>
            </div>
        </div>
        @endif

        <div class="d-flex align-items-center ms-auto">
            <div class="text-end me-3">
                <div class="time-display" id="currentTime"></div>
                <small class="date-display" id="currentDate"></small>
            </div>
            <div class="profile-container">
                <i class="fas fa-user-circle profile-icon"></i>
                <div>
                    <p class="profile-name">{{ auth()->user()->name }}</p>
                    <p class="profile-role">{{ ucfirst(auth()->user()->role) }}</p>
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="position-fixed top-0 end-0 p-3" style="z-index: 11">
    <div id="notificationToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let isCheckedIn = false;
let startTime = null;

document.addEventListener('DOMContentLoaded', function() {
    updateCurrentTime();
    setInterval(updateCurrentTime, 1000);
    checkTodayAttendance();

    const toastEl = document.getElementById('notificationToast');
    window.notificationToast = new bootstrap.Toast(toastEl, {
        autohide: true,
        delay: 4000
    });

    // Lock check-in for 10 hours if necessary
    const savedCheckIn = localStorage.getItem('checkInTime');
    if (savedCheckIn) {
        const checkInDate = new Date(savedCheckIn);
        const now = new Date();
        const diffMs = now - checkInDate;
        const hoursPassed = diffMs / (1000 * 60 * 60);

        if (hoursPassed < 10) {
            startTime = checkInDate;
            setCheckedInState();
        } else {
            localStorage.removeItem('checkInTime');
        }
    }
});

function updateCurrentTime() {
    const now = new Date();
    let hours = now.getHours();
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    const ampm = hours >= 12 ? 'PM' : 'AM';

    hours = hours % 12;
    hours = hours ? hours : 12;

    document.getElementById('currentTime').textContent = `${hours}:${minutes}:${seconds} ${ampm}`;
    document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', { 
        weekday: 'short', 
        month: 'short', 
        day: 'numeric' 
    });
}

function checkTodayAttendance() {
    fetch('/api/attendance/today', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        if (data.attendance) {
            const attendance = data.attendance;
            if (attendance.check_in && !attendance.check_out) {
                startTime = new Date(attendance.attendance_date + 'T' + attendance.check_in);
                localStorage.setItem('checkInTime', startTime.toISOString());
                setCheckedInState();
            } else if (attendance.check_out) {
                localStorage.removeItem('checkInTime');
                setCheckedOutState();
            }
        }
    })
    .catch(() => {});
}

function handleAttendance(action) {
    if (action === 'checkIn') checkIn();
    else if (action === 'checkOut') checkOut();
}

function checkIn() {
    const now = new Date();
    
    fetch('/attendance/checkIn', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ 
            user_id: document.querySelector('meta[name="user-id"]').content,
            time: formatTimeForDatabase(now)
        }),
        credentials: 'same-origin'
    })
    .then(res => {
        if (!res.ok) throw new Error('Network response not ok');
        return res.json();
    })
    .then(data => {
        if (data.success) {
            startTime = now;
            localStorage.setItem('checkInTime', now.toISOString());
            setCheckedInState();
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(() => {
        showNotification('Error during check-in. Please try again.', 'error');
    });
}

function checkOut() {
    const now = new Date();
    let workDuration = '';
    
    if (startTime) {
        workDuration = calculateWorkDuration(startTime, now);
    }

    fetch('/attendance/checkOut', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ 
            user_id: document.querySelector('meta[name="user-id"]').content,
            time: formatTimeForDatabase(now)
        }),
        credentials: 'same-origin'
    })
    .then(res => {
        if (!res.ok) throw new Error('Network response not ok');
        return res.json();
    })
    .then(data => {
        if (data.success) {
            localStorage.removeItem('checkInTime');
            setCheckedOutState();
            const message = workDuration ? 
                `${data.message} (Worked: ${workDuration})` : 
                data.message;
            showNotification(message, 'success');
            isCheckedIn = false;
            startTime = null;
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(() => {
        showNotification('Error during check-out. Please try again.', 'error');
    });
}

function setCheckedInState() {
    document.getElementById('checkInBtn').classList.add('disabled');
    document.getElementById('checkOutBtn').classList.remove('disabled');
    isCheckedIn = true;
}

function setCheckedOutState() {
    document.getElementById('checkInBtn').classList.remove('disabled');
    document.getElementById('checkOutBtn').classList.add('disabled');
    isCheckedIn = false;
}

function formatTimeForDatabase(date) {
    return date.toTimeString().split(' ')[0];
}

function calculateWorkDuration(start, end) {
    if (!start || !end) return '0:00';
    let diffMs = end - start;
    let diffHrs = Math.floor(diffMs / 3600000);
    let diffMins = Math.floor((diffMs % 3600000) / 60000);
    return `${diffHrs}:${diffMins.toString().padStart(2, '0')}`;
}

function showNotification(message, type = 'success') {
    const toastEl = document.getElementById('notificationToast');
    const toastBody = toastEl.querySelector('.toast-body');
    toastBody.textContent = message;
    toastEl.classList.remove('bg-success', 'bg-danger', 'bg-warning');
    if (type === 'success') toastEl.classList.add('bg-success');
    else if (type === 'error') toastEl.classList.add('bg-danger');
    else toastEl.classList.add('bg-warning');
    window.notificationToast.show();
}
</script>
</body>
</html>
