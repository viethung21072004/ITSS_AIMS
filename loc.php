<?php
$gmail = "https://www.youtube.com/watch?v=DSC3_pxUa2c&list=PLaevEBkXyvnXEMoe6ZHFJGjPDb_eCCVNc&index=68";
$gmail = filter_var($gmail,FILTER_SANITIZE_URL);
if(filter_var( $gmail, FILTER_VALIDATE_URL)){
    echo $gmail;
}else{
    echo "Not email";
}
?>