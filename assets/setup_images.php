<?php
/**
 * Image Asset Setup for HomeFix Quetta
 */
$base = __DIR__ . '/images/';
$brain = 'C:/Users/Puzzz/.gemini/antigravity/brain/98f5cfd7-d22f-47b3-a434-bed3f08d8078/';

$dirs = ['categories', 'services', 'technicians', 'gallery', 'avatars'];
foreach ($dirs as $d) {
    if (!is_dir($base . $d)) {
        mkdir($base . $d, 0777, true);
    }
}

// Generated photorealistic images
$hero = $brain . 'hero_homefix_1786802600405.jpg';
$ac = $brain . 'ac_service_1786802627211.jpg';
$plumb = $brain . 'plumbing_serv_1786802647682.jpg';

if (file_exists($hero)) {
    copy($hero, $base . 'hero_homefix.jpg');
    copy($hero, $base . 'categories/electrical.jpg');
    copy($hero, $base . 'services/electrical_diag.jpg');
    copy($hero, $base . 'services/ups_solar.jpg');
    copy($hero, $base . 'services/lights.jpg');
    copy($hero, $base . 'services/washing_machine.jpg');
    copy($hero, $base . 'services/handyman.jpg');
    copy($hero, $base . 'gallery/db_after.jpg');
    copy($hero, $base . 'gallery/db_before.jpg');
    copy($hero, $base . 'technicians/tech2.jpg');
    copy($hero, $base . 'technicians/tech6.jpg');
    copy($hero, $base . 'technicians/tech8.jpg');
    copy($hero, $base . 'avatars/admin.jpg');
}

if (file_exists($ac)) {
    copy($ac, $base . 'categories/ac.jpg');
    copy($ac, $base . 'categories/appliances.jpg');
    copy($ac, $base . 'categories/handyman.jpg');
    copy($ac, $base . 'services/ac_service.jpg');
    copy($ac, $base . 'services/ac_gas.jpg');
    copy($ac, $base . 'services/door_lock.jpg');
    copy($ac, $base . 'services/furniture.jpg');
    copy($ac, $base . 'gallery/ac_after.jpg');
    copy($ac, $base . 'gallery/ac_before.jpg');
    copy($ac, $base . 'technicians/tech3.jpg');
    copy($ac, $base . 'technicians/tech4.jpg');
}

if (file_exists($plumb)) {
    copy($plumb, $base . 'categories/plumbing.jpg');
    copy($plumb, $base . 'categories/carpentry.jpg');
    copy($plumb, $base . 'categories/painting.jpg');
    copy($plumb, $base . 'categories/cleaning.jpg');
    copy($plumb, $base . 'services/plumbing_leak.jpg');
    copy($plumb, $base . 'services/geyser.jpg');
    copy($plumb, $base . 'services/tank_cleaning.jpg');
    copy($plumb, $base . 'services/room_painting.jpg');
    copy($plumb, $base . 'services/waterproofing.jpg');
    copy($plumb, $base . 'services/house_cleaning.jpg');
    copy($plumb, $base . 'services/sofa_cleaning.jpg');
    copy($plumb, $base . 'gallery/plumb_after.jpg');
    copy($plumb, $base . 'gallery/plumb_before.jpg');
    copy($plumb, $base . 'gallery/paint_after.jpg');
    copy($plumb, $base . 'gallery/paint_before.jpg');
    copy($plumb, $base . 'technicians/tech1.jpg');
    copy($plumb, $base . 'technicians/tech5.jpg');
    copy($plumb, $base . 'technicians/tech7.jpg');
    copy($plumb, $base . 'avatars/farhan.jpg');
}

echo "Image assets copied successfully.\n";
