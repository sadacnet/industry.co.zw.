<?php
require_once __DIR__ . '/includes/header.php';
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>📋 CSV Header Checker</h3>
        <a href="import-csv.php" class="btn btn-info">← Back to Import</a>
    </div>
    
    <form method="POST" enctype="multipart/form-data">
        <div class="card" style="border:1px solid #e0e0e0;">
            <div class="card-body">
                <div class="form-group mb-3">
                    <label class="fw-bold">Select CSV File</label>
                    <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                </div>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-search"></i> Check Headers
                </button>
            </div>
        </div>
    </form>

    <?php if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === 0): 
        $handle = fopen($_FILES['csv_file']['tmp_name'], 'r');
        $headers = fgetcsv($handle);
        $headersLower = array_map('strtolower', $headers);
        $headersLower = array_map('trim', $headersLower);
        $firstRow = fgetcsv($handle);
        $secondRow = fgetcsv($handle);
        fclose($handle);
        
        // Count total rows (rough estimate)
        $totalRows = 0;
        $handle2 = fopen($_FILES['csv_file']['tmp_name'], 'r');
        fgetcsv($handle2); // Skip header
        while (fgetcsv($handle2) !== false) $totalRows++;
        fclose($handle2);
    ?>
    
    <!-- Summary -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-center p-3" style="background:#e8f5e9;">
                <h2 style="color:#006400;"><?php echo count($headers); ?></h2>
                <p class="mb-0">Total Columns</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-3" style="background:#e3f2fd;">
                <h2 style="color:#1565C0;"><?php echo $totalRows; ?></h2>
                <p class="mb-0">Data Rows</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-3" style="background:#fff3e0;">
                <h2 style="color:#e65100;"><?php echo filesize($_FILES['csv_file']['tmp_name']) > 1024 ? round(filesize($_FILES['csv_file']['tmp_name'])/1024) . ' KB' : filesize($_FILES['csv_file']['tmp_name']) . ' B'; ?></h2>
                <p class="mb-0">File Size</p>
            </div>
        </div>
    </div>

    <!-- All Columns -->
    <div class="card mt-4" style="border:1px solid #e0e0e0;">
        <div class="card-header" style="background:#f8fafc;"><h5 class="mb-0">All Columns Found</h5></div>
        <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0">
                <thead>
                    <tr><th>#</th><th>Column Name</th><th>Row 1 Value</th><th>Row 2 Value</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($headers as $i => $h): ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><code style="background:#f0f0f0;padding:2px 6px;border-radius:3px;"><?php echo htmlspecialchars(strtolower(trim($h))); ?></code></td>
                        <td><?php echo htmlspecialchars($firstRow[$i] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($secondRow[$i] ?? ''); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Column Detection Status -->
    <div class="card mt-4" style="border:1px solid #e0e0e0;">
        <div class="card-header" style="background:#f8fafc;"><h5 class="mb-0">Import Column Detection</h5></div>
        <div class="card-body">
            <table class="table table-sm table-bordered">
                <thead><tr><th>Required Data</th><th>Possible Column Names</th><th>Status</th><th>Matched Column</th></tr></thead>
                <tbody>
                    <?php
                    $checks = [
                        'Company Name' => ['content_post_title', 'name', 'title', 'company'],
                        'Industry' => ['directory_category', 'industry_id', 'category', 'sector'],
                        'Phone' => ['directory_contact__phone', 'phone', 'telephone', 'contact'],
                        'Mobile' => ['directory_contact__mobile', 'mobile', 'cell'],
                        'Email' => ['directory_contact__email', 'email', 'e-mail'],
                        'Website' => ['directory_contact__website', 'website', 'url', 'web'],
                        'Description' => ['content_body', 'description', 'body', 'about'],
                        'Address' => ['directory_location__address', 'address', 'location'],
                        'City' => ['directory_location__city', 'city', 'town'],
                        'State/Province' => ['directory_location__state', 'province_id', 'state', 'province'],
                        'Photos/Logo' => ['directory_photos', 'logo', 'photos', 'image'],
                    ];
                    
                    foreach ($checks as $label => $possibleNames):
                        $found = false;
                        $matchedCol = '';
                        foreach ($possibleNames as $name) {
                            if (in_array(strtolower($name), $headersLower)) {
                                $found = true;
                                $matchedCol = $name;
                                break;
                            }
                        }
                        $statusColor = $found ? '#e8f5e9' : '#ffebee';
                        $statusIcon = $found ? '✅' : '❌';
                        $statusText = $found ? 'Found' : 'Missing';
                    ?>
                    <tr style="background:<?php echo $statusColor; ?>;">
                        <td><strong><?php echo $label; ?></strong></td>
                        <td><small><?php echo implode(', ', $possibleNames); ?></small></td>
                        <td><?php echo $statusIcon; ?> <?php echo $statusText; ?></td>
                        <td><code><?php echo $matchedCol ?: '—'; ?></code></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-3">
        <a href="import-csv.php" class="btn btn-primary">Go to Import Page</a>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>