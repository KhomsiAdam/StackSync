<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Favicon -->
<link rel="icon" href="favicon.ico">
<link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">

<!-- Google / Search Engine Tags -->
<meta itemprop="name" content="StackSync | Fullstack Mini-Framework">
<meta itemprop="description" content="StackSync is a mini-framework. PHP with an MVC architecture and a custom API and components view system and templating layouts for the Web. SASS Architecture based on the 7-1 methodology and BEM convention with ES6 JavaScript modules.">
<meta itemprop="image" content="images/stacksync-social.svg">

<!-- Facebook Meta Tags -->
<meta property="og:url" content="">
<meta property="og:type" content="website">
<meta property="og:title" content="StackSync | Fullstack Mini-Framework">
<meta property="og:description" content="StackSync is a mini-framework. PHP with an MVC architecture and a custom API and components view system and templating layouts for the Web. SASS Architecture based on the 7-1 methodology and BEM convention with ES6 JavaScript modules.">
<meta property="og:image" content="images/stacksync-social.svg">

<!-- Twitter Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="StackSync | Fullstack Mini-Framework">
<meta name="twitter:description" content="StackSync is a mini-framework. PHP with an MVC architecture and a custom API and components view system and templating layouts for the Web. SASS Architecture based on the 7-1 methodology and BEM convention with ES6 JavaScript modules.">
<meta name="twitter:image" content="images/stacksync-social.svg">
<meta name="twitter:site" content="@AdamKhomsi" />
<meta name="twitter:creator" content="@AdamKhomsi" />

<!-- Global CSS and JS -->
<link rel="stylesheet" href="/css/main.css">
<script src="/js/app.js" type="module" defer></script>

<!-- Google Fonts Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<?php
// Meta tags depending on current page
switch ($_SERVER['REQUEST_URI']) {
    case '/': 
    case '/home': ?>
        <!-- Title & Description -->
        <title>StackSync | Fullstack Mini-Framework</title>
        <meta name="description" content="StackSync is a mini-framework. PHP with an MVC architecture and a custom API and components view system and templating layouts for the Web. SASS Architecture based on the 7-1 methodology and BEM convention with ES6 JavaScript modules.">
        <?php break;
    }
?>