<?php
include("./api/config.php");

// Fetch data for toilets
$toiletSql = "SELECT * FROM toilet";
$toiletStmt = $db->query($toiletSql);
$toilets = $toiletStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch data for checklists
$checklistSql = "SELECT c.*, t.lokasi FROM checklist c
                JOIN toilet t ON c.toilet_id = t.id";
$checklistStmt = $db->query($checklistSql);
$checklists = $checklistStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Data Toilet</h2>
    <?php if (!empty($toilets)): ?>
        <ul>
            <?php foreach ($toilets as $toilet): ?>
                <li><?php echo $toilet['lokasi']; ?> - <?php echo $toilet['keterangan']; ?></li>

                <?php
                $currentChecklist = array_filter($checklists, function ($checklist) use ($toilet) {
                    return $checklist['toilet_id'] == $toilet['id'];
                });

                if (!empty($currentChecklist)) {
                    $checklistData = reset($currentChecklist);
                ?>
                    <p>Checked on: <?php echo $checklistData['tanggal']; ?></p>
                    <p>Kloset: <?php echo $checklistData['kloset'] ? 'Yes' : 'No'; ?></p>
                    <p>Wastafel: <?php echo $checklistData['wastafel'] ? 'Yes' : 'No'; ?></p>
                    <p>Lantai: <?php echo $checklistData['lantai'] ? 'Yes' : 'No'; ?></p>
                    <p>Dinding: <?php echo $checklistData['dinding'] ? 'Yes' : 'No'; ?></p>
                    <p>Kaca: <?php echo $checklistData['kaca'] ? 'Yes' : 'No'; ?></p>
                    <p>Bau: <?php echo $checklistData['bau'] ? 'Yes' : 'No'; ?></p>
                    <p>Sabun: <?php echo $checklistData['sabun'] ? 'Yes' : 'No'; ?></p>
                <?php } else { ?>
                    <p>No checklist data available for this toilet.</p>
                <?php } ?>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No toilet data available.</p>
    <?php endif; ?>