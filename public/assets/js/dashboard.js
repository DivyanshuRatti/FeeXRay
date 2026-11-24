(() => {
    'use strict';

    const get = (id) => document.getElementById(id);
    const holdings = JSON.parse(get('dashboard-data').textContent);
    const motion = matchMedia('(prefers-reduced-motion: reduce)');
    const accountSelect = get('account-select');
    const yearSlider = get('horizon-years');
    const accountCards = document.querySelectorAll('[data-account]');
    const currency = new Intl.NumberFormat('en-CA', { style: 'currency', currency: 'CAD' });
    const axisCurrency = new Intl.NumberFormat('en-CA', {
        style: 'currency', currency: 'CAD', notation: 'compact', maximumFractionDigits: 1
    });
    const percent = new Intl.NumberFormat('en-CA', { style: 'percent', maximumFractionDigits: 2 });
    const money = (value) => currency.format(value);
    const allocationColors = ['#0c9c79', '#5b75ce', '#a06ac2', '#d77e45', '#cb5c79', '#289db8', '#a09a3f', '#547684'];
    const grossReturn = 0.05;
    let selectedHoldings = [];
    let feeChart;
    let allocationChart;

    function aggregateHoldings(rows) {
        const grouped = new Map();
        for (const row of rows) {
            const key = row.fundId === null ? 'custom:' + row.name.trim().toLowerCase() : 'fund:' + row.fundId;
            const holding = grouped.get(key) ?? { name: row.name, amount: 0, annualFee: 0 };
            holding.amount += row.amount;
            holding.annualFee += row.amount * row.mer;
            grouped.set(key, holding);
        }
        return [...grouped.values()].sort((a, b) => b.amount - a.amount || a.name.localeCompare(b.name));
    }

    function projectFees(rows, years) {
        const balances = rows.map(({ amount, mer }) => ({ amount, mer }));
        const cumulativeFees = [0];
        for (let year = 1; year <= years; year++) {
            let fees = cumulativeFees[year - 1];
            for (const holding of balances) {
                const annualFee = holding.amount * holding.mer;
                fees += annualFee;
                holding.amount = holding.amount * (1 + grossReturn) - annualFee;
            }
            cumulativeFees.push(fees);
        }
        return cumulativeFees;
    }

    // "Lower-cost options": funds that have a cheaper fund in the same category
    function renderSavings(years) {
        const byFund = new Map();
        for (const row of selectedHoldings) {
            if (!row.alternative) continue;
            const item = byFund.get(row.fundId) ?? { name: row.name, mer: row.mer, alternative: row.alternative, amount: 0 };
            item.amount += row.amount;
            byFund.set(row.fundId, item);
        }

        const items = [...byFund.values()].map((item) => {
            const current = projectFees([{ amount: item.amount, mer: item.mer }], years)[years];
            const cheaper = projectFees([{ amount: item.amount, mer: item.alternative.mer }], years)[years];
            return { ...item, current, cheaper, saving: current - cheaper };
        }).sort((a, b) => b.saving - a.saving);

        const yearLabel = years + (years === 1 ? ' year' : ' years');
        const totalSaving = items.reduce((sum, item) => sum + item.saving, 0);

        get('savings-period').textContent = 'Estimated fees over ' + yearLabel;
        get('savings-list').replaceChildren(...items.map(savingCard));
        get('savings-total').textContent = items.length ? 'Possible saving in total: ' + money(totalSaving) + ' over ' + yearLabel : '';
        get('savings-empty').hidden = items.length > 0;
        get('savings-empty').textContent = selectedHoldings.length
            ? 'Your funds are already the lowest-cost ones in their categories on our list.'
            : 'Add holdings to see lower-cost options.';
    }

    // One card: current fund, cheaper fund, and the saving
    function savingCard(item) {
        const card = document.createElement('li');
        card.className = 'saving-card';
        const lines = [
            ['saving-fund', item.name + ' · ' + percent.format(item.mer) + ' MER'],
            ['saving-fees', 'Fees: ' + money(item.current)],
            ['saving-alt', 'Same category: ' + item.alternative.name + ' (' + item.alternative.code + ') · ' + percent.format(item.alternative.mer) + ' MER'],
            ['saving-fees', 'Fees: ' + money(item.cheaper)],
            ['saving-amount', 'Possible saving: ' + money(item.saving)]
        ];
        for (const [className, text] of lines) {
            const line = document.createElement('p');
            line.className = className;
            line.textContent = text;
            card.append(line);
        }
        return card;
    }

    function chartColors() {
        const styles = getComputedStyle(document.documentElement);
        return Object.fromEntries(['muted', 'grid', 'line', 'line-fill'].map((token) => [
            token, styles.getPropertyValue('--' + token).trim()
        ]));
    }

    function setTheme(theme, persist = false) {
        document.documentElement.dataset.theme = theme;
        const dark = theme === 'dark';
        get('theme-toggle').setAttribute('aria-pressed', String(dark));
        get('theme-icon').textContent = dark ? '☀' : '☾';
        if (persist) {
            try {
                localStorage.setItem('feexray-theme', theme);
            } catch {}
        }
        updateChartTheme();
    }

    function updateChartTheme() {
        if (!feeChart || !allocationChart) return;
        const colors = chartColors();
        const { x, y } = feeChart.options.scales;
        x.ticks.color = colors.muted;
        y.ticks.color = colors.muted;
        y.grid.color = colors.grid;
        const line = feeChart.data.datasets[0];
        line.borderColor = colors.line;
        line.pointBackgroundColor = colors.line;
        feeChart.update('none');
        if (selectedHoldings.length === 0) {
            allocationChart.data.datasets[0].backgroundColor = [colors.grid];
            allocationChart.update('none');
        }
    }

    function renderProjection() {
        const years = Number(yearSlider.value);
        const yearLabel = years + (years === 1 ? ' year' : ' years');
        get('year-label').textContent = yearLabel;
        yearSlider.setAttribute('aria-valuetext', yearLabel);
        const fees = projectFees(selectedHoldings, years);
        get('projected-cost').textContent = money(fees[years]);
        renderSavings(years);
        if (!feeChart) return;
        feeChart.data.labels = fees.map((_, year) => year);
        feeChart.data.datasets[0].data = selectedHoldings.length ? fees : [];
        feeChart.update('none');
        get('fee-chart').setAttribute('aria-label', 'Projected cumulative MER fees over ' + yearLabel + ', totaling ' + money(fees[years]));
    }

    function renderAccount() {
        const scope = accountSelect.value;
        selectedHoldings = scope === 'all' ? holdings : holdings.filter(({ account }) => account === scope);
        const grouped = aggregateHoldings(selectedHoldings);
        const total = grouped.reduce((sum, { amount }) => sum + amount, 0);
        const empty = grouped.length === 0;
        const visibleName = accountSelect.selectedOptions[0].textContent;
        accountCards.forEach((card) => card.setAttribute('aria-pressed', String(card.dataset.account === scope)));
        get('holdings-scope').textContent = visibleName;
        get('holdings-table').hidden = empty;
        get('holdings-empty').hidden = !empty;
        get('fee-empty').hidden = !empty;
        get('allocation-empty').hidden = !empty;
        get('allocation-total').textContent = axisCurrency.format(total);
        get('allocation-chart').setAttribute('aria-label', 'Allocation for ' + visibleName + ', ' + money(total) + ' invested');
        renderHoldingDetails(grouped, total);
        if (allocationChart) {
            const dataset = allocationChart.data.datasets[0];
            allocationChart.data.labels = empty ? [] : grouped.map(({ name }) => name);
            dataset.data = empty ? [1] : grouped.map(({ amount }) => amount);
            dataset.backgroundColor = empty ? [chartColors().grid] : grouped.map((_, index) => allocationColors[index % allocationColors.length]);
            allocationChart.update('none');
        }
        renderProjection();
    }

    function renderHoldingDetails(grouped, total) {
        const rows = document.createDocumentFragment();
        const legend = document.createDocumentFragment();
        grouped.forEach(({ name, amount, annualFee }, index) => {
            const row = document.createElement('tr');
            [name, money(amount), percent.format(annualFee / amount), money(annualFee)].forEach((value, column) => {
                const cell = document.createElement(column === 0 ? 'th' : 'td');
                if (column === 0) cell.scope = 'row';
                cell.textContent = value;
                row.append(cell);
            });
            rows.append(row);

            const item = document.createElement('li');
            const swatch = document.createElement('i');
            swatch.className = 'swatch';
            swatch.style.backgroundColor = allocationColors[index % allocationColors.length];
            const label = document.createElement('span');
            label.textContent = name;
            const weight = document.createElement('strong');
            weight.textContent = percent.format(amount / total);
            item.append(swatch, label, weight);
            legend.append(item);
        });
        get('holdings-body').replaceChildren(rows);
        get('allocation-legend').replaceChildren(legend);
    }

    function createCharts() {
        if (!window.Chart) {
            get('chart-status').hidden = false;
            get('donut-frame').hidden = true;
            get('fee-chart').hidden = true;
            return;
        }
        const colors = chartColors();
        const common = { responsive: true, maintainAspectRatio: false, animation: false };
        feeChart = new Chart(get('fee-chart'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    borderColor: colors.line,
                    backgroundColor: ({ chart }) => {
                        const { chartArea, ctx } = chart;
                        if (!chartArea) return 'transparent';
                        const fill = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                        fill.addColorStop(0, chartColors()['line-fill']);
                        fill.addColorStop(1, 'transparent');
                        return fill;
                    },
                    fill: 'origin',
                    tension: 0.3,
                    pointBackgroundColor: colors.line,
                    pointRadius: ({ chart }) => chart.data.labels.length > 26 ? 2 : 3,
                    pointHoverRadius: 5,
                    borderWidth: 2
                }]
            },
            options: {
                ...common,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: { title: ([item]) => 'Year ' + item.label, label: ({ raw }) => money(raw) + ' cumulative' }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: colors.muted, maxTicksLimit: 7, maxRotation: 0, callback: (year) => 'Yr ' + year } },
                    y: { beginAtZero: true, grid: { color: colors.grid }, ticks: { color: colors.muted, maxTicksLimit: 6, callback: (value) => axisCurrency.format(value) } }
                }
            }
        });
        allocationChart = new Chart(get('allocation-chart'), {
            type: 'doughnut',
            data: { labels: [], datasets: [{ data: [], borderWidth: 0, hoverOffset: 4 }] },
            options: {
                ...common,
                cutout: '72%',
                layout: { padding: 6 },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        filter: () => selectedHoldings.length > 0,
                        callbacks: {
                            label: ({ label, raw, dataset }) => {
                                const total = dataset.data.reduce((sum, amount) => sum + amount, 0);
                                return label + ' · ' + money(raw) + ' (' + percent.format(raw / total) + ')';
                            }
                        }
                    }
                }
            }
        });
    }

    get('theme-toggle').addEventListener('click', () => {
        setTheme(document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark', true);
    });
    accountSelect.addEventListener('change', renderAccount);
    accountCards.forEach((card) => card.addEventListener('click', () => {
        accountSelect.value = accountSelect.value === card.dataset.account ? 'all' : card.dataset.account;
        renderAccount();
    }));
    yearSlider.addEventListener('input', renderProjection);

    setTheme(document.documentElement.dataset.theme);
    createCharts();
    renderAccount();

    if (!motion.matches) {
        import('https://cdn.jsdelivr.net/npm/three@0.174.0/build/three.module.js')
            .then((three) => {
                if (!motion.matches) animateOverview(three);
            })
            .catch(() => {
                // The CSS illustration covers unavailable WebGL and network failures.
            });
    }

    function animateOverview(three) {
        const art = get('overview-art');
        const renderer = new three.WebGLRenderer({ alpha: true, antialias: true });
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 1.75));
        const scene = new three.Scene();
        const camera = new three.PerspectiveCamera(35, 1, 0.1, 60);
        camera.position.z = 9;

        scene.add(new three.HemisphereLight(0xc1fff1, 0x145c69, 2.2));
        const light = new three.DirectionalLight(0xf0ffed, 3);
        light.position.set(3, 4, 5);
        scene.add(light);

        const sculpture = new three.Group();
        scene.add(sculpture);
        const ring = new three.Mesh(
            new three.TorusGeometry(1.43, 0.22, 24, 96),
            new three.MeshPhysicalMaterial({
                color: 0x66dbc1, metalness: 0.21, roughness: 0.21, clearcoat: 1,
                transparent: true, opacity: 0.82
            })
        );
        ring.rotation.set(0.95, 0.45, 0.1);
        sculpture.add(ring);

        const orbit = new three.Mesh(
            new three.TorusGeometry(1.7, 0.027, 10, 96),
            new three.MeshBasicMaterial({ color: 0xb3fdec, transparent: true, opacity: 0.7 })
        );
        orbit.rotation.set(-0.62, 0.42, -0.28);
        sculpture.add(orbit);

        const orb = new three.Mesh(
            new three.SphereGeometry(0.38, 24, 16),
            new three.MeshPhysicalMaterial({ color: 0xa6ffe1, roughness: 0.24, metalness: 0.12, clearcoat: 1 })
        );
        orb.position.set(0.5, 0.16, 0.26);
        sculpture.add(orb);

        function resize() {
            const { clientWidth: width, clientHeight: height } = art;
            if (width === 0 || height === 0) return;
            camera.aspect = width / height;
            camera.position.z = camera.aspect < 1.5 ? 12 : 9;
            camera.updateProjectionMatrix();
            renderer.setSize(width, height, false);
        }

        let visible = true;
        function syncAnimation() {
            const active = visible && !document.hidden && !motion.matches;
            art.classList.toggle('is-rendered', !motion.matches);
            renderer.domElement.hidden = motion.matches;
            renderer.setAnimationLoop(active ? (time) => {
                sculpture.rotation.y = Math.sin(time / 2700) * 0.55;
                sculpture.rotation.z = time / 14000;
                orb.position.y = Math.sin(time / 1300) * 0.22;
                renderer.render(scene, camera);
            } : null);
        }

        art.append(renderer.domElement);
        resize();
        new ResizeObserver(resize).observe(art);
        new IntersectionObserver(([entry]) => {
            visible = entry.isIntersecting;
            syncAnimation();
        }).observe(art);
        document.addEventListener('visibilitychange', syncAnimation);
        motion.addEventListener('change', syncAnimation);
        syncAnimation();
    }
})();