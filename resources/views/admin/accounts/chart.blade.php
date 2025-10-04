<div class="container mt-4 card mb-4">
    <h2>Thống kê tài khoản</h2>

    <div class="row">
        {{-- Tổng quan --}}
        <div class="col-md-6">
            <div class="card p-2">
                <div class="alert alert-success mb-2">
                    <strong>Tổng tài khoản: </strong> <span id="total-users">0</span>
                </div>
                <div class="row">
                    <div class="col-6"><div class="alert alert-primary mb-2"><strong>Nam: </strong><span id="male-users">0</span></div></div>
                    <div class="col-6"><div class="alert alert-danger mb-2"><strong>Nữ: </strong><span id="female-users">0</span></div></div>
                    <div class="col-6"><div class="alert alert-warning mb-2"><strong>Khác: </strong><span id="other-users">0</span></div></div>
                    <div class="col-6"><div class="alert alert-info mb-2"><strong>Hoạt động: </strong><span id="active-users">0</span></div></div>
                </div>
            </div>
        </div>
<!-- 
        {{-- Biểu đồ tròn giới tính --}}
        <div class="col-md-6">
            <div class="card p-2">
                <canvas id="genderChart" height="70"></canvas>
            </div>
        </div> -->
    </div>

    <div class="row mt-3">
        {{-- Biểu đồ cột độ tuổi --}}
        <div class="col-md-6">
            <div class="card-body p-2">
                
                <canvas id="ageChart" height="150"></canvas>
            </div>
        </div>

        {{-- Biểu đồ đường đăng ký --}}
        <div class="col-md-6">
            <div class="card-body p-2">
                <canvas id="registerChart" height="150"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let genderChart, ageChart, registerChart;

function initAccountCharts(data) {
    // Update text
    document.getElementById("total-users").innerText = data.total_users;
    document.getElementById("male-users").innerText = data.gender_counts.male;
    document.getElementById("female-users").innerText = data.gender_counts.female;
    document.getElementById("other-users").innerText = data.gender_counts.other;
    document.getElementById("active-users").innerText = data.active_users;

    // Pie chart: Gender
    genderChart = new Chart(document.getElementById('genderChart'), {
        type: 'pie',
        data: {
            labels: ['Nam','Nữ','Khác'],
            datasets: [{
                data: [data.gender_counts.male, data.gender_counts.female, data.gender_counts.other],
                backgroundColor: ['#3498db','#e74c3c','#f39c12']
            }]
        }
    });

    // Bar chart: Age
    ageChart = new Chart(document.getElementById('ageChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(data.age_distribution),
            datasets: [{
                label: 'Số lượng',
                data: Object.values(data.age_distribution),
                backgroundColor: '#2ecc71'
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Phân bố độ tuổi',
                    font: { size: 16, weight: 'bold' }
                }
            },
            scales: {
                y: {
                    ticks: {
                        callback: function(value) {
                            return Number.isInteger(value) ? value : null; // chỉ hiển thị số nguyên
                        }
                    }
                },
                x: {
                    ticks: {
                        callback: function(value, index) {
                            return this.getLabelForValue(value).replace('.0', ''); // bỏ .0 nếu có
                        }
                    }
                }
            }
        }
    });

    // Line chart: Register per month
    registerChart = new Chart(document.getElementById('registerChart'), {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Đăng ký',
                data: data.register_series,
                borderColor: '#9b59b6',
                fill: false,
                tension: 0.3
            }]
        },
        options:{
            plugins: {
                title: {
                    display: true,
                    text: 'Số lượng đăng ký trong năm',
                    font: { size: 16, weight: 'bold' }
                }
            },
        }
    });
}

fetch("{{ route('admin.chart.accounts') }}")
    .then(res => res.json())
    .then(data => initAccountCharts(data));
</script>
