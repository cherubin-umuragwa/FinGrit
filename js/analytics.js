document.addEventListener('DOMContentLoaded', function () {
    if (typeof window.analyticsData !== 'undefined') {
        initCharts();
    } else {
        console.error('Analytics data not found');
    }
});

function initCharts() {
    initIncomeExpenseChart();
    initCategoryChart();
}

// Income vs Expenses Bar Chart
function initIncomeExpenseChart() {
    const options = {
        chart: {
            type: 'bar',
            height: 350,
            toolbar: { show: false }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                borderRadius: 6
            }
        },
        dataLabels: { enabled: false },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        series: [
            {
                name: 'Income',
                data: window.analyticsData.monthly.income
            },
            {
                name: 'Expenses',
                data: window.analyticsData.monthly.expenses
            }
        ],
        xaxis: {
            categories: window.analyticsData.monthly.labels
        },
        yaxis: {
            title: { text: 'Amount ($)' }
        },
        fill: {
            opacity: 1,
            colors: ['#1e4972', '#e74c3c']
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return "$" + val.toLocaleString();
                }
            }
        }
    };

    const chart = new ApexCharts(document.querySelector("#incomeExpenseChart"), options);
    chart.render();
}

// Spending by Category Donut Chart
function initCategoryChart() {
    const options = {
        chart: {
            type: 'donut',
            height: 350
        },
        labels: window.analyticsData.categories.labels,
        series: window.analyticsData.categories.data,
        colors: ['#1e4972', '#ff5622', '#27ae60', '#f39c12', '#9b59b6'],
        legend: {
            position: 'bottom'
        },
        dataLabels: {
            formatter: function (val, opts) {
                return val.toFixed(1) + "%";
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return "$" + val.toLocaleString();
                }
            }
        }
    };

    const chart = new ApexCharts(document.querySelector("#categoryChart"), options);
    chart.render();
}


// Handle chart actions (download, expand, etc.)
function setupChartActions() {
    const downloadButtons = document.querySelectorAll('.chart-actions button:first-child');
    const expandButtons = document.querySelectorAll('.chart-actions button:last-child');
    
    downloadButtons.forEach(button => {
        button.addEventListener('click', function() {
            const chartId = this.closest('.chart-container').querySelector('canvas').id;
            downloadChart(chartId);
        });
    });
    
    expandButtons.forEach(button => {
        button.addEventListener('click', function() {
            const chartContainer = this.closest('.chart-container');
            toggleFullscreen(chartContainer);
        });
    });
}

// Download chart as image
function downloadChart(chartId) {
    const chartCanvas = document.getElementById(chartId);
    const link = document.createElement('a');
    link.href = chartCanvas.toDataURL('image/png');
    link.download = `${chartId}-${new Date().toISOString().slice(0, 10)}.png`;
    link.click();
}

// Toggle fullscreen mode for chart
function toggleFullscreen(element) {
    if (!document.fullscreenElement) {
        if (element.requestFullscreen) {
            element.requestFullscreen();
        } else if (element.webkitRequestFullscreen) {
            element.webkitRequestFullscreen();
        } else if (element.msRequestFullscreen) {
            element.msRequestFullscreen();
        }
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
    }
}

// Initialize chart actions when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    setupChartActions();
});

// Handle responsive behavior
function handleResponsive() {
    const sidebar = document.getElementById('sidebar');
    const hamburger = document.getElementById('hamburger');
    
    if (window.innerWidth <= 768) {
        // Mobile view
        if (sidebar) {
            sidebar.classList.remove('collapsed');
        }
    } else if (window.innerWidth <= 992) {
        // Tablet view
        if (sidebar) {
            sidebar.classList.add('collapsed');
        }
    } else {
        // Desktop view
        if (sidebar) {
            sidebar.classList.remove('collapsed');
        }
    }
    
    // Hamburger menu toggle
    if (hamburger && sidebar) {
        hamburger.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });
    }
}

// Initialize responsive behavior
window.addEventListener('load', handleResponsive);
window.addEventListener('resize', handleResponsive);

// Additional utility functions
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

// Export functions for use in other modules (if needed)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        initCharts,
        initIncomeExpenseChart,
        initCategoryChart,
        setupChartActions,
        downloadChart,
        toggleFullscreen,
        handleResponsive,
        formatCurrency
    };
}