<?php
/**
 * Update Image Assets with exact matching photos
 */
$base = __DIR__ . '/images/';
$brain = 'C:/Users/Puzzz/.gemini/antigravity/brain/98f5cfd7-d22f-47b3-a434-bed3f08d8078/';

$heroImg = $brain . 'hero_homefix_1786802600405.jpg';
$plumbImg = $brain . 'plumbing_serv_1786802647682.jpg';
$geyserImg = $brain . 'geyser_repair_1786805428911.jpg';
$solarImg = $brain . 'solar_inverter_1786805460553.jpg';
$paintImg = $brain . 'wall_painting_1786805496402.jpg';

// Categories
if (file_exists($plumbImg)) copy($plumbImg, $base . 'categories/plumbing.jpg');
if (file_exists($heroImg)) copy($heroImg, $base . 'categories/electrical.jpg');
if (file_exists($paintImg)) copy($paintImg, $base . 'categories/painting.jpg');
if (file_exists($heroImg)) copy($heroImg, $base . 'categories/handyman.jpg');

// Services - Plumbing
if (file_exists($plumbImg)) copy($plumbImg, $base . 'services/plumbing_leak.jpg');
if (file_exists($geyserImg)) copy($geyserImg, $base . 'services/geyser.jpg');
if (file_exists($plumbImg)) copy($plumbImg, $base . 'services/tank_cleaning.jpg');

// Services - Electrical
if (file_exists($heroImg)) copy($heroImg, $base . 'services/electrical_diag.jpg');
if (file_exists($solarImg)) copy($solarImg, $base . 'services/ups_solar.jpg');
if (file_exists($heroImg)) copy($heroImg, $base . 'services/lights.jpg');
if (file_exists($heroImg)) copy($heroImg, $base . 'services/earthing.jpg');

// Services - Painting
if (file_exists($paintImg)) copy($paintImg, $base . 'services/room_painting.jpg');
if (file_exists($paintImg)) copy($paintImg, $base . 'services/house_painting.jpg');
if (file_exists($paintImg)) copy($paintImg, $base . 'services/waterproofing.jpg');

// Services - Handyman
if (file_exists($heroImg)) copy($heroImg, $base . 'services/handyman.jpg');
if (file_exists($heroImg)) copy($heroImg, $base . 'services/tv_mounting.jpg');

// Technicians
if (file_exists($plumbImg)) copy($plumbImg, $base . 'technicians/tech1.jpg');
if (file_exists($heroImg)) copy($heroImg, $base . 'technicians/tech2.jpg');
if (file_exists($geyserImg)) copy($geyserImg, $base . 'technicians/tech3.jpg');
if (file_exists($heroImg)) copy($heroImg, $base . 'technicians/tech4.jpg');
if (file_exists($paintImg)) copy($paintImg, $base . 'technicians/tech5.jpg');
if (file_exists($heroImg)) copy($heroImg, $base . 'technicians/tech6.jpg');

// Gallery
if (file_exists($plumbImg)) {
    copy($plumbImg, $base . 'gallery/plumb_after.jpg');
    copy($plumbImg, $base . 'gallery/plumb_before.jpg');
}
if (file_exists($heroImg)) {
    copy($heroImg, $base . 'gallery/db_after.jpg');
    copy($heroImg, $base . 'gallery/db_before.jpg');
}
if (file_exists($paintImg)) {
    copy($paintImg, $base . 'gallery/paint_after.jpg');
    copy($paintImg, $base . 'gallery/paint_before.jpg');
}

echo "Matching images updated successfully.\n";
