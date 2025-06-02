// Dashboard data fetching and visualization

// Initialize charts
let revenueChart = null;
let ratingChart = null;

console.log('dashboard.js loaded');

// Fetch dashboard data
async function fetchDashboardData() {
    try {
        // Fetch stats
        const statsResponse = await fetch('../../pages/api/dashboard_api.php?action=stats');
        const statsData = await statsResponse.json();
        console.log('Stats API response:', statsData);
        if (statsData.success) {
            updateStats(statsData.data);
        }

        // Fetch revenue data
        const revenueResponse = await fetch('../../pages/api/dashboard_api.php?action=revenue');
        const revenueData = await revenueResponse.json();
        console.log('Revenue API response:', revenueData);
        if (revenueData.success) {
            updateRevenueChart(revenueData.data);
        }

        // Fetch ratings data
        const ratingsResponse = await fetch('../../pages/api/dashboard_api.php?action=ratings');
        const ratingsData = await ratingsResponse.json();
        console.log('Ratings API response:', ratingsData);
        if (ratingsData.success) {
            updateRatingChart(ratingsData.data);
        }

        // Fetch activities
        const activitiesResponse = await fetch('../../pages/api/dashboard_api.php?action=activities');
        const activitiesData = await activitiesResponse.json();
        console.log('Activities API response:', activitiesData);
        if (activitiesData.success) {
            updateActivities(activitiesData.data);
        }

        // Fetch top hotels
        const topHotelsResponse = await fetch('../../pages/api/dashboard_api.php?action=top_hotels');
        const topHotelsData = await topHotelsResponse.json();
        console.log('Top Hotels API response:', topHotelsData);
        if (topHotelsData.success) {
            updateTopHotels(topHotelsData.data);
        }
    } catch (error) {
        console.error('Error fetching dashboard data:', error);
        showErrorAlert('Failed to load dashboard data. Please try again later.');
    }
}

// Update statistics cards
function updateStats(stats) {
    document.getElementById('totalHotels').textContent = stats.total_hotels;
    document.getElementById('totalUsers').textContent = stats.total_users;
    document.getElementById('activeHotels').textContent = stats.active_hotels;
    document.getElementById('pendingReviews').textContent = stats.pending_reviews;
}

// Update revenue chart
function updateRevenueChart(data) {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    if (revenueChart) {
        revenueChart.destroy();
    }

    const labels = data.map(item => {
        const [year, month] = item.month.split('-');
        return new Date(year, month - 1).toLocaleDateString('default', { month: 'short', year: 'numeric' });
    });

    revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Revenue',
                data: data.map(item => item.revenue),
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => 'EGP ' + value.toLocaleString()
                    }
                }
            }
        }
    });
}

// Update rating distribution chart
function updateRatingChart(data) {
    const ctx = document.getElementById('ratingChart').getContext('2d');
    
    if (ratingChart) {
        ratingChart.destroy();
    }

    ratingChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(item => item.rating + ' ★'),
            datasets: [{
                data: data.map(item => item.count),
                backgroundColor: '#0d6efd',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}

// Update recent activities
function updateActivities(activities) {
    const container = document.getElementById('recentActivities');
    
    if (!activities.length) {
        container.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                <p class="mb-0 text-muted">No recent activities</p>
            </div>
        `;
        return;
    }

    const html = activities.map(activity => `
        <div class="timeline-item">
            <div class="timeline-icon ${activity.type === 'booking' ? 'success' : 'info'}">
                <i class="fas fa-${activity.type === 'booking' ? 'calendar-check' : 'star'}"></i>
            </div>
            <div class="timeline-content">
                <p class="mb-0">${activity.description}</p>
                <small class="text-muted">${new Date(activity.created_at).toLocaleString()}</small>
            </div>
        </div>
    `).join('');

    container.innerHTML = html;
}

// Update top hotels
function updateTopHotels(hotels) {
    const container = document.getElementById('topHotels');
    if (!hotels.length) {
        container.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-hotel fa-2x text-muted mb-2"></i>
                <p class="mb-0 text-muted">No hotels found</p>
            </div>
        `;
        return;
    }
    const html = `<div class="top-hotels-list">` + hotels.map(hotel => `
        <div class="hotel-entry d-flex align-items-center mb-3">
            <div class="hotel-info ms-3 flex-grow-1">
                <strong class="hotel-name">${hotel.name}</strong>
                <div class="rating text-warning mb-1">${'★'.repeat(Math.round(hotel.rating))}</div>
                <small class="text-muted">${hotel.bookings} bookings</small>
            </div>
        </div>
    `).join('') + `</div>`;
    container.innerHTML = html;
}

// Show error alert
function showErrorAlert(message) {
    const alertHtml = `
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);
}

// Initialize dashboard
document.addEventListener('DOMContentLoaded', fetchDashboardData); 