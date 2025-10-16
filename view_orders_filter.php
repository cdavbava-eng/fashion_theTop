<?php
$filename = 'orders.csv';
$orders = [];

if (file_exists($filename)) {
    if (($handle = fopen($filename, "r")) !== FALSE) {
        $header = fgetcsv($handle);
        while (($data = fgetcsv($handle)) !== FALSE) {
            $orders[] = array_combine($header, $data);
        }
        fclose($handle);
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>عرض الطلبات</title>
<style>
body { font-family: "Tajawal", sans-serif; background: #f9f9f9; padding: 20px; }
.container { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1);}
th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
th { background: #007BFF; color: #fff; }
img { max-width: 80px; max-height: 80px; }
input { padding: 8px; margin-bottom: 10px; width: 100%; border-radius: 6px; border: 1px solid #ccc; }
</style>
</head>
<body>
<h2 style="text-align:center;">📦 قائمة الطلبات</h2>

<label>🔍 ابحث باسم الزبون أو نوع الطلب:</label>
<input type="text" id="searchInput" placeholder="اكتب هنا للبحث...">

<div class="container">
<table id="ordersTable">
<tr>
<?php foreach($header as $col) { echo "<th>{$col}</th>"; } ?>
</tr>
<?php foreach($orders as $order): ?>
<tr>
<?php foreach($order as $key => $value): ?>
<td>
<?php 
if($key == 'صورة القطعة' && !empty($value) && file_exists($value)){
    echo "<img src='{$value}' alt='صورة'>";
} else {
    echo htmlspecialchars($value);
}
?>
</td>
<?php endforeach; ?>
</tr>
<?php endforeach; ?>
</table>
</div>

<script>
// وظيفة البحث
document.getElementById("searchInput").addEventListener("keyup", function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#ordersTable tr:not(:first-child)");
    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
});
</script>
</body>
</html>