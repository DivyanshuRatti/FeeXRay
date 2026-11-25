(() => {
    const countdown = document.getElementById('countdown');
    const unit = document.getElementById('countdown-unit');
    const progress = document.getElementById('logout-progress');
    const home = document.getElementById('logout-home');
    let seconds = Number(countdown.textContent);

    // The redirect runs independently of the background.
    const timer = setInterval(() => {
        seconds -= 1;
        countdown.textContent = seconds;
        unit.textContent = seconds === 1 ? 'second' : 'seconds';
        progress.value = seconds;
        if (seconds === 0) {
            clearInterval(timer);
            window.location.href = home.href;
        }
    }, 1000);

    const motion = matchMedia('(prefers-reduced-motion: reduce)');
    if (!motion.matches) {
        import('https://cdn.jsdelivr.net/npm/three@0.174.0/build/three.module.js')
            .then((three) => {
                if (!motion.matches) animateCoins(three);
            })
            .catch(() => {
                // Keep the static artwork if Three.js or WebGL is unavailable.
            });
    }

    function animateCoins(three) {
        const backdrop = document.getElementById('logout-scene');
        const renderer = new three.WebGLRenderer({ alpha: true, antialias: true });
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 1.5));
        const scene = new three.Scene();
        const camera = new three.OrthographicCamera(-1, 1, 1, -1, 1, 1000);
        camera.position.z = 500;

        const edge = new three.MeshStandardMaterial({ color: 0xc28934, roughness: 0.42 });
        const face = new three.MeshBasicMaterial({ map: makeCoinFace(three) });
        const coinBody = new three.CylinderGeometry(1, 1, 0.17, 48).rotateX(Math.PI / 2);
        const coinFace = new three.CircleGeometry(0.99, 48);
        const coins = [];

        for (let index = 0; index < 3; index++) {
            const coin = new three.Group();
            const front = new three.Mesh(coinFace, face);
            const back = new three.Mesh(coinFace, face);
            front.position.z = 0.086;
            back.position.z = -0.086;
            back.rotation.y = Math.PI;
            coin.add(new three.Mesh(coinBody, edge), front, back);
            coins.push(coin);
            scene.add(coin);
        }
        scene.add(new three.HemisphereLight(0xffebc1, 0x483b21, 2.5));

        const desktopPositions = [[0.15, 0.64, 0.11], [0.85, 0.29, 0.105], [0.79, 0.8, 0.063]];
        const mobilePositions = [[0.17, 0.19, 0.116], [0.84, 0.82, 0.1]];
        function resize() {
            const width = backdrop.clientWidth;
            const height = backdrop.clientHeight;
            const positions = width <= 620 ? mobilePositions : desktopPositions;
            camera.left = -width / 2;
            camera.right = width / 2;
            camera.top = height / 2;
            camera.bottom = -height / 2;
            camera.updateProjectionMatrix();
            renderer.setSize(width, height, false);

            // Put the coins around the message and keep it clear on phones.
            coins.forEach((coin, index) => {
                const position = positions[index];
                coin.visible = Boolean(position);
                if (!position) return;
                const [x, y, size] = position;
                coin.position.x = width * (x - 0.5);
                coin.position.y = height * (0.5 - y);
                coin.userData.baseY = coin.position.y;
                coin.scale.setScalar(Math.min(width, height) * size);
            });
            renderer.render(scene, camera);
        }

        function frame(time) {
            coins.forEach((coin, index) => {
                coin.position.y = coin.userData.baseY + Math.sin(time / 700 + index) * 9;
                coin.rotation.x = 0.12 + Math.sin(time / 950 + index) * 0.25;
                coin.rotation.y = 0.38 + Math.sin(time / 820 + index * 1.8) * 0.5;
                coin.rotation.z = index % 2 ? 0.18 : -0.28;
            });
            renderer.render(scene, camera);
        }

        function updateMotion() {
            backdrop.classList.toggle('is-animated', !motion.matches);
            renderer.domElement.hidden = motion.matches;
            renderer.setAnimationLoop(motion.matches ? null : frame);
        }

        backdrop.append(renderer.domElement);
        resize();
        updateMotion();
        window.addEventListener('resize', resize);
        motion.addEventListener('change', updateMotion);
        window.addEventListener('pagehide', () => renderer.setAnimationLoop(null), { once: true });
    }

    function makeCoinFace(three) {
        const canvas = document.createElement('canvas');
        canvas.width = 256;
        canvas.height = 256;
        const context = canvas.getContext('2d');
        const gold = context.createLinearGradient(32, 16, 222, 240);
        gold.addColorStop(0, '#fff0ab');
        gold.addColorStop(0.48, '#f4c55e');
        gold.addColorStop(1, '#c38737');
        context.fillStyle = gold;
        context.fillRect(0, 0, 256, 256);

        for (const [radius, color, width] of [[119, '#ffe7a1', 7], [105, '#a96c26', 4], [98, '#ffe0a0', 2]]) {
            context.beginPath();
            context.arc(128, 128, radius, 0, Math.PI * 2);
            context.strokeStyle = color;
            context.lineWidth = width;
            context.stroke();
        }
        context.font = 'bold 140px Arial';
        context.textAlign = 'center';
        context.textBaseline = 'middle';
        context.fillStyle = '#ffefa8';
        context.fillText('$', 128, 135);
        context.fillStyle = '#89500f';
        context.fillText('$', 126, 131);

        const texture = new three.CanvasTexture(canvas);
        texture.colorSpace = three.SRGBColorSpace;
        return texture;
    }
})();
