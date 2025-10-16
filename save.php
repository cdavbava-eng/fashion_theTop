<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $type = $_POST['type'];
    $province = $_POST['province'];
    $city = $_POST['city'];
    $address = $_POST['address'] ?? '';
    $notes = $_POST['notes'] ?? '';

    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    $file = fopen('orders.csv', 'a');

    // اكتب الرأس إذا كان الملف فارغ
    if (filesize('orders.csv') === 0) {
        fputcsv($file, [
            'اسم الزبون', 'رقم الهاتف', 'نوع الطلب', 'المحافظة', 'القضاء', 
            'الموقع', 'صورة القطعة', 'لون القطعة', 'حجم اللون', 'ملاحظات'
        ]);
    }

    // معالجة كل القطع
    if (!empty($_FILES['image']['name'])) {
        foreach ($_FILES['image']['name'] as $i => $imageNameOriginal) {
            $imagePath = '';
            if (!empty($imageNameOriginal)) {
                $imagePath = 'uploads/' . time() . '_' . basename($imageNameOriginal);
                move_uploaded_file($_FILES['image']['tmp_name'][$i], $imagePath);
            }

            // الحصول على ألوان وحجم كل لون
            $colorsKey = "colors_of_piece{$i}";
            $sizesKey = "sizes_of_color{$i}";

            $colors = $_POST[$colorsKey] ?? [];
            $sizes = $_POST[$sizesKey] ?? [];

            $numColors = max(count($colors), count($sizes));

            for ($j = 0; $j < $numColors; $j++) {
                $color = $colors[$j] ?? '';
                $size = $sizes[$j] ?? '';

                fputcsv($file, [
                    $name, $phone, $type, $province, $city,
                    $address, $imagePath, $color, $size, $notes
                ]);
            }
        }
    }

    fclose($file);
    echo "<script>alert('✅ تم حفظ الطلب بنجاح!'); window.location.href='index.html';</script>";
}
?>