<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Backup redirect after 5 seconds, in case JavaScript is off -->
    <meta http-equiv="refresh" content="5;url=/">
    <title>Logged out | FeeXRay</title>
</head>
<body>
    <main>
        <h1>You have been logged out</h1>
        <p>Thanks for using FeeXRay.</p>
        <p>Redirecting you to the homepage in <span id="countdown">5</span> seconds...</p>
        <p><a href="/">Go to the homepage now</a></p>
    </main>

    <script>
        let seconds = 5;
        const countdown = document.getElementById('countdown');

        // Every 1 second: count down by 1, and go home at 0
        const timer = setInterval(function () {
            seconds = seconds - 1;
            countdown.textContent = seconds;

            if (seconds <= 0) {
                clearInterval(timer);
                window.location.href = '/';
            }
        }, 1000);
    </script>
</body>
</html>