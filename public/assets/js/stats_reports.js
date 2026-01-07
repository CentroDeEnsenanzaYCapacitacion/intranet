let currentPeriod = 'anual';

function setPeriod(periodType) {
    currentPeriod = periodType;

    const periodButtons = document.querySelectorAll('[data-period-btn]');
    periodButtons.forEach(btn => {
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-secondary');
    });

    const activeBtn = document.querySelector(`[data-period-btn="${periodType}"]`);
    if (activeBtn) {
        activeBtn.classList.remove('btn-secondary');
        activeBtn.classList.add('btn-primary');
    }

    const yearGroup = document.getElementById('yearGroup');
    const monthGroup = document.getElementById('monthGroup');
    const semesterGroup = document.getElementById('semesterGroup');
    const customRangeGroup = document.getElementById('customRangeGroup');

    if (monthGroup) monthGroup.style.display = 'none';
    if (semesterGroup) semesterGroup.style.display = 'none';
    if (customRangeGroup) customRangeGroup.style.display = 'none';

    if (periodType === 'mensual' && monthGroup) {
        if (yearGroup) yearGroup.style.display = 'flex';
        monthGroup.style.display = 'flex';
    } else if (periodType === 'semestral' && semesterGroup) {
        if (yearGroup) yearGroup.style.display = 'flex';
        semesterGroup.style.display = 'flex';
    } else if (periodType === 'anual') {
        if (yearGroup) yearGroup.style.display = 'flex';
    } else if (periodType === 'custom' && customRangeGroup) {
        if (yearGroup) yearGroup.style.display = 'none';
        customRangeGroup.style.display = 'flex';
    }
}

function applyFilters() {
    if (!window.statsReportsConfig) {
        console.error('statsReportsConfig not found');
        return;
    }

    const yearSelector = document.getElementById('yearSelector');
    const selectedYear = yearSelector ? yearSelector.value : window.statsReportsConfig.year;
    const baseRoute = window.statsReportsConfig.baseRoute;

    console.log('Applying filters for period:', currentPeriod);
    console.log('Selected year:', selectedYear);

    if (currentPeriod === 'mensual') {
        const monthSelector = document.getElementById('monthSelector');
        const selectedMonth = monthSelector ? monthSelector.value : null;
        console.log('Selected month:', selectedMonth);
        if (selectedMonth) {
            const url = baseRoute + '/mensual/' + selectedYear + '?month=' + selectedMonth;
            console.log('Navigating to:', url);
            window.location.href = url;
        }
    } else if (currentPeriod === 'semestral') {
        const semesterSelector = document.getElementById('semesterSelector');
        const selectedSemester = semesterSelector ? semesterSelector.value : null;
        console.log('Selected semester:', selectedSemester);
        if (selectedSemester) {
            const url = baseRoute + '/semestral/' + selectedYear + '?month=' + selectedSemester;
            console.log('Navigating to:', url);
            window.location.href = url;
        }
    } else if (currentPeriod === 'anual') {
        const url = baseRoute + '/anual/' + selectedYear;
        console.log('Navigating to:', url);
        window.location.href = url;
    } else if (currentPeriod === 'custom') {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        console.log('Custom range:', startDate, 'to', endDate);

        if (!startDate || !endDate) {
            alert('Por favor selecciona ambas fechas');
            return;
        }

        const startYear = new Date(startDate).getFullYear();
        const url = baseRoute + '/mensual/' + startYear + '?start_date=' + startDate + '&end_date=' + endDate;
        console.log('Navigating to:', url);
        window.location.href = url;
    }
}

function initStatsReports() {
    if (!window.statsReportsConfig) {
        console.error('statsReportsConfig not found');
        return;
    }

    currentPeriod = window.statsReportsConfig.period || 'anual';

    if (!window.statsReportsConfig.stats) {
        return;
    }

    const stats = window.statsReportsConfig.stats;
    const compareStats = window.statsReportsConfig.compareStats || [];
    const year = window.statsReportsConfig.year;

    const reportsCtx = document.getElementById('reportsChart');
    if (!reportsCtx) return;

    const datasets = [
        {
            label: 'Informes ' + year,
            data: stats.map(s => s.reports),
            borderColor: '#F57F17',
            backgroundColor: 'rgba(245, 127, 23, 0.1)',
            borderWidth: 3,
            tension: 0.4,
            fill: true,
            pointRadius: 5,
            pointHoverRadius: 7
        },
        {
            label: 'Inscritos ' + year,
            data: stats.map(s => s.enrolled),
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            borderWidth: 3,
            tension: 0.4,
            fill: true,
            pointRadius: 5,
            pointHoverRadius: 7
        }
    ];

    if (compareStats.length > 0) {
        datasets.push({
            label: 'Informes ' + (year - 1),
            data: compareStats.map(s => s.reports),
            borderColor: '#F57F17',
            backgroundColor: 'rgba(245, 127, 23, 0.05)',
            borderWidth: 2,
            borderDash: [5, 5],
            tension: 0.4,
            fill: false,
            pointRadius: 3,
            pointHoverRadius: 5
        });
        datasets.push({
            label: 'Inscritos ' + (year - 1),
            data: compareStats.map(s => s.enrolled),
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.05)',
            borderWidth: 2,
            borderDash: [5, 5],
            tension: 0.4,
            fill: false,
            pointRadius: 3,
            pointHoverRadius: 5
        });
    }

    new Chart(reportsCtx.getContext('2d'), {
        type: 'line',
        data: {
            labels: stats.map(s => s.period),
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 14,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { size: 14, weight: '600' },
                    bodyFont: { size: 13 },
                    borderColor: '#F57F17',
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: { size: 12 }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { size: 12 }
                    }
                }
            }
        }
    });

    const conversionCtx = document.getElementById('conversionChart');
    if (!conversionCtx) return;

    new Chart(conversionCtx.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Inscritos', 'No Inscritos'],
            datasets: [{
                data: [window.statsReportsConfig.totalEnrolled, window.statsReportsConfig.totalReports - window.statsReportsConfig.totalEnrolled],
                backgroundColor: [
                    '#10b981',
                    '#e5e7eb'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: {
                            size: 13,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { size: 14, weight: '600' },
                    bodyFont: { size: 13 }
                }
            }
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initStatsReports);
} else {
    initStatsReports();
}
