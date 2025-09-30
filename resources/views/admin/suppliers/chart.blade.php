
<div class="container card">
    <h2 class="mb-3">Thống kê cung cấp</h2>

    <!-- Bộ lọc -->
    <div class="mb-3">
    <button class="btn btn-outline-secondary me-2 active-btn filter-btn" data-filter="week">Tuần này</button>
    <button class="btn btn-outline-secondary me-2 filter-btn" data-filter="month">Tháng này</button>
    <button class="btn btn-outline-secondary me-2 filter-btn" data-filter="year">Năm nay</button>
    <button class="btn btn-outline-secondary me-2 filter-btn" data-filter="all">Tất cả</button>
</div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title">Tổng số lượng sản phẩm nhập</h5>
                    <canvas id="quantityChart"></canvas>
                    <div id="emptyQuantity" class="text-center text-muted d-none">Không có dữ liệu</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title">Tổng số tiền đã chi</h5>
                    <canvas id="costChart"></canvas>
                    <div id="emptyCost" class="text-center text-muted d-none">Không có dữ liệu</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

let quantityChart, costChart;

function initSupplierCharts() {
    quantityChart = new Chart(document.getElementById('quantityChart'), {
        type: 'pie',
        data: { labels: [], datasets: [{ data: [], backgroundColor: genColors() }] }
    });

    costChart = new Chart(document.getElementById('costChart'), {
        type: 'pie',
        data: { labels: [], datasets: [{ data: [], backgroundColor: genColors() }] }
    });
}

function genColors() {
    return ['#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF','#FF9F40','#2ecc71','#e74c3c'];
}

function loadSupplierData(filter = 'all') {
    fetch(`/admin/suppliers/chart?filter=${filter}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.json())
        .then(data => {
            // Update chart số lượng
            quantityChart.data.labels = data.labels;
            quantityChart.data.datasets[0].data = data.quantities;
            quantityChart.update();

            // Update chart chi phí
            costChart.data.labels = data.labels;
            costChart.data.datasets[0].data = data.costs;
            costChart.update();

            // Hiển thị text nếu trống
            document.getElementById('emptyQuantity').classList.toggle('d-none', data.quantities.some(v => v > 0));
            document.getElementById('emptyCost').classList.toggle('d-none', data.costs.some(v => v > 0));
        });
}

document.addEventListener("DOMContentLoaded", function(){
    initSupplierCharts();
    loadSupplierData(); // mặc định all

    document.querySelectorAll(".filter-btn").forEach(btn => {
    btn.addEventListener("click", function(){
        document.querySelectorAll(".filter-btn").forEach(b => b.classList.remove("active-btn"));
        this.classList.add("active-btn");
        loadSupplierData(this.dataset.filter);
    });
});
});

</script>

<style>
.active-btn {
    background-color: #6c757d !important; /* xám Bootstrap secondary */
    color: #fff !important;
    border-color: #6c757d !important;
}
</style>

