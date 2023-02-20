<?php
echo !empty($errors['username']['required']) ? '<br><em class="error-notice" style="color:red;">&nbsp;' . $errors['username']['required'] . '</em>' : '';

echo !empty($errors['username']['min_length']) ? '<br><em class="error-notice" style="color:red;">&nbsp;' . $errors['username']['min_length'] . '</em>' : '';

echo !empty($errors['username']['exist']) ? '<br><em class="error-notice" style="color:red;">&nbsp;' . $errors['username']['exist'] . '</em>' : '';
