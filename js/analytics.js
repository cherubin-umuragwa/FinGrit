// Store chart instances and configuration
const chartInstances = {};
const chartConfig = {
    defaultColors: ['#1e4972', '#e74c3c', '#27ae60', '#f39c12', '#9b59b6', '#8e44ad', '#34495e'],
    downloadOptions: {
        scale: 2,
        width: 1200,
        height: 800
    }
};

// Initialize everything when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, checking for analytics data...');
    
    if (typeof window.analyticsData !== 'undefined') {
        console.log('Analytics data found:', window.analyticsData);
        initCharts();
        setupChartActions();
        handleResponsive();
    } else {
        console.error('Analytics data not found');
        showDataError();
    }
});

// Show error message if data is missing
function showDataError() {
    const chartContainers = document.querySelectorAll('[id$="Chart"]');
    chartContainers.forEach(container => {
        container.innerHTML = `
            <div style="display: flex; align-items: center; justify-content: center; height: 350px; color: #666; font-size: 16px;">
                <div style="text-align: center;">
                    <div style="font-size: 48px; margin-bottom: 16px;">📊</div>
                    <div>Analytics data is not available</div>
                    <div style="font-size: 14px; margin-top: 8px;">Please ensure the data is loaded before initializing charts</div>
                </div>
            </div>
        `;
    });
}

// Initialize all charts
function initCharts() {
    try {
        initIncomeExpenseChart();
        initCategoryChart();
        console.log('All charts initialized successfully');
    } catch (error) {
        console.error('Error initializing charts:', error);
    }
}

// Income vs Expenses Bar Chart
function initIncomeExpenseChart() {
    const chartElement = document.querySelector("#incomeExpenseChart");
    if (!chartElement) {
        console.warn('Income/Expense chart container not found');
        return;
    }

    const options = {
        chart: {
            type: 'bar',
            height: 350,
            toolbar: { 
                show: false // Completely hide the toolbar including the three-bar icon
            },
            id: 'incomeExpenseChart',
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                borderRadius: 6,
                borderRadiusApplication: 'end',
                borderRadiusWhenStacked: 'last'
            }
        },
        dataLabels: { 
            enabled: false 
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        series: [
            {
                name: 'Income',
                data: window.analyticsData?.monthly?.income || []
            },
            {
                name: 'Expenses',
                data: window.analyticsData?.monthly?.expenses || []
            }
        ],
        xaxis: {
            categories: window.analyticsData?.monthly?.labels || [],
            axisBorder: {
                show: true
            },
            axisTicks: {
                show: true
            }
        },
        yaxis: {
            title: { 
                text: 'Amount ($)',
                style: {
                    fontSize: '14px',
                    fontWeight: 600
                }
            },
            labels: {
                formatter: function(val) {
                    return '$' + val.toLocaleString();
                }
            }
        },
        fill: {
            opacity: 1,
            colors: chartConfig.defaultColors
        },
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function(val) {
                    return "$" + val.toLocaleString();
                }
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'left'
        },
        grid: {
            borderColor: '#f1f1f1',
            strokeDashArray: 4
        }
    };

    try {
        const chart = new ApexCharts(chartElement, options);
        chart.render();
        chartInstances.incomeExpenseChart = chart;
        console.log('Income/Expense chart created successfully');
    } catch (error) {
        console.error('Error creating Income/Expense chart:', error);
    }
}

// Spending by Category Donut Chart
function initCategoryChart() {
    const chartElement = document.querySelector("#categoryChart");
    if (!chartElement) {
        console.warn('Category chart container not found');
        return;
    }

    const options = {
        chart: {
            type: 'donut',
            height: 350,
            id: 'categoryChart',
            toolbar: { 
                show: false // Completely hide the toolbar including the three-bar icon
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        labels: window.analyticsData?.categories?.labels || [],
        series: window.analyticsData?.categories?.data || [],
        colors: chartConfig.defaultColors,
        legend: {
            position: 'bottom',
            horizontalAlign: 'center'
        },
        dataLabels: {
            enabled: true,
            formatter: function(val, opts) {
                return val.toFixed(1) + "%";
            },
            style: {
                fontSize: '14px',
                fontFamily: 'inherit',
                fontWeight: 600
            }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '70%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            formatter: function (w) {
                                const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                return '$' + total.toLocaleString();
                            }
                        }
                    }
                }
            }
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return "$" + val.toLocaleString();
                }
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    height: 300
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    try {
        const chart = new ApexCharts(chartElement, options);
        chart.render();
        chartInstances.categoryChart = chart;
        console.log('Category chart created successfully');
    } catch (error) {
        console.error('Error creating Category chart:', error);
    }
}

// Set up chart action buttons
function setupChartActions() {
    console.log('Setting up chart actions...');
    
    // Download buttons
    const downloadButtons = document.querySelectorAll('.download-chart');
    console.log('Found download buttons:', downloadButtons.length);
    
    downloadButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const chartId = this.getAttribute('data-chart-id');
            console.log('Download button clicked for chart:', chartId);
            downloadChart(chartId);
        });
    });
    
    // Fullscreen buttons
    const fullscreenButtons = document.querySelectorAll('.expand-chart');
    console.log('Found fullscreen buttons:', fullscreenButtons.length);
    
    fullscreenButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const chartContainer = this.closest('.chart-container');
            toggleFullscreen(chartContainer);
        });
    });
    
    // Add fullscreen change listener
    document.addEventListener('fullscreenchange', handleFullscreenChange);
    document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
    document.addEventListener('msfullscreenchange', handleFullscreenChange);
}

// Handle fullscreen changes
function handleFullscreenChange() {
    // Resize charts when exiting fullscreen
    if (!document.fullscreenElement) {
        setTimeout(() => {
            Object.values(chartInstances).forEach(chart => {
                if (chart && typeof chart.resize === 'function') {
                    chart.resize();
                }
            });
        }, 100);
    }
}

// Enhanced download chart functionality
function downloadChart(chartId) {
    console.log('Attempting to download chart:', chartId);
    
    const chart = chartInstances[chartId];
    if (!chart) {
        console.error('Chart instance not found:', chartId);
        showErrorMessage('Chart not found. Please refresh and try again.');
        return;
    }

    // Show download options menu
    showDownloadMenu(chartId, chart);
}

// Show download options menu
function showDownloadMenu(chartId, chart) {
    // Remove existing menu if present
    const existingMenu = document.getElementById('download-menu');
    if (existingMenu) {
        existingMenu.remove();
    }

    // Create download menu
    const menu = document.createElement('div');
    menu.id = 'download-menu';
    menu.style.cssText = `
        position: fixed;
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        z-index: 10000;
        min-width: 150px;
        padding: 8px 0;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    `;

    // Download options - only keeping download options
    const options = [
        { label: 'Download PNG', action: () => downloadAs(chartId, 'png') },
        { label: 'Download SVG', action: () => downloadAs(chartId, 'svg') },
        { label: 'Download CSV', action: () => downloadData(chartId, 'csv') },
        { label: 'Download JSON', action: () => downloadData(chartId, 'json') }
    ];

    options.forEach(option => {
        const item = document.createElement('div');
        item.style.cssText = `
            padding: 8px 16px;
            cursor: pointer;
            font-size: 14px;
            color: #333;
            transition: background-color 0.2s;
        `;
        item.textContent = option.label;
        
        item.addEventListener('mouseenter', () => {
            item.style.backgroundColor = '#f8f9fa';
        });
        
        item.addEventListener('mouseleave', () => {
            item.style.backgroundColor = 'transparent';
        });
        
        item.addEventListener('click', () => {
            option.action();
            menu.remove();
        });
        
        menu.appendChild(item);
    });

    // Position menu near the download button
    const downloadBtn = document.querySelector(`[data-chart-id="${chartId}"]`);
    if (downloadBtn) {
        const rect = downloadBtn.getBoundingClientRect();
        menu.style.top = (rect.bottom + 5) + 'px';
        menu.style.left = (rect.left- 76)+ 'px';
    } else {
        menu.style.top = '50px';
        menu.style.right = '20px';
    }

    document.body.appendChild(menu);

    // Close menu when clicking outside
    const closeMenu = (e) => {
        if (!menu.contains(e.target)) {
            menu.remove();
            document.removeEventListener('click', closeMenu);
        }
    };
    
    setTimeout(() => {
        document.addEventListener('click', closeMenu);
    }, 100);
}

// Download chart in specific format
function downloadAs(chartId, format) {
    const chart = chartInstances[chartId];
    if (!chart) return;

    showDownloadProgress(chartId, `Preparing ${format.toUpperCase()} download...`);

    if (format === 'png') {
        // High quality PNG download
        if (typeof chart.dataURI === 'function') {
            chart.dataURI({
                scale: 2,
                width: 1200,
                height: 800
            }).then((dataUrl) => {
                hideDownloadProgress(chartId);
                triggerDownload(dataUrl, chartId, 'png');
            }).catch(() => {
                hideDownloadProgress(chartId);
                convertSvgToImage(chartId);
            });
        } else {
            hideDownloadProgress(chartId);
            convertSvgToImage(chartId);
        }
    } else if (format === 'svg') {
        // SVG download
        const chartElement = document.getElementById(chartId);
        const svgElement = chartElement?.querySelector('svg');
        
        if (svgElement) {
            try {
                const svgClone = svgElement.cloneNode(true);
                const rect = svgElement.getBoundingClientRect();
                svgClone.setAttribute('width', rect.width);
                svgClone.setAttribute('height', rect.height);
                
                const svgXml = new XMLSerializer().serializeToString(svgClone);
                const svgBlob = new Blob([svgXml], { type: 'image/svg+xml;charset=utf-8' });
                const url = URL.createObjectURL(svgBlob);
                
                hideDownloadProgress(chartId);
                triggerDownload(url, chartId, 'svg');
            } catch (error) {
                hideDownloadProgress(chartId);
                showErrorMessage('SVG download failed');
            }
        } else {
            hideDownloadProgress(chartId);
            showErrorMessage('SVG not found');
        }
    }
}

// Download chart data
function downloadData(chartId, format) {
    const chart = chartInstances[chartId];
    if (!chart || !chart.w || !chart.w.config) {
        showErrorMessage('Chart data not available');
        return;
    }

    try {
        const series = chart.w.config.series;
        const categories = chart.w.config.xaxis?.categories || chart.w.config.labels;
        
        let data, blob, filename;

        if (format === 'csv') {
            // Create CSV data
            let csvContent = '';
            
            if (chartId === 'categoryChart') {
                // Donut chart CSV
                csvContent = 'Category,Amount\n';
                const labels = chart.w.config.labels || [];
                const values = series || [];
                
                labels.forEach((label, index) => {
                    csvContent += `"${label}",${values[index] || 0}\n`;
                });
            } else {
                // Bar chart CSV
                csvContent = 'Month';
                series.forEach(s => csvContent += `,"${s.name}"`);
                csvContent += '\n';
                
                categories.forEach((category, index) => {
                    csvContent += `"${category}"`;
                    series.forEach(s => {
                        csvContent += `,${s.data[index] || 0}`;
                    });
                    csvContent += '\n';
                });
            }
            
            blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            filename = `${chartId}-data.csv`;
        } else if (format === 'json') {
            // Create JSON data
            const jsonData = {
                chartId: chartId,
                type: chart.w.config.chart.type,
                series: series,
                categories: categories,
                exportDate: new Date().toISOString()
            };
            
            blob = new Blob([JSON.stringify(jsonData, null, 2)], { type: 'application/json;charset=utf-8;' });
            filename = `${chartId}-data.json`;
        }

        if (blob) {
            const url = URL.createObjectURL(blob);
            triggerDownload(url, chartId.replace('Chart', ''), filename.split('.').pop());
        }
    } catch (error) {
        console.error('Data export failed:', error);
        showErrorMessage('Data export failed');
    }
}

// Convert SVG to downloadable image
function convertSvgToImage(chartId) {
    console.log('Converting SVG to image for:', chartId);
    
    const chartElement = document.getElementById(chartId);
    if (!chartElement) {
        showErrorMessage('Chart element not found.');
        return;
    }
    
    const svgElement = chartElement.querySelector('svg');
    if (!svgElement) {
        showErrorMessage('Chart SVG not found.');
        return;
    }

    try {
        // Clone SVG to avoid modifying original
        const svgClone = svgElement.cloneNode(true);
        
        // Set explicit dimensions
        const rect = svgElement.getBoundingClientRect();
        svgClone.setAttribute('width', rect.width * 2);
        svgClone.setAttribute('height', rect.height * 2);
        
        // Convert to blob
        const svgXml = new XMLSerializer().serializeToString(svgClone);
        const svgBlob = new Blob([svgXml], { type: 'image/svg+xml;charset=utf-8' });
        
        // Create canvas for conversion
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();
        
        canvas.width = rect.width * 2;
        canvas.height = rect.height * 2;
        
        img.onload = function() {
            try {
                // Fill white background
                ctx.fillStyle = 'white';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                
                // Draw SVG
                ctx.drawImage(img, 0, 0);
                
                // Convert to PNG and download
                canvas.toBlob((blob) => {
                    if (blob) {
                        const url = URL.createObjectURL(blob);
                        triggerDownload(url, chartId, 'png');
                    } else {
                        showManualSaveOption(svgBlob, chartId, 'svg');
                    }
                }, 'image/png', 0.95);
                
            } catch (error) {
                console.error('Canvas conversion failed:', error);
                showManualSaveOption(svgBlob, chartId, 'svg');
            }
        };
        
        img.onerror = function() {
            console.error('Image loading failed');
            showManualSaveOption(svgBlob, chartId, 'svg');
        };
        
        // Load SVG into image
        const url = URL.createObjectURL(svgBlob);
        img.src = url;
        
    } catch (error) {
        console.error('SVG conversion failed:', error);
        showErrorMessage('Unable to convert chart. Please take a screenshot.');
    }
}

// Enhanced download trigger with better error handling
function triggerDownload(dataUrl, chartId, extension = 'png') {
    try {
        console.log('Triggering download for:', chartId);
        
        const link = document.createElement('a');
        const filename = `${chartId}-${new Date().toISOString().slice(0, 10)}.${extension}`;
        
        link.href = dataUrl;
        link.download = filename;
        link.style.display = 'none';
        
        document.body.appendChild(link);
        
        // Force click
        link.click();
        
        // Clean up
        setTimeout(() => {
            document.body.removeChild(link);
            if (dataUrl.startsWith('blob:')) {
                URL.revokeObjectURL(dataUrl);
            }
        }, 100);
        
        showSuccessMessage('Chart downloaded successfully!');
        
    } catch (error) {
        console.error('Download trigger failed:', error);
        showErrorMessage('Download failed. Please try right-clicking and saving the image.');
    }
}

// Show manual save option
function showManualSaveOption(blob, chartId, extension) {
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `${chartId}-${new Date().toISOString().slice(0, 10)}.${extension}`;
    link.textContent = `Download ${chartId} Chart`;
    link.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #007bff;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        z-index: 10000;
        box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    `;
    
    document.body.appendChild(link);
    
    setTimeout(() => {
        if (link.parentNode) {
            document.body.removeChild(link);
        }
        URL.revokeObjectURL(url);
    }, 10000);
}

// Show download progress
function showDownloadProgress(chartId, message) {
    const existingProgress = document.getElementById('download-progress');
    if (existingProgress) {
        existingProgress.remove();
    }
    
    const progress = document.createElement('div');
    progress.id = 'download-progress';
    progress.innerHTML = `
        <div style="position: fixed; top: 20px; right: 20px; background: #007bff; color: white; padding: 10px 20px; border-radius: 5px; z-index: 10000; box-shadow: 0 2px 10px rgba(0,0,0,0.3);">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 20px; height: 20px; border: 2px solid transparent; border-top: 2px solid white; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                ${message}
            </div>
        </div>
        <style>
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
    `;
    
    document.body.appendChild(progress);
}

// Hide download progress
function hideDownloadProgress(chartId) {
    const progress = document.getElementById('download-progress');
    if (progress) {
        progress.remove();
    }
}

// Show success message
function showSuccessMessage(message) {
    showMessage(message, 'success', '#28a745');
}

// Show error message
function showErrorMessage(message) {
    showMessage(message, 'error', '#dc3545');
}

// Generic message display
function showMessage(message, type, color) {
    const messageEl = document.createElement('div');
    messageEl.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${color};
        color: white;
        padding: 12px 20px;
        border-radius: 5px;
        z-index: 10001;
        box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        font-size: 14px;
        max-width: 300px;
    `;
    messageEl.textContent = message;
    
    document.body.appendChild(messageEl);
    
    setTimeout(() => {
        if (messageEl.parentNode) {
            messageEl.style.opacity = '0';
            messageEl.style.transition = 'opacity 0.3s';
            setTimeout(() => {
                if (messageEl.parentNode) {
                    document.body.removeChild(messageEl);
                }
            }, 300);
        }
    }, 3000);
}

// Enhanced fullscreen toggle
function toggleFullscreen(element) {
    if (!element) return;
    
    if (!document.fullscreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
        // Enter fullscreen
        const requestFullscreen = element.requestFullscreen || 
                                element.webkitRequestFullscreen || 
                                element.msRequestFullscreen;
        if (requestFullscreen) {
            requestFullscreen.call(element).catch(error => {
                console.error('Fullscreen request failed:', error);
                showErrorMessage('Fullscreen not supported by your browser.');
            });
        }
    } else {
        // Exit fullscreen
        const exitFullscreen = document.exitFullscreen || 
                             document.webkitExitFullscreen || 
                             document.msExitFullscreen;
        if (exitFullscreen) {
            exitFullscreen.call(document).catch(error => {
                console.error('Exit fullscreen failed:', error);
            });
        }
    }
}

// Enhanced responsive handling
function handleResponsive() {
    const sidebar = document.getElementById('sidebar');
    const hamburger = document.getElementById('hamburger');
    
    // Handle responsive sidebar
    if (sidebar && hamburger) {
        if (window.innerWidth <= 768) {
            sidebar.classList.remove('collapsed');
        } else if (window.innerWidth <= 992) {
            sidebar.classList.add('collapsed');
        } else {
            sidebar.classList.remove('collapsed');
        }
        
        // Remove existing listeners to prevent duplicates
        const existingHandler = hamburger.getAttribute('data-handler-added');
        if (!existingHandler) {
            hamburger.addEventListener('click', function() {
                sidebar.classList.toggle('open');
            });
            hamburger.setAttribute('data-handler-added', 'true');
        }
    }
    
    // Resize charts on window resize
    Object.values(chartInstances).forEach(chart => {
        if (chart && typeof chart.resize === 'function') {
            setTimeout(() => chart.resize(), 100);
        }
    });
}

// Debounced resize handler
let resizeTimeout;
window.addEventListener('resize', function() {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(handleResponsive, 150);
});

// Utility function for currency formatting
function formatCurrency(amount, currency = 'USD', locale = 'en-US') {
    return new Intl.NumberFormat(locale, {
        style: 'currency',
        currency: currency
    }).format(amount);
}

// Export functions for external use if needed
window.chartUtils = {
    formatCurrency,
    downloadChart,
    toggleFullscreen,
    getChartInstance: (chartId) => chartInstances[chartId]
};

// Debug function
window.debugCharts = function() {
    console.log('Chart Instances:', chartInstances);
    console.log('Analytics Data:', window.analyticsData);
    console.log('Available Charts:', Object.keys(chartInstances));
};