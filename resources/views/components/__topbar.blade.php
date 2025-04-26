<nav class="navbar navbar-header navbar-expand-lg shadow-sm" data-background-color="light" style="height: 70px">
    <div class="container-fluid d-flex justify-content-between align-items-center px-4">
        <!-- Attendance & Clock Section -->
        <div class="d-flex align-items-center">
            <div class="btn-group" role="group">
                <button class="btn btn-success rounded-start-pill topbar-btn" id="checkInBtn">
                    <i class="fas fa-sign-in-alt me-2"></i>Check In
                </button>
                <button class="btn btn-warning rounded-0 topbar-btn" id="breakBtn">
                    <i class="fas fa-coffee me-2"></i>Break
                </button>
                <button class="btn btn-danger rounded-end-pill topbar-btn" id="checkOutBtn">
                    <i class="fas fa-sign-out-alt me-2"></i>Check Out
                </button>
            </div>
            
            <!-- Status Message Section -->
        <div class="d-flex align-items-center">
            <div class="alert alert-light py-1 px-3 mb-0 border d-flex align-items-center" id="statusAlert">
                <i class="fas fa-info-circle me-2"></i>
                <span id="statusMessage" class="small fw-bold">Status: Ready</span>
            </div>
        </div>
            
            <div class="ms-3 border-start ps-3 d-flex align-items-center">
                <i class="fas fa-clock me-2 text-primary"></i>
                <span id="liveClock" class="fw-bold fs-6 text-dark"></span>
            </div>
        </div>

        <!-- Employee Profile Section -->
        <div class="d-flex align-items-center">
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="me-3 text-end">
                        <h6 class="mb-0 fw-bold text-dark">{{ auth()->user()->name }}</h6>
                        <span class="badge bg-primary">{{ ucfirst(auth()->user()->role) }}</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
    // Enhanced live clock functionality
    function updateClock() {
        const now = new Date();
        const options = { 
            weekday: 'short', 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit',
            hour12: true
        };
        const timeString = now.toLocaleTimeString('en-US', options);

        document.getElementById('liveClock').innerHTML = `
            <span class="time">${timeString}</span>
        `;
        
        setTimeout(updateClock, 1000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateClock();

        // Enhanced status functionality
        const statusMessage = document.getElementById('statusMessage');
        const statusAlert = document.getElementById('statusAlert');

        // Check In Button
        document.getElementById('checkInBtn').addEventListener('click', function() {
            statusMessage.textContent = "Checked In at " + new Date().toLocaleTimeString();
            statusAlert.className = 'alert alert-success py-1 px-3 mb-0 border d-flex align-items-center';
        });

        // Check Out Button
        document.getElementById('checkOutBtn').addEventListener('click', function() {
            statusMessage.textContent = "Checked Out at " + new Date().toLocaleTimeString();
            statusAlert.className = 'alert alert-danger py-1 px-3 mb-0 border d-flex align-items-center';
        });

        // Break Button
        document.getElementById('breakBtn').addEventListener('click', function() {
            statusMessage.textContent = "On Break since " + new Date().toLocaleTimeString();
            statusAlert.className = 'alert alert-warning py-1 px-3 mb-0 border d-flex align-items-center';
        });
    });
</script>

<style>
    .topbar-btn {
        padding: 0.375rem 1rem;
        font-size: 0.85rem;
        transition: all 0.2s;
    }
    
    .topbar-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .avatar {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    #statusAlert {
        transition: all 0.3s ease;
        min-width: 200px;
    }
    
    #liveClock .time {
        font-weight: 600;
    }
    
    #liveClock .date {
        font-size: 0.75rem;
        margin-left: 8px;
    }
    
    .dropdown-menu {
        border: none;
        min-width: 200px;
    }
    
    .dropdown-item {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
</style>