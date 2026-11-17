<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add holding | FeeXRay</title>
</head>
<body>
    <main>
        <p><a href="/portfolio-view.php?id=<?php echo $portfolio['id']; ?>">Back to <?php echo htmlspecialchars($portfolio['name']); ?></a></p>
        <h1>Add a holding to <?php echo htmlspecialchars($portfolio['name']); ?></h1>

        <?php
        // Show any errors from the logic file
        if (!empty($errors)) {
            echo '<ul>';
            foreach ($errors as $error) {
                echo '<li>' . htmlspecialchars($error) . '</li>';
            }
            echo '</ul>';
        }
        ?>

        <!-- The portfolio id in the URL tells the logic file where to save the holding -->
        <form method="POST" action="/holding-add.php?portfolio_id=<?php echo $portfolio['id']; ?>">
            <label for="fund_id">Fund</label>
            <select id="fund_id" name="fund_id" required>
                <option value="">Choose a fund</option>

                <?php
                foreach ($funds as $f) {
                    $id = $f['id'];
                    $name = htmlspecialchars($f['name']);
                    $mer = number_format($f['mer'] * 100, 2); // 0.0225 → 2.25

                    echo "<option value='$id'>$name · MER $mer%</option>";
                }
                ?>

                <option value="custom" <?php if ($choice === 'custom') echo 'selected'; ?>>My fund isn't listed</option>
            </select>

            <!-- Only shown when "My fund isn't listed" is picked (see script below) -->
            <div id="custom-fields" hidden>
                <label for="custom_name">Fund name</label>
                <input type="text" id="custom_name" name="custom_name" value="<?php echo htmlspecialchars($customName); ?>">

                <label for="custom_mer">MER (%)</label>
                <input type="text" id="custom_mer" name="custom_mer" placeholder="2.25" value="<?php echo htmlspecialchars($customMer); ?>">
            </div>

            <!-- Outside the custom box: every holding needs an amount -->
            <label for="amount">Amount invested ($)</label>
            <input type="text" id="amount" name="amount" placeholder="10000" value="<?php echo htmlspecialchars($amount); ?>" required>

            <button type="submit">Save</button>
        </form>
    </main>

    <!-- At the end so the dropdown and boxes already exist when it runs -->
    <script>
        const fundSelect = document.getElementById('fund_id');
        const customFields = document.getElementById('custom-fields');

        // Show the custom boxes only for "My fund isn't listed"
        function toggleCustomFields() {
            const isCustom = fundSelect.value === 'custom';
            customFields.hidden = !isCustom;

            // Clear the boxes when hiding them, so old typing isn't sent
            if (!isCustom) {
                document.getElementById('custom_name').value = '';
                document.getElementById('custom_mer').value = '';
            }
        }

        fundSelect.addEventListener('change', toggleCustomFields);
        toggleCustomFields(); // run once on load too
    </script>
</body>
</html>