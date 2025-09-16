<div class="container mt-4 card mb-4">
    <h2>Thống kê đơn hàng</h2>

    <div class="row mb-3">
        <div class="col-md-3">
            <select id="chart-type" class="form-select">
                <option value="week">Tuần</option>
                <option value="month">Tháng</option>
                <option value="year">Năm</option>
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary" id="btn-refresh">Cập nhật</button>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <canvas id="orderChart" height="100"></canvas>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="alert alert-success">
                <strong>Tổng số đơn hàng: </strong> <span id="total-orders">0</span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="alert alert-info">
                <strong>Tổng doanh thu: </strong> <span id="total-revenue">0</span> đ
            </div>
        </div>
    </div>
</div>



<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
let chart;

function fetchChart(type = 'week') {
    axios.get(`/admin/api/orders/chart?type=${type}`)
        .then(res => {
            const data = res.data;
            console.log(data)

            // Cập nhật tổng số đơn và tổng doanh thu
            document.getElementById('total-orders').innerText = data.total_orders;
            document.getElementById('total-revenue').innerText = new Intl.NumberFormat().format(data.total_revenue);

            // Nếu chart đã tồn tại, destroy trước
            if (chart) chart.destroy();

            const ctx = document.getElementById('orderChart').getContext('2d');
            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Số lượng đơn',
                        data: data.data,
                        backgroundColor: '#0d6efd'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: { mode: 'index', intersect: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        })
        .catch(err => console.error(err));
}

// Khi load trang
document.addEventListener('DOMContentLoaded', () => {
    fetchChart();

    document.getElementById('btn-refresh').addEventListener('click', () => {
        const type = document.getElementById('chart-type').value;
        fetchChart(type);
    });
});
</script>
