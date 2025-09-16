<div class="card mb-4">
    <h2 class="card-header">Tỷ lệ sản phẩm đã bán</h2>
    <div class="card-body">
        <canvas id="productPieChart" ></canvas>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    axios.get( `/admin/api/chart/productbuy`)
        .then(res => {
           
            const ctx = document.getElementById('productPieChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: res.data.labels,
                    datasets: [{
                        data: res.data.data,
                        backgroundColor: ['#0d6efd', '#ffc107']
                    }]
                },
                options: {
                    responsive: true
                }
            });
        });
});
</script>
