<template>
  <div class="card shadow-sm p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5>Thống kê đơn hàng</h5>
      <select v-model="filterType" @change="fetchData" class="form-select w-auto">
        <option value="week">Tuần</option>
        <option value="month">Tháng</option>
        <option value="year">Năm</option>
      </select>
    </div>

    <canvas ref="chartCanvas" height="150"></canvas>

    <div class="mt-3 d-flex justify-content-around">
      <div>
        <strong>Tổng đơn hàng:</strong> {{ totalOrders }}
      </div>
      <div>
        <strong>Tổng doanh thu:</strong> {{ formatPrice(totalRevenue) }} đ
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import { Chart, BarElement, CategoryScale, LinearScale, Tooltip, Legend } from 'chart.js';

Chart.register(BarElement, CategoryScale, LinearScale, Tooltip, Legend);

const chartCanvas = ref(null);
let chartInstance = null;

const filterType = ref('week');
const totalOrders = ref(0);
const totalRevenue = ref(0);

const formatPrice = (val) => new Intl.NumberFormat('vi-VN').format(val);

const fetchData = async () => {
  try {
    const res = await axios.get(`/admin/api/orders/chart?type=${filterType.value}`);
    const { labels, data, total_orders, total_revenue } = res.data;

    totalOrders.value = total_orders;
    totalRevenue.value = total_revenue;

    if (chartInstance) chartInstance.destroy();

    chartInstance = new Chart(chartCanvas.value, {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          label: 'Số lượng đơn',
          data,
          backgroundColor: 'rgba(54, 162, 235, 0.6)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: { beginAtZero: true, stepSize: 1 }
        }
      }
    });
  } catch (err) {
    console.error("Lỗi lấy dữ liệu chart:", err);
  }
};

onMounted(fetchData);
</script>
