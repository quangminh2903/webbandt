<?php
echo !empty($errors['email']['required']) ? '<br><em class="error-notice" style="color:red;">&nbsp;' . $errors['email']['required'] . '</em>' : '';

echo !empty($errors['email']['not_valid']) ? '<br><em class="error-notice" style="color:red;">&nbsp;' . $errors['email']['not_valid'] . '</em>' : '';

echo !empty($errors['email']['exist']) ? '<br><em class="error-notice" style="color:red;">&nbsp;' . $errors['email']['exist'] . '</em>' : '';