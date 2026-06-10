<?php
require_once __DIR__ . '/includes/header.php';

$imported = 0;
$skipped = 0;
$errors = [];
$previewData = [];
$importStakeholder = 'CZI';
$importType = 'partner';

if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === 0) {
    $file = $_FILES['csv_file']['tmp_name'];
    
    $sample = fread(fopen($file, 'r'), 500);
    $delimiter = (substr_count($sample, "\t") > substr_count($sample, ',')) ? "\t" : ",";
    
    $handle = fopen($file, 'r');
    $headers = fgetcsv($handle, 0, $delimiter);
    $headers = array_map('strtolower', $headers);
    $headers = array_map('trim', $headers);
    
    $importStakeholder = $_POST['import_stakeholder'] ?? 'CZI';
    $importType = $_POST['import_type'] ?? 'partner';
    
    if ($importType === 'industry') {
        $importStakeholder = null;
    }
    
    require_once __DIR__ . '/../api/config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    $preview = isset($_POST['preview']);
    
    while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
        if (count($row) < 2) continue;
        if (count($row) > count($headers)) $row = array_slice($row, 0, count($headers));
        
        // Pad row if shorter than headers
        while (count($row) < count($headers)) $row[] = '';
        
        $data = array_combine($headers, $row);
        
        // Company name - check multiple column names
        $companyName = trim($data['content_post_title'] ?? $data['name'] ?? $data['company'] ?? $data['title'] ?? '');
        if (empty($companyName)) continue;
        
        // Industry
        $categoryRaw = trim($data['directory_category'] ?? $data['category'] ?? $data['industry'] ?? '');
        $categoryParts = explode(';', $categoryRaw);
        $industryName = trim($categoryParts[0]);
        $industrySlug = mapIndustry($industryName);
        $industryId = getIndustryId($db, $industrySlug);
        
        // Phone
        $phone = trim($data['directory_contact__phone'] ?? $data['phone'] ?? '');
        if (empty($phone)) $phone = trim($data['directory_contact__mobile'] ?? $data['mobile'] ?? '');
        
        // Email
        $email = trim($data['directory_contact__email'] ?? $data['email'] ?? '');
        
        // Website
        $website = trim($data['directory_contact__website'] ?? $data['website'] ?? '');
        
        // Description
        $rawDescription = trim($data['content_body'] ?? $data['description'] ?? $data['location'] ?? '');
        $description = strip_tags($rawDescription);
        $description = str_replace('&nbsp;', ' ', $description);
        $description = preg_replace('/\s+/', ' ', $description);
        $description = trim($description);
        
        // Extract location
        $extracted = extractLocationInfo($description);
        
        // Province
        $provinceRaw = trim($data['directory_location__state'] ?? $data['province_id'] ?? $data['region'] ?? $data['province'] ?? '');
        if (!empty($provinceRaw)) {
            $provinceSlug = mapProvince($provinceRaw);
        } else {
            $provinceSlug = mapProvince($extracted['province']);
        }
        $provinceId = getProvinceId($db, $provinceSlug);
        
        // Logo
        $logo = trim($data['directory_photos'] ?? $data['logo'] ?? $data['image url'] ?? $data['image'] ?? '');
        if (strpos($logo, '|') !== false) {
            $logoParts = explode('|', $logo);
            foreach ($logoParts as $part) {
                if (preg_match('/\.(jpg|jpeg|png|gif|webp)/i', $part)) {
                    $logo = trim($part);
                    break;
                }
            }
        }
        
        $companyData = [
            'name' => $companyName,
            'industry_id' => (int)$industryId,
            'province_id' => (int)$provinceId,
            'phone' => $phone,
            'email' => $email,
            'website' => $website,
            'description' => $description,
            'logo' => $logo,
            'industry_name' => getIndustryName($db, $industryId),
            'province_name' => getProvinceName($db, $provinceId),
            'city' => $extracted['city'],
            'address' => $extracted['address'],
            'stakeholder' => $importStakeholder,
            'listing_type' => $importType
        ];
        
        if ($preview) {
            $previewData[] = $companyData;
        } else {
            // Check duplicate
            try {
                if ($importType === 'industry') {
                    $checkStmt = $db->prepare("SELECT id FROM companies WHERE name = ? AND (stakeholder IS NULL OR stakeholder = '')");
                    $checkStmt->execute([$companyData['name']]);
                } else {
                    $checkStmt = $db->prepare("SELECT id FROM companies WHERE name = ? AND stakeholder = ?");
                    $checkStmt->execute([$companyData['name'], $companyData['stakeholder']]);
                }
                if ($checkStmt->fetch()) { $skipped++; continue; }
            } catch (Exception $e) { $errors[] = $companyData['name'] . ' (check): ' . $e->getMessage(); continue; }
            
            // Insert
            try {
                $stmt = $db->prepare("INSERT INTO companies (name, industry_id, province_id, stakeholder, listing_type, phone, email, website, logo, description, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
                $stmt->execute([
                    $companyData['name'],
                    $companyData['industry_id'],
                    $companyData['province_id'],
                    $companyData['stakeholder'],
                    $companyData['listing_type'],
                    $companyData['phone'],
                    $companyData['email'],
                    $companyData['website'],
                    $companyData['logo'],
                    $companyData['description']
                ]);
                $imported++;
            } catch (Exception $e) {
                $errors[] = $companyData['name'] . ': ' . $e->getMessage();
            }
        }
    }
    fclose($handle);
}

// ========== HELPER FUNCTIONS ==========
function extractLocationInfo($text) {
    $result = ['address' => $text, 'city' => '', 'province' => ''];
    $textLower = strtolower($text);
    $cities = ['harare'=>'Harare','bulawayo'=>'Bulawayo','masvingo'=>'Masvingo','gweru'=>'Gweru','mutare'=>'Mutare','kwekwe'=>'Kwekwe','chinhoyi'=>'Chinhoyi','marondera'=>'Marondera','kadoma'=>'Kadoma'];
    foreach($cities as $k=>$n){if(strpos($textLower,$k)!==false){$result['city']=$n;break;}}
    $provinces = ['harare'=>'harare','bulawayo'=>'bulawayo','masvingo'=>'masvingo','midlands'=>'midlands','manicaland'=>'manicaland','gweru'=>'midlands','mutare'=>'manicaland','kwekwe'=>'midlands','chinhoyi'=>'mashonaland-west','marondera'=>'mashonaland-east'];
    foreach($provinces as $k=>$s){if(strpos($textLower,$k)!==false){$result['province']=$s;break;}}
    if(empty($result['province'])&&!empty($result['city'])){$cl=strtolower($result['city']);if(isset($provinces[$cl]))$result['province']=$provinces[$cl];}
    return $result;
}

function mapProvince($name) {
    $name = strtolower(trim($name)); if(empty($name))return 'harare';
    $map = ['harare'=>'harare','bulawayo'=>'bulawayo','manicaland'=>'manicaland','masvingo'=>'masvingo','midlands'=>'midlands','gweru'=>'midlands','mutare'=>'manicaland','kwekwe'=>'midlands','mashonaland central'=>'mashonaland-central','mashonaland east'=>'mashonaland-east','mashonaland west'=>'mashonaland-west','matabeleland north'=>'matabeleland-north','matabeleland south'=>'matabeleland-south'];
    if(isset($map[$name]))return $map[$name];
    foreach($map as $k=>$v){if(strpos($name,$k)!==false)return $v;}
    return 'harare';
}

function mapIndustry($name) {
    $name = strtolower(trim($name)); if(empty($name))return 'manufacturing';
    $map = ['auto'=>'auto','automotive'=>'auto','car'=>'auto','vehicle'=>'auto','motor'=>'auto','accommodation'=>'accommodation','hotel'=>'accommodation','lodge'=>'accommodation','agriculture'=>'agriculture','farming'=>'agriculture','banking'=>'banking-finance','finance'=>'banking-finance','bank'=>'banking-finance','biotechnology'=>'biotechnology','biotech'=>'biotechnology','construction'=>'construction','building'=>'construction','engineering'=>'construction','general-contractors'=>'construction','contractors'=>'construction','education'=>'education','school'=>'education','university'=>'education','energy'=>'energy-power','power'=>'energy-power','solar'=>'energy-power','healthcare'=>'healthcare','health'=>'healthcare','medical'=>'healthcare','hospital'=>'healthcare','manufacturing'=>'manufacturing','factory'=>'manufacturing','mining'=>'mining','mine'=>'mining','technology'=>'technology-ict','ict'=>'technology-ict','software'=>'technology-ict','it'=>'technology-ict','tourism'=>'tourism-hospitality','hospitality'=>'tourism-hospitality','travel'=>'tourism-hospitality','transport'=>'transport-logistics','logistics'=>'transport-logistics','haulage'=>'transport-logistics','security'=>'technology-ict','printing'=>'manufacturing','food'=>'manufacturing','pharmaceutical'=>'healthcare','packaging'=>'manufacturing','chemical'=>'manufacturing','real estate'=>'construction','consulting'=>'construction','electrical'=>'energy-power','media'=>'technology-ict','fashion'=>'manufacturing','hardware'=>'manufacturing','cement'=>'construction','brick'=>'construction','concrete'=>'construction','painting'=>'construction','landscaping'=>'construction','aluminium'=>'manufacturing','leather'=>'manufacturing','stationery'=>'manufacturing','plastic'=>'manufacturing'];
    if(isset($map[$name]))return $map[$name];
    foreach($map as $k=>$v){if(strpos($name,$k)!==false)return $v;}
    return 'manufacturing';
}

function getProvinceId($db,$slug){$s=$db->prepare("SELECT id FROM provinces WHERE slug=?");$s->execute([$slug]);$r=$s->fetch();return $r?$r['id']:1;}
function getProvinceName($db,$id){$s=$db->prepare("SELECT name FROM provinces WHERE id=?");$s->execute([$id]);$r=$s->fetch();return $r?$r['name']:'Harare';}
function getIndustryId($db,$slug){$s=$db->prepare("SELECT id FROM industries WHERE slug=?");$s->execute([$slug]);$r=$s->fetch();return $r?$r['id']:10;}
function getIndustryName($db,$id){$s=$db->prepare("SELECT name FROM industries WHERE id=?");$s->execute([$id]);$r=$s->fetch();return $r?$r['name']:'Manufacturing';}
?>

<div class="card" style="border-radius:4px;box-shadow:0 1px 3px rgba(0,0,0,0.08);">
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 20px 0;">
        <div><h3 style="font-weight:600;color:#1d2327;margin:0;">📥 Import Companies</h3><p style="color:#646970;font-size:13px;margin:4px 0 0;">Import CZI, CIFOZ, or industry.co.zw listings from CSV</p></div>
        <a href="members.php" class="btn" style="background:#fff;border:1px solid #c3c4c7;color:#2271b1;border-radius:3px;font-size:13px;padding:6px 14px;">← View Members</a>
    </div>

    <?php if (!empty($previewData)): ?>
    <div style="padding:20px;">
        <div class="alert" style="background:#f0f6fc;border:1px solid #c5d9ed;border-radius:3px;"><h5 style="margin:0 0 8px;"><i class="bi bi-eye"></i> Preview - <?php echo count($previewData); ?> companies</h5><p style="margin:0;font-size:13px;">Type: <strong><?php echo $importType==='industry'?'Industry Listings':$importStakeholder.' Partner'; ?></strong></p></div>
        <div style="overflow-x:auto;margin-top:16px;">
            <table class="table table-bordered table-sm" style="font-size:13px;"><thead style="background:#f0f0f1;"><tr><th>#</th><th>Company</th><th>Industry</th><th>Address</th><th>City</th><th>Province</th></tr></thead>
                <tbody><?php foreach($previewData as $i=>$c): ?><tr><td><?php echo $i+1; ?></td><td><strong><?php echo htmlspecialchars($c['name']); ?></strong></td><td><span class="badge bg-success"><?php echo $c['industry_name']; ?></span></td><td><small><?php echo htmlspecialchars(substr($c['address'],0,50)); ?>...</small></td><td><?php echo $c['city']; ?></td><td><?php echo $c['province_name']; ?></td></tr><?php endforeach; ?></tbody>
            </table>
        </div>
        <form method="POST" enctype="multipart/form-data" class="mt-3 d-flex gap-2">
            <input type="hidden" name="confirm" value="1">
            <input type="hidden" name="csv_data" value="<?php echo base64_encode(serialize($previewData)); ?>">
            <input type="hidden" name="import_stakeholder" value="<?php echo $importStakeholder; ?>">
            <input type="hidden" name="import_type" value="<?php echo $importType; ?>">
            <button type="submit" class="btn" style="background:#2271b1;color:#fff;border:none;border-radius:3px;padding:8px 20px;font-size:14px;"><i class="bi bi-check-circle"></i> Confirm (<?php echo count($previewData); ?>)</button>
            <a href="import-csv.php" class="btn" style="background:#fff;border:1px solid #c3c4c7;color:#2271b1;border-radius:3px;padding:8px 20px;">Cancel</a>
        </form>
    </div>

    <?php elseif (isset($_POST['confirm']) && isset($_POST['csv_data'])): ?>
    <?php
    $previewData = unserialize(base64_decode($_POST['csv_data']));
    $importStakeholder = $_POST['import_stakeholder'] ?? 'CZI';
    $importType = $_POST['import_type'] ?? 'partner';
    require_once __DIR__ . '/../api/config/database.php';
    $db = (new Database())->getConnection();
    
    foreach($previewData as $c){
        // Check duplicate
        try {
            if($importType==='industry'){$chk=$db->prepare("SELECT id FROM companies WHERE name=? AND (stakeholder IS NULL OR stakeholder='')");$chk->execute([$c['name']]);}
            else{$chk=$db->prepare("SELECT id FROM companies WHERE name=? AND stakeholder=?");$chk->execute([$c['name'],$c['stakeholder']]);}
            if($chk->fetch()){$skipped++;continue;}
        }catch(Exception $e){$errors[]=$c['name'].' (check): '.$e->getMessage();continue;}
        
        // Insert
        try {
            $s=$db->prepare("INSERT INTO companies (name,industry_id,province_id,stakeholder,listing_type,phone,email,website,logo,description,is_active) VALUES (?,?,?,?,?,?,?,?,?,?,1)");
            $s->execute([$c['name'],(int)$c['industry_id'],(int)$c['province_id'],$c['stakeholder'],$c['listing_type'],$c['phone'],$c['email'],$c['website'],$c['logo'],$c['description']]);
            $imported++;
        }catch(Exception $e){$errors[]=$c['name'].': '.$e->getMessage();}
    }
    $typeLabel = $importType==='industry'?'Industry Listings':$importStakeholder;
    ?>
    <div style="padding:20px;">
        <div class="alert" style="background:#edfaef;border:1px solid #b4d9b9;border-radius:3px;"><h5><i class="bi bi-check-circle"></i> Import Complete! (<?php echo $typeLabel; ?>)</h5>
            <div class="row mt-3"><div class="col-md-4"><div style="text-align:center;padding:12px;background:#fff;border-radius:3px;"><h2 style="color:#007017;"><?php echo $imported; ?></h2><small>Imported</small></div></div><div class="col-md-4"><div style="text-align:center;padding:12px;background:#fff;border-radius:3px;"><h2 style="color:#996800;"><?php echo $skipped; ?></h2><small>Skipped</small></div></div><div class="col-md-4"><div style="text-align:center;padding:12px;background:#fff;border-radius:3px;"><h2 style="color:#d63638;"><?php echo count($errors); ?></h2><small>Errors</small></div></div></div>
            <?php if(!empty($errors)): ?><div class="mt-3"><h6>Errors (first 10):</h6><ul style="font-size:12px;"><?php foreach(array_slice($errors,0,10) as $e)echo"<li>$e</li>"; ?></ul></div><?php endif; ?>
            <div class="mt-3"><a href="members.php" class="btn" style="background:#2271b1;color:#fff;border:none;border-radius:3px;padding:6px 14px;">View Members</a> <a href="import-csv.php" class="btn" style="background:#fff;border:1px solid #c3c4c7;color:#2271b1;border-radius:3px;padding:6px 14px;">Import Another</a></div>
        </div>
    </div>

    <?php else: ?>
    <div style="padding:20px;">
        <div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:24px;max-width:700px;">
            <h5 style="font-weight:600;font-size:14px;margin-bottom:16px;"><i class="bi bi-upload"></i> Upload CSV File</h5>
            <form method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6"><label class="fw-bold" style="font-size:13px;">Import Type *</label><select name="import_type" id="importType" class="form-select" onchange="toggleStakeholder()" style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;"><option value="partner">Partner Directory (CZI/CIFOZ)</option><option value="industry">Industry Listings (industry.co.zw)</option></select></div>
                    <div class="col-md-6" id="stakeholderGroup"><label class="fw-bold" style="font-size:13px;">Stakeholder *</label><select name="import_stakeholder" class="form-select" style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;"><option value="CZI">CZI</option><option value="CIFOZ">CIFOZ</option></select></div>
                </div>
                <div class="form-group mb-3"><label class="fw-bold" style="font-size:13px;">Select CSV File *</label><input type="file" name="csv_file" class="form-control" accept=".csv,.tsv,.txt" required style="border:1px solid #8c8f94;border-radius:3px;font-size:13px;"></div>
                <button type="submit" name="preview" value="1" class="btn" style="background:#2271b1;color:#fff;border:none;border-radius:3px;padding:8px 20px;font-size:14px;"><i class="bi bi-eye"></i> Preview Before Import</button>
            </form>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>function toggleStakeholder(){document.getElementById('stakeholderGroup').style.display=document.getElementById('importType').value==='partner'?'block':'none';}</script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>