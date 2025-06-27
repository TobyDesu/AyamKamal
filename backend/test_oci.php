<?php
if (function_exists('oci_connect')) {
    echo "OCI8 is enabled!";
} else {
    echo "OCI8 is NOT enabled.";
}