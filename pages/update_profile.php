<?php

if ($stmt->execute()) {
    if ($stmt->rowCount() > 0) {
        // تم التحديث بنجاح
        header('Location: profile.php?success=1');
        exit();
    } else {
        // لم يتم تحديث أي صف (ربما لم تتغير البيانات)
        header('Location: profile.php?error=not_updated');
        exit();
    }
} else {
    // حدث خطأ في التنفيذ
    header('Location: profile.php?error=update_failed');
    exit();
} 