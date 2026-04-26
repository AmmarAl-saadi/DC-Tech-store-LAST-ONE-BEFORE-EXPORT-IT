{{-- Load the Nuxt frontend entry point --}}
<?php 
if (file_exists(public_path('index.html'))) {
    include public_path('index.html');
} else {
    echo "Frontend entry point (index.html) not found. Please ensure the frontend is built.";
}
?>
