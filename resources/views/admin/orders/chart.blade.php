

<div class="container mt-4 card mb-4">
    <h2>Thống kê đơn hàng</h2>

    <!-- Các button -->
    <div class="row mb-3">
        <div class="col-md-12">
            <button class="btn btn-outline-secondary me-2" onclick="setActive(this); loadOrderData('week')">Tuần này</button>
            <button class="btn btn-outline-secondary me-2" onclick="setActive(this); loadOrderData('lastWeek')">Tuần trước</button>
            <button class="btn btn-outline-secondary me-2" onclick="setActive(this); loadOrderData('month')">Tháng này</button>
            <button class="btn btn-outline-secondary me-2" onclick="setActive(this); loadOrderData('lastMonth')">Tháng trước</button>
            <button class="btn btn-outline-secondary me-2  active-btn" onclick="setActive(this); loadOrderData('year')">Năm nay</button>
            <button class="btn btn-outline-secondary me-2" onclick="setActive(this); loadOrderData('lastYear')">Năm trước</button>
            <!-- <button class="btn btn-outline-secondary me-2" onclick="setActive(this); loadOrderData('all')">Tất cả</button> -->
        </div>
    </div>

    <!-- Row: Tổng đơn + doanh thu (trái) và Biểu đồ tròn (phải) -->
    <div class="row">
        <!-- Div tổng đơn + doanh thu + trạng thái -->
        <div class="col-md-6">
            <div class="card p-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="alert alert-success mb-2">
                            <strong>Tổng đơn hoàn thành: </strong> 
                            <span id="completed-orders">0</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-info mb-2">
                            <strong>Tổng doanh thu: </strong> 
                            <span id="total-revenue">0</span> đ
                        </div>
                    </div>
                </div>
                <!-- Thêm thông tin số lượng theo trạng thái -->
                <div class="row mt-2">
                    <div class="col-md-6">
                        <div class="alert alert-warning mb-2">
                            <strong>Chờ thanh toán: </strong> 
                            <span id="pending-orders">0</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-primary mb-2">
                            <strong>Đang xử lý: </strong> 
                            <span id="processing-orders">0</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-secondary mb-2">
                            <strong>Đang giao: </strong> 
                            <span id="shipping-orders">0</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-dark mb-2">
                            <strong>Đổi trả: </strong> 
                            <span id="returned-orders">0</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-danger mb-2">
                            <strong>Đã hủy: </strong> 
                            <span id="canceled-orders">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Div biểu đồ tròn -->
        <div class="card p-3 position-relative col-md-6">
            <canvas id="statusChart" height="150"></canvas>
            <div id="statusChartEmpty" class="text-center text-muted position-absolute top-50 start-50 translate-middle d-none">
                Không có dữ liệu
            </div>
        </div>
    </div>

    <!-- Biểu đồ đường đơn hàng -->
    <div class="card-body position-relative">
        <canvas id="orderChart" height="250"></canvas>
        <div id="orderChartEmpty" class="text-center text-muted position-absolute top-50 start-50 translate-middle d-none">
            Không có dữ liệu
        </div>
    </div>
    <!-- Biểu đồ cột doanh thu -->
    <div class="card-body position-relative mt-3">
        <canvas id="revenueChart" height="250"></canvas>
        <div id="revenueChartEmpty" class="text-center text-muted position-absolute top-50 start-50 translate-middle d-none">
            Không có dữ liệu
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

let orderChart, statusChart,revenueChart;

function initOrderCharts() {
    const ctx1 = document.getElementById('orderChart');
    const ctx2 = document.getElementById('statusChart');
    const ctx3 = document.getElementById('revenueChart');

    // Biểu đồ đường
    orderChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                { label: 'Chờ thanh toán', data: [], borderColor: '#f39c12', fill: false, tension: 0.3 },
                { label: 'Đang xử lý', data: [], borderColor: '#3498db', fill: false, tension: 0.3 },
                { label: 'Đang giao', data: [], borderColor: '#9b3cc0', fill: false, tension: 0.3 },
                { label: 'Hoàn tất', data: [], borderColor: '#2ecc71', fill: false, tension: 0.3 },
                { label: 'Đổi trả', data: [], borderColor: '#eb8f3e', fill: false, tension: 0.3 },
                { label: 'Đã hủy', data: [], borderColor: '#dd210c', fill: false, tension: 0.3 }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } },
            scales: {
                x: { title: { display: true, text: 'Ngày' } },
                y: { beginAtZero: true, title: { display: true, text: 'Số đơn' }, ticks: { stepSize: 1 } }
            }
        }
    });

    // Biểu đồ tròn
    statusChart = new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: ['Chờ thanh toán', 'Đang xử lý', 'Đang giao', 'Hoàn tất', 'Đổi trả', 'Đã hủy'],
            datasets: [{
                data: [0, 0, 0, 0, 0, 0],
                backgroundColor: ['#f39c12','#3498db','#9b3cc0','#2ecc71','#eb8f3e','#dd210c']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
    //biểu đồ cột
    revenueChart = new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Doanh thu',
                data: [],
                backgroundColor: '#2ecc71'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { title: { display: true, text: 'Thời gian' } },
                y: { beginAtZero: true, title: { display: true, text: 'Doanh thu (VNĐ)' } }
            }
        }
    });
}

function loadOrderData(type) {
    fetch(`/admin/api/chart/orders?type=${type}`)
        .then(res => res.json())
        .then(data => {
            if (data.no_data) {
                resetUI();
                return;
            }

            // update chart đường theo từng trạng thái
            orderChart.data.labels = data.labels;
            orderChart.data.datasets[0].data = data.series.pending || [];
            orderChart.data.datasets[1].data = data.series.processing || [];
            orderChart.data.datasets[2].data = data.series.shipping || [];
            orderChart.data.datasets[3].data = data.series.completed || [];
            orderChart.data.datasets[4].data = data.series.returned || [];
            orderChart.data.datasets[5].data = data.series.canceled || [];
            orderChart.update();

            // update chart tròn
            statusChart.data.datasets[0].data = [
                data.status_counts.pending || 0,
                data.status_counts.processing || 0,
                data.status_counts.shipping || 0,
                data.status_counts.completed || 0,
                data.status_counts.returned || 0,
                data.status_counts.canceled || 0
            ];
            statusChart.update();

            // update bar chart (doanh thu)
            revenueChart.data.labels = data.labels;
            revenueChart.data.datasets[0].data = data.revenue_series || [];
            revenueChart.update();


            // update số liệu text
            document.getElementById("completed-orders").innerText = data.status_counts.completed || 0;
            document.getElementById("total-revenue").innerText = data.total_revenue.toLocaleString();
            document.getElementById("pending-orders").innerText = data.status_counts.pending || 0;
            document.getElementById("processing-orders").innerText = data.status_counts.processing || 0;
            document.getElementById("shipping-orders").innerText = data.status_counts.shipping || 0;
            document.getElementById("returned-orders").innerText = data.status_counts.returned || 0;
            document.getElementById("canceled-orders").innerText = data.status_counts.canceled || 0;

            // ẩn overlay
            document.getElementById("orderChartEmpty").classList.add("d-none");
            document.getElementById("statusChartEmpty").classList.add("d-none");
             ["orderChartEmpty", "statusChartEmpty", "revenueChartEmpty"].forEach(id => {
                document.getElementById(id).classList.add("d-none");
            });
        });
}

function resetUI() {
    ["completed-orders","total-revenue","pending-orders","processing-orders","shipping-orders","returned-orders","canceled-orders"]
        .forEach(id => document.getElementById(id).innerText = 0);

    // reset line chart
    orderChart.data.labels = [];
    orderChart.data.datasets.forEach(ds => ds.data = []);
    orderChart.update();

    // reset pie chart
    statusChart.data.datasets[0].data = [];
    statusChart.update();

    // reset bar chart
    revenueChart.data.labels = [];
    revenueChart.data.datasets[0].data = [];
    revenueChart.update();

    ["orderChartEmpty", "statusChartEmpty", "revenueChartEmpty"].forEach(id => {
        document.getElementById(id).classList.remove("d-none");
    });
}

function setActive(button) {
    document.querySelectorAll(".btn-outline-secondary").forEach(btn => btn.classList.remove("active-btn"));
    button.classList.add("active-btn");
}

initOrderCharts();
loadOrderData('year');
</script>
<style>
    .active-btn {
    background-color: #6c757d !important; /* cùng màu với hover */
    color: #fff !important;
    border-color: #6c757d !important;
}
</style>


